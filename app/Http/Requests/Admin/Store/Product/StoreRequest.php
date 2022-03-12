<?php

namespace App\Http\Requests\Admin\Store\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'price' => 'required',
            'image' => 'required|image',
            'description' => 'required',
            'is_active' => 'required|boolean',
            'category_id' => 'required'
        ];
        if ($this->method() == 'PUT')
        {
            unset($validations_rules['image']);
        }
        return $validations_rules;
    }
}
