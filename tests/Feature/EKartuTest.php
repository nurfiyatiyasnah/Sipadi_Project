<?php

namespace Tests\Feature;

use App\EKartuPdfRenderer;
use App\Models\Anggota;
use App\Models\EKartuAnggota;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EKartuTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_e_kartu_dapat_dibuka(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_anggota_dapat_mengunduh_e_kartu_pdf(): void
    {
        $anggota = $this->anggotaDenganEKartu();

        $response = $this
            ->actingAs($anggota->user)
            ->get(route('anggota.e-kartu.download'));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertDownload("e-kartu-{$anggota->no_anggota}-satu-lembar.pdf");

        $this->assertStringStartsWith('%PDF-', $response->content());
        $this->assertSame(1, $this->pdfPageCount($response->content()));
    }

    public function test_renderer_e_kartu_menghasilkan_pdf_dengan_dompdf(): void
    {
        $anggota = $this->anggotaDenganEKartu();

        $response = app(EKartuPdfRenderer::class)->download($anggota, $anggota->eKartuAnggota);

        $content = (string) $response->getContent();

        $this->assertStringStartsWith('%PDF-', $content);
        $this->assertSame(1, $this->pdfPageCount($content));
    }

    private function anggotaDenganEKartu(): Anggota
    {
        $role = Role::create([
            'nama_role' => 'Anggota',
            'deskripsi' => 'Pengguna umum yang dapat mengakses layanan perpustakaan',
        ]);
        $user = User::factory()->create(['id_role' => $role->id_role]);
        $anggota = Anggota::factory()->for($user, 'user')->create([
            'nik' => '1375010101010001',
            'no_anggota' => '1375010101010001',
            'nama_lengkap' => 'Stenly Rizalevan',
        ]);

        EKartuAnggota::factory()->for($anggota, 'anggota')->create([
            'no_anggota' => $anggota->nik,
        ]);

        return $anggota->load('user', 'eKartuAnggota');
    }

    private function pdfPageCount(string $content): int
    {
        preg_match_all('/\/Type\s*\/Page\b/', $content, $matches);

        return count($matches[0]);
    }
}
