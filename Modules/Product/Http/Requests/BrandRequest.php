<?php

namespace Modules\Product\Http\Requests;

use App\Rules\BlackListRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'max:50', 'regex:/^([a-z A-Z]{1,30})$/'],
            'persian_name' => ['required', 'max:50', 'regex:/^([ضصثقفغعهخحجچشسیبلاتنمکگپظطزرذدئو.ءِ]{1,30})$/'],
            'description' => ['required', 'max:5000', new BlackListRule()],
            'image' => 'required',
            'status' => [Rule::in([1, 2])],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'نام برند (انگلیسی)',
            'persian_name' => 'نام برند (فارسی)',
            'description' => 'توضیحات برند',
            'image' => 'تصویر برند',
            'status' => 'وضعیت',
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'نام برند باید به صورت حروف انگلیسی باشد',
            'persian_name.regex' => 'نام برند باید به صورت حروف فارسی باشد',
            'image.image' => 'فرمت تصویر نامعتبر است'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
