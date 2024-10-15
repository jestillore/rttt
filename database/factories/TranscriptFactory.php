<?php

namespace Database\Factories;

use App\Models\Meeting;
use App\Models\Transcript;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranscriptFactory extends Factory
{
    protected $model = Transcript::class;

    public function definition(): array
    {
        return [
            'meeting_id' => Meeting::factory(),
            'source_language' => 'en',
            'destination_language' => 'en',
            'content' => $this->faker->sentence(),
        ];
    }
}
