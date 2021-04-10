<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function validationData() {
        return $this->route()->parameters() + $this->all(); 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|numeric|exists:products,id',
            'name' => 'required|string|min:4',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:categories,id',
            'color_id' => 'required|exists:product_colors,id',
            'cost' => 'required|numeric',
            'discount' => 'required|numeric',
            'images' => 'array|min:1',
            'images.*' => 'image|mimes:jpg,png|max:2048'
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(response()->json(['code'=>412,'status'=>'failed','msg'=>$validator->errors()->first()], 412));
    }
}
