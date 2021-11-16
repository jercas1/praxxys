<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class UpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'category' => 'required|in:Convenience goods,Shopping goods,Specialty goods',
            'description' => 'required|string|max:4294967295',
            'images.*' => 'required|mimetypes:image/png,image/jpeg,image/jpg|max:102400',
            'images' => [
                new RequiredIf(!$this->request->has('old_files')),
            ],
            'datetime' => 'required',
        ];
    }
}
