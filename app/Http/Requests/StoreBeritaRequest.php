<?php

namespace App\Http\Requests;

use App\Models\Berita;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBeritaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPetugas() === true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['nullable', 'string'],
            'id_kategori_berita' => ['required', 'exists:kategori_berita,id_kategori_berita'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status_berita' => ['required', Rule::in([Berita::STATUS_DRAFT, Berita::STATUS_PUBLISHED])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul berita wajib diisi.',
            'judul.max' => 'Judul berita maksimal 150 karakter.',
            'id_kategori_berita.required' => 'Kategori berita wajib dipilih.',
            'id_kategori_berita.exists' => 'Kategori berita tidak valid.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'gambar.max' => 'Ukuran gambar maksimal 5MB.',
            'status_berita.required' => 'Status berita wajib dipilih.',
            'status_berita.in' => 'Status berita tidak valid.',
        ];
    }
}
