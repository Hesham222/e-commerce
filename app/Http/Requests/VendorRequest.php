<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'logo' => 'required_without:id|mimes:jpg,jpeg,png',
            'mobile' => 'required|max:100|unique:vendors,mobile,'.$this -> id,
            'email' => 'required|email|unique:vendors,email,'.$this -> id, //بقوله unique الا ف حاله انه نفس اليوزر
            'address' => 'required|string|max:500',
            'category_id' => 'required|exists:main_categories,id',
            'password' => 'required_without:id|',
        ];
    }

    public function messages()
    {
        return [
            'required' =>'هذا الحقل مطلوب' ,
            'max' => 'هذ الحقل طويل',
            'name.string' => 'هذا الحقل يجب ان يكون احرف',
            'address.string' => ' العنوان لابد ان يكون حروف',
            'category_id.exists' => 'القسم غير موجود',
            'email.email' => 'سيغه البريد الالكتروني غير صحيحه',
            'email.unique' => ' البريد الالكتروني  مستخدم من قبل',
            'mobile.unique' => ' رقم الهاتف مستخدم من قبل',
            'logo.required_without' => 'الصوره مطلوبه',


        ];
    }
}
