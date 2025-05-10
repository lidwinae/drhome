<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreDesignRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'photo' => [
                'required',
                File::types(['jpeg', 'png'])
                    ->max(16 * 1024),
            ],
        ];
    }

    public function messages()
    {
        return [
            'photo.required' => 'Foto design wajib diupload',
            'photo.mimes' => 'Hanya file JPEG dan PNG yang diperbolehkan',
            'photo.max' => 'Ukuran file maksimal 16MB',
        ];
    }
}