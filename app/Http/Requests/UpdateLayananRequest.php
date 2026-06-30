<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateLayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPetugas() === true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_layanan' => ['required', 'string', 'max:150'],
            'kategori_layanan' => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'persyaratan' => ['nullable', 'array'],
            'persyaratan.*' => ['nullable', 'string', 'max:255'],
            'prosedur' => ['nullable', 'array'],
            'prosedur.*' => ['nullable', 'string', 'max:255'],
            'jam_layanan' => ['nullable', 'string', 'max:100'],
            'biaya' => ['nullable', 'string', 'max:100'],
            'kontak_layanan' => ['nullable', 'string', 'max:100'],
            'status_layanan' => ['required', 'string', 'in:aktif,nonaktif,maintenance,review'],
            'gambar' => ['nullable', File::image()->max('4mb')],
        ];
    }
}
