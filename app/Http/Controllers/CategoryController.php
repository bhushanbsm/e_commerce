<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\AddCategoryRequest;
use App\Http\Requests\Categories\DeleteCategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Models\Category;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller {
    /**
     * Loads view for categories
     */
    public function index() {
        return view('pages.Categories.categories');
    }

    /**
     * Generates a tree of all categories from root and sends json response
     * @return json
     */
    public function list() {
        $categories = Category::get()->toTree();

        return response(
            [
                "code" => "200",
                "status" => "success",
                "data" => $categories,
            ],
            200
        );
    }    

    /**
     * Generates a tree of all categories from root and sends json response
     * @return json
     */
    public function sub_categories($id) {
        $categories = Category::where('parent_id', $id)->get()->toFlatTree();

        return response(
            [
                "code" => "200",
                "status" => "success",
                "data" => $categories,
            ],
            200
        );
    }

    /**
     * Fetches details of supplied category id
     * @param  int    $id category id
     * @return json
     */
    public function show($id) {
        $categories = Category::firstWhere('id', $id);

        return response(
            [
                "code" => "200",
                "status" => "success",
                "data" => $categories,
            ],
            200
        );
    }

    /**
     * Creates new category in database with following fields
     * @param  AddCategoryRequest $request
     * @param  string             name             unique name for category
     * @param  string             description      description for category
     * @param  string             meta_title       title for seo meta details
     * @param  string             meta_description description for seo desction
     * @param  string             keywords         comma seperated list of keywords for seo
     * @param  int                parent_id        if new category is child then parent category id
     * @return json
     */
    public function store(AddCategoryRequest $request) {
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['slug'] = Str::slug($request->name);
        $data['meta_title'] = $request->meta_title ?? null;
        $data['meta_description'] = $request->meta_description ?? null;
        $data['meta_keywords'] = $request->meta_keywords ?? null;

        // start transaction
        DB::beginTransaction();
        try {
            if (empty($request->parent_id)) {
                $category = Category::create($data);
            } else {
                $parent = Category::firstWhere('id', $request->parent_id);
                $category = Category::create($data, $parent);
            }

            if (!$category) {
                DB::rollBack();
                return response(
                    [
                        "code" => "400",
                        "status" => "failed",
                        "msg" => 'unable to add category',
                    ],
                    400
                );
            }

            Category::where('id', $category->id)->update(['category_id' => "CATID" . $category->id]);

            DB::commit();
            return response(
                [
                    "code" => "200",
                    "status" => "success",
                    "data" => $category,
                ],
                200
            );
        } catch (Exceprion $e) {
            DB::rollBack();
            return response(
                [
                    "code" => "400",
                    "status" => "failed",
                    "msg" => 'unable to update category',
                ],
                400
            );
        }
    }

    /**
     * Update category in database with following fields
     * @param  UpdateCategoryRequest $request
     * @param  int                   id               id of category to update
     * @param  string                name             unique name for category
     * @param  string                description      description for category
     * @param  string                meta_title       title for seo meta details
     * @param  string                meta_description description for seo desction
     * @param  string                keywords         comma seperated list of keywords for seo
     * @param  int                   parent_id        if new category is child then parent category id
     * @return json
     */
    public function update(UpdateCategoryRequest $request) {
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['slug'] = Str::slug($request->name);
        $data['meta_title'] = $request->meta_title ?? null;
        $data['meta_description'] = $request->meta_description ?? null;
        $data['meta_keywords'] = $request->meta_keywords ?? null;
        $id = $request->id;

        // start transaction
        DB::beginTransaction();
        try {
            $category = Category::where('id', $id)->update($data);

            if (!$category) {
                DB::rollBack();
                return response(
                    [
                        "code" => "400",
                        "status" => "failed",
                        "msg" => 'unable to update category',
                    ],
                    400
                );
            }

            DB::commit();
            return response(
                [
                    "code" => "200",
                    "status" => "success",
                    "data" => $category,
                ],
                200
            );
        } catch (Exceprion $e) {
            DB::rollBack();
            return response(
                [
                    "code" => "400",
                    "status" => "failed",
                    "msg" => 'unable to update category',
                ],
                400
            );
        }
    }

    /**
     * Delete category from database.
     * It will its child also.
     * @param  DeleteCategoryRequest $request
     * @param  int                   id         Category id tobe deleted
     * @return json
     */
    public function destroy(DeleteCategoryRequest $request) {
        $id = $request->id;
        // start transaction
        DB::beginTransaction();
        try {
            $status = Category::firstWhere('id', '=', $id);
            $status = $status->delete();
            if (!$status) {
                DB::rollBack();
                return response(
                    [
                        "code" => "400",
                        "status" => "failed",
                        "msg" => "unable to delete category",
                    ],
                    400
                );
            }

            DB::commit();
            return response(
                [
                    "code" => "200",
                    "status" => "success",
                    "msg" => "category deleted successfully",
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
