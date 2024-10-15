<?php

namespace Database\Factories;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingFactory extends Factory
{
    protected $model = Meeting::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->uuid(),
            'context' => $this->faker->sentence(),
            'language' => 'en',
        ];
    }
}
