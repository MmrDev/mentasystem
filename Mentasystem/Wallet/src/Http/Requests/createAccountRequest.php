<?php

namespace Modules\Account\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createAccountRequest extends FormRequest
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
            /*            'name' => 'required|string',
                        'email' => 'required|email|unique:accounts,email',
                        'phone' => 'int',
                        'password' => 'min:8|required',
                        'type' => 'string',
                        'status' => 'string'*/
        ];
    }

    /**
     * Get the validation error message.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'فیلد نام اجباری است',
            'name.string' => 'نام باید شامل کاراکتر باشد',
            'email.required' => 'ایمیل اجباری است',
            'email.email' => 'قالب ایمیل درست نیست',
            'email.unique' => 'این ایمیل دردسترس نمی باشد',
            'phone.int' => 'شماره باید شامل اعداد باشد',
            'phone.max' => 'شماره نباید بیشتر از ۱۲ رقم شود',
            'password.min' => 'حداقل پسورد ۸ رقم است ',
            'password.required' => 'وارد کردن پسورد اجباری است',
            'credit.float' => 'نوع داده باید اعشاری باشد',
            'type.string' => 'نوع داده باید کاراکتر باشد',
            'status.string' => 'نوع داده باید کاراکتر باشد',
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
