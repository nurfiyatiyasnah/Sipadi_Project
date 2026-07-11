<?php

namespace App\Http\Requests;

use App\Models\Prestasi;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StorePrestasiRequest extends FormRequest
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
            'judul_prestasi' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tingkat_prestasi' => ['required', 'string', Rule::in(['lokal', 'nasional', 'internasional'])],
            'penyelenggara' => ['nullable', 'string', 'max:150'],
            'nomor_sertifikat' => ['nullable', 'string', 'max:100'],
            'tanggal_prestasi' => ['nullable', 'date'],
            'status_prestasi' => ['required', Rule::in([
                Prestasi::STATUS_DRAFT,
                Prestasi::STATUS_PUBLISHED,
                Prestasi::STATUS_INACTIVE,
            ])],
            'gambar' => ['nullable', File::image()->max('4mb')],
            'file_lampiran' => ['nullable', File::types(['pdf', 'jpg', 'jpeg', 'png', 'webp'])->max('5mb')],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul_prestasi.required' => 'Judul prestasi wajib diisi.',
            'judul_prestasi.max' => 'Judul prestasi maksimal 150 karakter.',
            'tingkat_prestasi.required' => 'Tingkat prestasi wajib dipilih.',
            'tingkat_prestasi.in' => 'Tingkat prestasi tidak valid.',
            'status_prestasi.required' => 'Status prestasi wajib dipilih.',
            'status_prestasi.in' => 'Status prestasi tidak valid.',
            'gambar.image' => 'Foto dokumentasi harus berupa gambar.',
            'gambar.max' => 'Ukuran foto dokumentasi maksimal 4MB.',
            'file_lampiran.max' => 'Ukuran lampiran maksimal 5MB.',
        ];
    }
}
