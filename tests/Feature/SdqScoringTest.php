<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Question;
use App\Models\QuestionnaireResponse;
use App\Models\SdqAnswer;
use App\Services\ScoringService;
use Database\Seeders\QuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SdqScoringTest extends TestCase
{
    use RefreshDatabase;

    protected ScoringService $scoringService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scoringService = $this->app->make(ScoringService::class);
    }

    /**
     * Test scoring calculation with direct and reversed questions.
     */
    public function test_sdq_scoring_calculation(): void
    {
        // 1. Seed questions
        $this->seed(QuestionSeeder::class);

        // 2. Create user & response
        $user = User::factory()->create(['gender' => 'L']);
        $response = QuestionnaireResponse::create([
            'user_id' => $user->id,
            'status' => 'draft',
        ]);

        // We will answer the questions such that:
        // - Prosocial (1, 4, 9, 17, 20): Answered as 2. Because they are prosocial, they should be excluded from total.
        // - Question 7 (Conduct): Answered as 2. Since it is reversed, it should score 2 - 2 = 0.
        // - Question 11, 14, 21, 25 (Reversed): Answered as 0. Since they are reversed, they should score 2 - 0 = 2.
        // - All other difficulties questions: Answered as 0. Since they are direct, they should score 0.
        
        // Let's seed these answers:
        $answers = [
            // Prosocial (value 2)
            1 => 2,
            4 => 2,
            9 => 2,
            17 => 2,
            20 => 2,
            // Conduct 7 (value 2) -> reversed
            7 => 2,
            // Reversed difficulties (value 0 -> score 2)
            11 => 0,
            14 => 0,
            21 => 0,
            25 => 0,
            // All other questions (value 0 -> score 0)
            2 => 0, 3 => 0, 5 => 0, 6 => 0, 8 => 0, 10 => 0, 12 => 0, 13 => 0,
            15 => 0, 16 => 0, 18 => 0, 19 => 0, 22 => 0, 23 => 0, 24 => 0
        ];

        foreach ($answers as $num => $val) {
            SdqAnswer::create([
                'response_id' => $response->id,
                'question_number' => $num,
                'answer_value' => $val,
            ]);
        }

        // Calculate
        $result = $this->scoringService->calculate($response);

        // Expected score calculation:
        // - Prosocial: ignored (questions 1, 4, 9, 17, 20) -> score 0
        // - Question 7 (conduct): reversed value 2 -> score 2 - 2 = 0
        // - Question 11 (peer): reversed value 0 -> score 2
        // - Question 14 (peer): reversed value 0 -> score 2
        // - Question 21 (hyperactivity): reversed value 0 -> score 2
        // - Question 25 (hyperactivity): reversed value 0 -> score 2
        // - Other difficulties (2,3,5,6,8,10,12,13,15,16,18,19,22,23,24): direct value 0 -> score 0
        // Total expected SDQ score = 0 (from q7) + 2*4 (from q11, 14, 21, 25) = 8.
        
        $this->assertEquals(8, $result['sdq_score']);
        $this->assertEquals('Normal', $result['sdq_category']);
    }
}
