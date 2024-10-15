<?php

namespace Tests\Feature;

use App\Jobs\SummarizeAndBroadcast;
use App\Models\Audience;
use App\Models\Meeting;
use Illuminate\Support\Facades\Queue;

use function Pest\Laravel\get;

it('can finish the meeting', function () {
    $meeting = Meeting::factory()
        ->create();
    $englishAudience = Audience::factory()
        ->for($meeting)
        ->create([
            'language' => 'en',
        ]);
    $spanishAudience = Audience::factory()
        ->for($meeting)
        ->create([
            'language' => 'es',
        ]);

    Queue::fake();

    get(route('meetings.finish', $meeting->code))
        ->assertOk();

    Queue::assertPushed(SummarizeAndBroadcast::class, function (SummarizeAndBroadcast $job) use ($meeting, $englishAudience) {
        return $job->audienceId === $englishAudience->id;
    });
    Queue::assertPushed(SummarizeAndBroadcast::class, function (SummarizeAndBroadcast $job) use ($meeting, $spanishAudience) {
        return $job->audienceId === $spanishAudience->id;
    });
});
