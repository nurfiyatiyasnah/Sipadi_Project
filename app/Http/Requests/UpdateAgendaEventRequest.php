<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAgendaEventRequest extends FormRequest
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
            'deskripsi' => ['nullable', 'string'],
            'lokasi' => ['nullable', 'string', 'max:2048'],
            'tanggal_mulai' => ['nullable', 'date'],
            'jam_mulai' => ['nullable', 'date_format:H:i,H:i:s'],
            'gambar' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'status_event' => ['required', 'string', 'in:draft,terbit'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'tampilkan_beranda' => ['nullable', 'boolean'],
        ];
    }
}
