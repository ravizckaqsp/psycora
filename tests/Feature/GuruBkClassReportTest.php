<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Classes;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruBkClassReportTest extends TestCase
{
    use RefreshDatabase;

    private function setupGuruBkAndClass()
    {
        $this->seed(RoleSeeder::class);

        $class = Classes::create([
            'name' => '7A',
            'grade' => 7,
            'academic_year' => '2024/2025',
        ]);

        $guruBk = User::factory()->create([
            'email' => 'gurubk@psycora.com',
        ]);
        $guruBk->assignRole('guru_bk');

        return [$guruBk, $class];
    }

    public function test_guru_bk_can_access_class_report_print_page(): void
    {
        [$guruBk, $class] = $this->setupGuruBkAndClass();

        $response = $this->actingAs($guruBk)->get(route('guruBk.laporan-kelas.print', $class->id));

        $response->assertStatus(200);
        $response->assertSee('Laporan Hasil Deteksi Dini Kondisi Psikososial Kelas');
        $response->assertSee('Kelas: 7A');
    }

    public function test_guru_bk_can_export_class_report_csv(): void
    {
        [$guruBk, $class] = $this->setupGuruBkAndClass();

        $response = $this->actingAs($guruBk)->get(route('guruBk.laporan-kelas.export', $class->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename=Laporan_Kelas_7A_' . date('Ymd') . '.csv');

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('LAPORAN KONDISI PSIKOSOSIAL KELAS', $content);
        $this->assertStringContainsString('Kelas,7A', $content);
    }

    public function test_guru_bk_can_access_school_report_print_page(): void
    {
        [$guruBk, $class] = $this->setupGuruBkAndClass();

        $response = $this->actingAs($guruBk)->get(route('guruBk.laporan-sekolah.print'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Rangkuman Deteksi Dini Kondisi Psikososial Sekolah');
        $response->assertSee('Analisis Sebaran Tingkat Risiko');
    }

    public function test_guru_bk_can_export_school_report_csv(): void
    {
        [$guruBk, $class] = $this->setupGuruBkAndClass();

        $response = $this->actingAs($guruBk)->get(route('guruBk.laporan-sekolah.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename=Laporan_Sekolah_Per_Kelas_' . date('Ymd') . '.csv');

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('LAPORAN RINGKASAN KONDISI KELAS (SATU SEKOLAH)', $content);
    }

    public function test_guru_bk_can_access_student_report_print_page(): void
    {
        [$guruBk, $class] = $this->setupGuruBkAndClass();

        $studentUser = User::factory()->create();
        $studentUser->assignRole('siswa');

        \App\Models\Siswa::create([
            'user_id' => $studentUser->id,
            'nis' => '12345',
            'tanggal_lahir' => '2012-05-15',
            'class_id' => $class->id,
            'tahun_ajaran' => '2024/2025',
        ]);

        $latestResponse = \App\Models\QuestionnaireResponse::create([
            'user_id' => $studentUser->id,
            'status' => 'completed',
            'sdq_score' => 15,
            'psc17_score' => 12,
            'sassv_score' => 25,
            'completed_at' => now(),
        ]);

        \App\Models\RiskClassification::create([
            'response_id' => $latestResponse->id,
            'overall_risk' => 'Tidak Berisiko Psikososial',
            'sdq_category' => 'Normal',
            'psc17_category' => 'Negatif',
            'sassv_category' => 'Tidak Kecanduan',
            'recommendation' => 'Siswa dalam kondisi baik.',
        ]);

        $response = $this->actingAs($guruBk)->get(route('guruBk.laporan-siswa.print', $studentUser->id));

        $response->assertStatus(200);
        $response->assertSee('Laporan Hasil Deteksi Dini Kondisi Psikososial');
        $response->assertSee(e($studentUser->name));
    }

    public function test_guru_bk_can_access_filtered_report_print_page(): void
    {
        [$guruBk, $class] = $this->setupGuruBkAndClass();

        $response = $this->actingAs($guruBk)->get(route('guruBk.laporan-filter.print', [
            'class_id' => $class->id,
            'search' => 'SomeStudentName'
        ]));

        $response->assertStatus(200);
        $response->assertSee('Laporan Hasil Deteksi Dini Kondisi Psikososial Siswa');
    }

    public function test_guru_bk_can_export_filtered_report_csv(): void
    {
        [$guruBk, $class] = $this->setupGuruBkAndClass();

        $response = $this->actingAs($guruBk)->get(route('guruBk.laporan-filter.export', [
            'class_id' => $class->id
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename=Laporan_Siswa_Terfilter_' . date('Ymd') . '.csv');

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('LAPORAN KONDISI PSIKOSOSIAL SISWA (HASIL FILTER)', $content);
    }

    public function test_guru_bk_can_filter_students_by_sdq_category(): void
    {
        [$guruBk, $class] = $this->setupGuruBkAndClass();

        $response = $this->actingAs($guruBk)->get(route('guruBk.siswa.index', [
            'sdq_category' => 'Abnormal'
        ]));

        $response->assertStatus(200);
        $response->assertSee('Penyaringan Kategori Aktif');
        $response->assertSee('Abnormal');
    }
}
