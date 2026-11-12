<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Classes;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruBkDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_bk_can_access_dashboard(): void
    {
        // 1. Seed roles
        $this->seed(RoleSeeder::class);

        // 2. Create class
        $class = Classes::create([
            'name' => '7A',
            'grade' => 7,
            'academic_year' => '2024/2025',
        ]);

        // 3. Create a Guru BK user and assign role
        $guruBk = User::factory()->create([
            'email' => 'gurubk@psycora.com',
        ]);
        $guruBk->assignRole('guru_bk');

        // 4. Access dashboard
        $response = $this->actingAs($guruBk)->get(route('guruBk.dashboard'));

        $response->assertStatus(200);
    }

    public function test_guru_bk_can_access_bi_page(): void
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

        $response = $this->actingAs($guruBk)->get(route('guruBk.bi'));

        $response->assertStatus(200);
    }
}
