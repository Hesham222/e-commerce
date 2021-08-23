<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            //حطينا الماكس 100 عشان انا ف الداتا بيز عامل الفاركار ب 100 للاسم
            'name' => 'required|string|max:100',
            'abbr' => 'required|string|max:10',
            //'active' => 'required|in:0,1',
            'direction' => 'required|in:rtl,ltr',

        ];
    }
    public function messages()
    {
        return [
            'required' =>'هذا الحقل مطلوب' ,
            'in' => 'القيم المدخله غير صحيحه',
            'name.string' => 'هذا الحقل يجب ان يكون احرف',
            'abbr.max' => 'هذا الحقل يجب ان لا يزيد عن 10',
            'abbr.string' => 'هذا الحقل يجب ان يكون احرف',
            'name.max' => 'هذا الحقل يجب ان لايزيد عن 100 حرف',


        ];
    }
}
