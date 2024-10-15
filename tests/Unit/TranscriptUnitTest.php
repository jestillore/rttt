<?php

namespace Tests\Unit;

use App\Models\Meeting;
use App\Models\Transcript;

it('can generate meeting transcript', function () {
    /** @var Meeting $meeting */
    $meeting = Meeting::factory()
        ->create();
    $hello = Transcript::factory()
        ->for($meeting)
        ->create([
            'content' => 'Hello',
        ]);
    $world = Transcript::factory()
        ->for($meeting)
        ->create([
            'content' => 'World',
        ]);

    expect($meeting->generateTranscriptsToString())
        ->toBe($hello->content . "\n" . $world->content);
});
