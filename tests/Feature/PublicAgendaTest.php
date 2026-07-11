<?php

namespace Tests\Feature;

use App\Models\AgendaEvent;
use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAgendaTest extends TestCase
{
    use RefreshDatabase;

    private Petugas $petugas;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user and petugas role for relations
        $role = Role::create([
            'nama_role' => 'Petugas',
            'deskripsi' => 'Petugas',
        ]);
        $user = User::factory()->create([
            'id_role' => $role->id_role,
        ]);
        $this->petugas = Petugas::create([
            'id_user' => $user->id_user,
            'nama_petugas' => 'Petugas Test',
            'jabatan' => 'Pustakawan',
        ]);
    }

    public function test_can_view_agenda_index_with_all_events(): void
    {
        // 1. Create published events
        $agenda1 = AgendaEvent::create([
            'judul_event' => 'Kunjungan SMK Ma\'arif 1',
            'slug' => 'kunjungan-smk-maarif-1',
            'deskripsi' => 'Kunjungan rombongan SMK Ma\'arif ke perpustakaan.',
            'lokasi' => 'Perpustakaan Bukittinggi',
            'tanggal_mulai' => '2026-06-24',
            'jam_mulai' => '08:00:00',
            'status_event' => 'terbit',
            'kategori' => 'Kunjungan',
            'tampilkan_beranda' => true,
            'created_by' => $this->petugas->id_petugas,
        ]);

        $agenda2 = AgendaEvent::create([
            'judul_event' => 'Bedah Buku Lokal',
            'slug' => 'bedah-buku-lokal',
            'deskripsi' => 'Bedah buku bersama budayawan.',
            'lokasi' => 'Aula Balai Kota',
            'tanggal_mulai' => '2026-06-28',
            'jam_mulai' => '10:00:00',
            'status_event' => 'terbit',
            'kategori' => 'Seminar',
            'tampilkan_beranda' => true,
            'created_by' => $this->petugas->id_petugas,
        ]);

        // 2. Request index page
        $response = $this->get(route('agenda.index'));

        // 3. Assertions
        $response->assertStatus(200);
        $response->assertSee('Agenda Kegiatan');
        $response->assertViewHas('allEvents');

        $allEvents = $response->viewData('allEvents');
        $this->assertCount(2, $allEvents);
        $this->assertEquals('Bedah Buku Lokal', $allEvents[0]['judul_event']);
        $this->assertEquals('Kunjungan SMK Ma\'arif 1', $allEvents[1]['judul_event']);
    }

    public function test_can_view_published_agenda_detail(): void
    {
        // 1. Create a published agenda
        $agenda = AgendaEvent::create([
            'judul_event' => 'Kunjungan SMK Ma\'arif 1 Sendang Agung',
            'slug' => 'kunjungan-smk-maarif-1-sendang-agung',
            'deskripsi' => 'Kunjungan rombongan SMK Ma\'arif ke perpustakaan.',
            'lokasi' => 'Perpustakaan Bukittinggi',
            'tanggal_mulai' => '2026-06-24',
            'jam_mulai' => '08:00:00',
            'status_event' => 'terbit',
            'kategori' => 'Kunjungan',
            'tampilkan_beranda' => true,
            'created_by' => $this->petugas->id_petugas,
        ]);

        // 2. Request detail page
        $response = $this->get(route('agenda.show', $agenda->slug));

        // 3. Assertions
        $response->assertStatus(200);
        $response->assertSee('Kunjungan SMK Ma\'arif 1 Sendang Agung');
        $response->assertSee('Kunjungan rombongan SMK Ma\'arif');
        $response->assertSee('Perpustakaan Bukittinggi');
    }

    public function test_cannot_view_draft_agenda_detail(): void
    {
        // 1. Create a draft agenda
        $agenda = AgendaEvent::create([
            'judul_event' => 'Bedah Buku Internal',
            'slug' => 'bedah-buku-internal',
            'deskripsi' => 'Bedah buku tertutup.',
            'lokasi' => 'Ruang Rapat',
            'tanggal_mulai' => '2026-06-25',
            'jam_mulai' => '10:00:00',
            'status_event' => 'draft',
            'kategori' => 'Diskusi',
            'tampilkan_beranda' => false,
            'created_by' => $this->petugas->id_petugas,
        ]);

        // 2. Request detail page -> should fail with 404
        $response = $this->get(route('agenda.show', $agenda->slug));
        $response->assertStatus(404);
    }
}
