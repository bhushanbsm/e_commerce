<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddResumeRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'first_name' => 'required|string|min:4',
            'last_name' => 'required|string|min:4',
            'address_line' => 'required|string|min:4',
            'zipcode' => 'required|string|min:6',
            'about' => 'required|string|min:4',
            'username' => 'required|string|min:4|unique:resumes,username',
            'email' => 'required|email|min:4|unique:resumes,email',
            'document' => 'required|mimes:jpg,png,pdf|max:2048'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['code'=>412,'status'=>'failed','msg'=>$validator->errors()->first()], 412));
    }
}
