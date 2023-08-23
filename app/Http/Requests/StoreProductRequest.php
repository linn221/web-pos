<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:20',
            // @fix?
            'actual_price' => 'required|min:1|numeric',
            'sale_price' => 'required|min:1|numeric',
            // 'total_stock' => 'nullable|numeric',
            'unit' => 'required',
            'more_information' => 'nullable',
            'brand_id' => 'required|exists:brands,id',
            // @fix check if photo exists in the media
            'photo' => 'nullable'
            // 'photo' => 'nullable|mimes:png,jpeg,gif'
        ];
    }
}
