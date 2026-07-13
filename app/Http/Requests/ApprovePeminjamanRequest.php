<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApprovePeminjamanRequest extends FormRequest
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
            'tanggal_pengambilan' => ['required', 'date', 'after_or_equal:today'],
            'jam_pengambilan' => ['required', 'string'],
            'lokasi_pengambilan' => ['required', 'string', 'max:100'],
            'pesan' => ['nullable', 'string'],
        ];
    }
}
