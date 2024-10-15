<?php

namespace Tests\Unit;

use App\Contracts\Translator;
use App\Events\MeetingDone;
use App\Jobs\SummarizeAndBroadcast;
use App\Models\Audience;
use App\Models\Meeting;
use App\Models\Transcript;
use Illuminate\Support\Facades\Event;
use Mockery;
use function Pest\Laravel\assertDatabaseHas;

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

it('can broadcast meeting done after generating summary', function () {
    $meeting = Meeting::factory()
        ->create();
    $audience = Audience::factory()
        ->for($meeting)
        ->create([
            'language' => 'es',
            'summary' => null,
        ]);
    $transcript = Transcript::factory()
        ->for($meeting)
        ->create([
            'content' => 'Hello',
        ]);

    $translator = Mockery::mock(Translator::class);
    $translator->shouldReceive('summarize')
        ->withArgs(function (Meeting $_meeting, Audience $_audience, string $_transcript) use ($meeting, $audience, $transcript) {
            expect($_meeting->is($meeting))->toBeTrue()
                ->and($_audience->is($audience))->toBeTrue()
                ->and($_transcript)->toBe($transcript->content);
            return true;
        })
        ->andReturn('Hola')
        ->once();
    app()->instance(Translator::class, $translator);

    Event::fake();

    dispatch(new SummarizeAndBroadcast($audience->id));

    assertDatabaseHas('audiences', [
        'id' => $audience->id,
        'summary' => 'Hola',
    ]);

    Event::assertDispatched(MeetingDone::class, function (MeetingDone $event) use ($meeting, $audience) {
        return $event->meeting === $meeting->code &&
            $event->audience === $audience->id;
    });
});
