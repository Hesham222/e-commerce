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
            'mobile' => 'required|max:100',
            'email' => 'sometimes|nullable|email',
            'address' => 'required|string|max:500',
            'category_id' => 'required|exists:main_categories,id',
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
            'logo.required_without' => '    الصوره مطلوبه',


        ];
    }
}
