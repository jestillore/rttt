<?php

namespace Database\Factories;

use App\Models\Audience;
use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;

class AudienceFactory extends Factory
{
    protected $model = Audience::class;

    public function definition(): array
    {
        return [
            'meeting_id' => Meeting::factory(),
            'language' => 'sv',
        ];
    }
}
