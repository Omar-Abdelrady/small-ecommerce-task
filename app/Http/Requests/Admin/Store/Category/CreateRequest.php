<?php

namespace App\Http\Requests\Admin\Store\Category;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
        $validations_rules =
            [
            'name' => 'required|string',
            'image' => 'required|image',
            'is_active' => 'required|boolean'
            ];
        if ($this->method() == 'PUT')
        {
            unset($validations_rules['image']);
        }
        return $validations_rules;
    }
}
