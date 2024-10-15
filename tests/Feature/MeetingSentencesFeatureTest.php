<?php

namespace Tests\Feature;

use App\Contracts\Translator;
use App\Events\NewSentence;
use App\Jobs\TranslateAndBroadcast;
use App\Models\Audience;
use App\Models\Meeting;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Mockery;

use function Pest\Laravel\post;

it('should send an event to all audiences when there is a new sentence', function () {
    $meeting = Meeting::factory()
        ->create([
            'language' => 'sv',
        ]);

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

    $sentence = 'Hej! Hur mår du?';
    post(route('meetings.sentences.store', $meeting->code), [
        'sentence' => $sentence,
    ]);

    Queue::assertPushed(TranslateAndBroadcast::class, function (TranslateAndBroadcast $job) use ($meeting, $englishAudience, $sentence) {
        return $job->meetingId === $meeting->id &&
            $job->audienceId === $englishAudience->id &&
            $job->message === $sentence;
    });
    Queue::assertPushed(TranslateAndBroadcast::class, function (TranslateAndBroadcast $job) use ($meeting, $spanishAudience, $sentence) {
        return $job->meetingId === $meeting->id &&
            $job->audienceId === $spanishAudience->id &&
            $job->message === $sentence;
    });
});

it('should send an event to the audience once it is done translating', function () {
    $meeting = Meeting::factory()
        ->create();
    $audience = Audience::factory()
        ->for($meeting)
        ->create();

    $translator = Mockery::mock(Translator::class);
    $translator->shouldReceive('translate')
        ->withArgs(function (Meeting $_meeting, Audience $_audience) use ($meeting, $audience) {
            expect($_meeting->is($meeting))->toBeTrue()
                ->and($_audience->is($audience))->toBeTrue();
            return true;
        })
        ->andReturn('Hello! How are you?')
        ->once();
    app()->instance(Translator::class, $translator);

    Event::fake();

    $sentence = 'Hej! Hur mår du?';
    dispatch(new TranslateAndBroadcast(
        meetingId: $meeting->id,
        audienceId: $audience->id,
        message: $sentence
    ));

    Event::assertDispatched(NewSentence::class, function (NewSentence $event) use ($meeting, $audience) {
        return $event->meeting === $meeting->code &&
            $event->audience === $audience->id &&
            $event->message === 'Hello! How are you?';
    });
});
