<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Classes;
use App\Models\Siswa;
use App\Models\QuestionnaireResponse;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class QuestionnaireCooldownTest extends TestCase
{
    use RefreshDatabase;

    private User $studentUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        $class = Classes::create([
            'name' => '7A',
            'grade' => 7,
            'academic_year' => '2024/2025',
        ]);

        $this->studentUser = User::factory()->create([
            'email' => 'siswa@psycora.com',
        ]);
        $this->studentUser->assignRole('siswa');

        Siswa::create([
            'user_id' => $this->studentUser->id,
            'nis' => '12345',
            'tanggal_lahir' => '2012-05-15',
            'class_id' => $class->id,
            'tahun_ajaran' => '2024/2025',
        ]);
    }

    public function test_student_without_previous_kuesioner_can_start_immediately(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.questionnaire.index'));

        $response->assertStatus(200);
        $response->assertSee('Mulai kuesioner sekarang');
        $response->assertDontSee('Batas Pengisian Kuesioner');

        $sdqResponse = $this->actingAs($this->studentUser)
            ->get(route('siswa.questionnaire.sdq'));
        $sdqResponse->assertStatus(200);
    }

    public function test_student_can_start_after_7_days_cooldown(): void
    {
        // Completed 8 days ago
        QuestionnaireResponse::create([
            'user_id' => $this->studentUser->id,
            'status' => 'completed',
            'completed_at' => Carbon::now()->subDays(8),
        ]);

        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.questionnaire.index'));

        $response->assertStatus(200);
        $response->assertSee('Mulai kuesioner baru');
        $response->assertDontSee('Batas Pengisian Kuesioner');

        $sdqResponse = $this->actingAs($this->studentUser)
            ->get(route('siswa.questionnaire.sdq'));
        $sdqResponse->assertStatus(200);
    }

    public function test_student_blocked_during_7_days_cooldown(): void
    {
        // Completed 3 days ago
        QuestionnaireResponse::create([
            'user_id' => $this->studentUser->id,
            'status' => 'completed',
            'completed_at' => Carbon::now()->subDays(3),
        ]);

        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.questionnaire.index'));

        $response->assertStatus(200);
        $response->assertSee('Batas Pengisian Kuesioner');
        $response->assertSee('Mulai kuesioner baru (Terkunci)');

        // Trying to access sdq page directly should redirect back with error
        $sdqResponse = $this->actingAs($this->studentUser)
            ->get(route('siswa.questionnaire.sdq'));
        $sdqResponse->assertRedirect(route('siswa.questionnaire.index'));
        $sdqResponse->assertSessionHas('error');
    }

    public function test_student_can_resume_draft_even_in_cooldown(): void
    {
        // Completed 3 days ago
        QuestionnaireResponse::create([
            'user_id' => $this->studentUser->id,
            'status' => 'completed',
            'completed_at' => Carbon::now()->subDays(3),
        ]);

        // Has active draft
        QuestionnaireResponse::create([
            'user_id' => $this->studentUser->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.questionnaire.index'));

        $response->assertStatus(200);
        $response->assertSee('Batas Pengisian Kuesioner');
        $response->assertSee('Draf Kuesioner Tersimpan');
        $response->assertSee('Lanjutkan Pengisian');
        $response->assertSee('Hapus draf & mulai baru (Terkunci)', false);

        // Accessing sdq to resume draft should work fine
        $sdqResponse = $this->actingAs($this->studentUser)
            ->get(route('siswa.questionnaire.sdq'));
        $sdqResponse->assertStatus(200);
    }
}
