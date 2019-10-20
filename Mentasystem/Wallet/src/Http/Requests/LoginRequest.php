<?php

namespace Modules\Account\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class loginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
//            "email" => "required|email",
//            "password" => "required|min:8",
        ];
    }

    public function messages()
    {
        return [
            "email.email" => 'قالب ایمیل صحیح نمی باشد',
            "email.required" => 'فیلد ایمیل اجباری میباشد',
            "password.required" => 'فیلد پسوورد اجباری می باشد',
            "password.min" => 'حداقل تعداد کاراکتر های پسوورد ۸ عدد میباشد'
        ];

    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
