<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Classes;
use App\Models\Siswa;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_access_dashboard(): void
    {
        $this->seed(RoleSeeder::class);

        $class = Classes::create([
            'name' => '7A',
            'grade' => 7,
            'academic_year' => '2024/2025',
        ]);

        $studentUser = User::factory()->create([
            'email' => 'siswa@psycora.com',
        ]);
        $studentUser->assignRole('siswa');

        Siswa::create([
            'user_id' => $studentUser->id,
            'nis' => '12345',
            'tanggal_lahir' => '2012-05-15',
            'class_id' => $class->id,
            'tahun_ajaran' => '2024/2025',
        ]);

        $response = $this->actingAs($studentUser)->get(route('siswa.dashboard'));

        $response->assertStatus(200);
        
        // Verifikasi menu navigasi (Sidebar)
        $response->assertSee('Psycora');
        $response->assertSee('Psikososial Remaja');
        $response->assertSee('Dashboard');
        $response->assertSee('Kuesioner');
        $response->assertSee('Riwayat');
        $response->assertSee('PsyBot');
        $response->assertSee('AI'); // Badge AI
        $response->assertSee($studentUser->name); // Nama siswa di sidebar
    }
}
