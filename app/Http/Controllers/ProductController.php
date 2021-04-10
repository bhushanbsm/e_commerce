<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\AddProductRequest;
use App\Http\Requests\Products\DeleteProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller {
    /**
     * Loads view for products with data
     */
    public function index() {
        $products = Product::with(['product_color:name,id', 'category:name,id', 'sub_category:name,id', 'product_image'])
            ->where('status', 1)
            ->get();
        return view('pages.dashboard', compact('products'));
    }

    /**
     * Show create new product page
     * @return view
     */
    public function create() {
        $colors = ProductColor::select('id', 'name')
            ->where('status', 1)
            ->get();
        $categories = Category::select('id', 'name')
            ->where('parent_id', null)
            ->where('status', 1)
            ->get();
        return view('pages.Products.add', compact('colors', 'categories'));
    }

    /**
     * Stores new product in data
     * @param  AddProductRequest $request
     * @param  int               category_id     Category id of category table
     * @param  int               sub_category_id Sub Category id of category table
     * @param  int               color_id        Color option for product, id of product_color table
     * @param  string            name            name for product
     * @param  decimal           cost            Pricing of product
     * @param  decimal           discount        Discount to give on product
     * @param  file              images[]        images array for product
     * @return json
     */
    public function store(AddProductRequest $request) {
        $data['name'] = $request->name;
        $data['category_id'] = $request->category_id;
        $data['sub_category_id'] = $request->sub_category_id;
        $data['color_id'] = $request->color_id;
        $data['cost'] = $request->cost;
        $data['discount'] = $request->discount;

        // start transaction
        DB::beginTransaction();
        try {
            $product = Product::create($data);

            if (!$product) {
                DB::rollBack();
                return response(
                    [
                        "code" => "400",
                        "status" => "failed",
                        "msg" => 'unable to add product',
                    ],
                    400
                );
            }

            $this->uploadImages($request, $product->id);

            DB::commit();
            return response(
                [
                    "code" => "200",
                    "status" => "success",
                    "data" => $product,
                ],
                200
            );
        } catch (Exceprion $e) {
            DB::rollBack();
            return response(
                [
                    "code" => "400",
                    "status" => "failed",
                    "msg" => 'unable to add product',
                ],
                400
            );
        }
    }

    /**
     * View page for product edit
     * @param  int    $id product id to edit from product table
     * @return view
     */
    public function show($id) {
        $product = Product::with(['product_color:name,id', 'category:name,id', 'sub_category:name,id', 'product_images'])
            ->where('status', 1)
            ->where('id', $id)
            ->first();

        $colors = ProductColor::select('id', 'name')
            ->where('status', 1)
            ->get();

        $categories = Category::select('id', 'name')
            ->where('parent_id', null)
            ->where('status', 1)
            ->get();

        $sub_categories = Category::select('id', 'name')
            ->where('parent_id', $product->category_id)
            ->where('status', 1)
            ->get()
            ->toFlatTree();

        return view('pages.Products.edit', compact('product', 'colors', 'categories', 'sub_categories'));
    }

    /**
     * Updates product in data by id
     * @param  UpdateProductRequest $request
     * @param  int                  category_id     Category id of category table
     * @param  int                  sub_category_id Sub Category id of category table
     * @param  int                  color_id        Color option for product, id of product_color table
     * @param  string               name            name for product
     * @param  decimal              cost            Pricing of product
     * @param  decimal              discount        Discount to give on product
     * @param  file                 images[]        images array for product
     * @return json
     */
    public function update(UpdateProductRequest $request) {
        $data['name'] = $request->name;
        $data['category_id'] = $request->category_id;
        $data['sub_category_id'] = $request->sub_category_id;
        $data['color_id'] = $request->color_id;
        $data['cost'] = $request->cost;
        $data['discount'] = $request->discount;
        $id = $request->id;

        // start transaction
        DB::beginTransaction();
        try {
            $product = Product::where('id', $id)->update($data);

            if (!$product) {
                DB::rollBack();
                return response(
                    [
                        "code" => "400",
                        "status" => "failed",
                        "msg" => 'unable to update product',
                    ],
                    400
                );
            }

            $this->uploadImages($request, $id);

            DB::commit();
            return response(
                [
                    "code" => "200",
                    "status" => "success",
                    "data" => $product,
                ],
                200
            );
        } catch (Exceprion $e) {
            DB::rollBack();
            return response(
                [
                    "code" => "400",
                    "status" => "failed",
                    "msg" => 'unable to update product',
                ],
                400
            );
        }
    }

    /**
     * Helper method to upload images to storage and database
     * @param  Request $request
     * @param  int     id         product id of products table
     * @return null
     */
    public function uploadImages($request, $id) {
        $files = [];
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('files'), $name);
                $files[] = ['path' => $name, 'product_id' => $id];
            }
        }

        $productImage = ProductImage::insert($files);
    }

    /**
     * Deletes product from products table
     * @param  DeleteProductRequest $request
     * @param  int                  id       product id
     * @return json
     */
    public function destroy(DeleteProductRequest $request) {
        $id = $request->id;
        // start transaction
        DB::beginTransaction();
        try {
            $status = Product::where('id', '=', $id)->delete();
            ProductImage::where('product_id', '=', $id)->delete();

            if (!$status) {
                DB::rollBack();
                return response(
                    [
                        "code" => "400",
                        "status" => "failed",
                        "msg" => "unable to delete product",
                    ],
                    400
                );
            }

            DB::commit();
            return response(
                [
                    "code" => "200",
                    "status" => "success",
                    "msg" => "product deleted successfully",
                ],
                200
            );
        } catch (Exceprion $e) {
            DB::rollBack();
            return response(
                [
                    "code" => "400",
                    "status" => "failed",
                    "msg" => $e->getMessage(),
                ],
                400
            );
        }
    }

}
