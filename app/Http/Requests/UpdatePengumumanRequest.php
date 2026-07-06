<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengumumanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPetugas() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['required', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'prioritas' => ['required', 'string', 'in:Normal,Penting'],
            'target_pengguna' => ['required', 'string', 'max:100'],
            'status_pengumuman' => ['required', 'string', 'in:draf,terbit'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'file_lampiran' => ['nullable', 'array'],
            'file_lampiran.*' => ['nullable', 'file', 'max:10240'], // 10MB limit per file
        ];
    }

    /**
     * Get the validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul pengumuman wajib diisi.',
            'judul.max' => 'Judul pengumuman maksimal 150 karakter.',
            'isi.required' => 'Isi pengumuman wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'prioritas.required' => 'Prioritas wajib diisi.',
            'prioritas.in' => 'Prioritas tidak valid.',
            'target_pengguna.required' => 'Target pengguna wajib diisi.',
            'status_pengumuman.required' => 'Status publikasi wajib diisi.',
            'status_pengumuman.in' => 'Status publikasi tidak valid.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'gambar.max' => 'Ukuran gambar maksimal 5MB.',
            'file_lampiran.*.max' => 'Ukuran lampiran maksimal 10MB.',
        ];
    }
}
