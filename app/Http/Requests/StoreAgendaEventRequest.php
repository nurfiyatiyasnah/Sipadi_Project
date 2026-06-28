<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAgendaEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isPetugas() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul_event' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'lokasi' => ['required', 'string', 'max:2048'],
            'tanggal_waktu' => ['required', 'date'],
            'gambar' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'status_event' => ['required', 'string', 'in:draft,terbit,menunggu_review'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'tampilkan_beranda' => ['nullable', 'boolean'],
        ];
    }
}
