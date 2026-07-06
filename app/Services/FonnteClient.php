<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class FonnteClient
{
    /**
     * @return array<string, mixed>
     */
    public function sendMessage(string $target, string $message): array
    {
        $token = config('services.fonnte.token');

        if (! is_string($token) || trim($token) === '') {
            throw new RuntimeException('Token Fonnte belum dikonfigurasi.');
        }

        $response = Http::asForm()
            ->baseUrl($this->baseUrl())
            ->withHeaders([
                'Authorization' => trim($token),
            ])
            ->timeout(10)
            ->connectTimeout(5)
            ->retry([100, 500, 1000])
            ->post('/send', [
                'target' => $this->normalizeTarget($target),
                'message' => $message,
                'countryCode' => (string) config('services.fonnte.country_code', '62'),
            ])
            ->throw();

        $responseData = $response->json();

        if (! is_array($responseData)) {
            throw new RuntimeException('Response Fonnte tidak valid.');
        }

        $status = $responseData['status'] ?? $responseData['Status'] ?? false;

        if ($status !== true) {
            throw new RuntimeException((string) ($responseData['reason'] ?? $responseData['detail'] ?? 'Pengiriman Fonnte gagal.'));
        }

        return $responseData;
    }

    private function baseUrl(): string
    {
        return rtrim((string) config('services.fonnte.base_url', 'https://api.fonnte.com'), '/');
    }

    private function normalizeTarget(string $target): string
    {
        $normalizedTarget = preg_replace('/\D+/', '', $target) ?? '';

        if ($normalizedTarget === '') {
            throw new RuntimeException('Nomor WhatsApp tujuan tidak valid.');
        }

        return $normalizedTarget;
    }
}
