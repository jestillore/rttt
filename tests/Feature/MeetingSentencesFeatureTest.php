<?php

namespace Tests\Feature;

use App\Contracts\Translator;
use App\Events\NewSentence;
use App\Models\Audience;
use App\Models\Meeting;
use Illuminate\Support\Facades\Event;
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

    $translator = Mockery::mock(Translator::class);
    $translator->shouldReceive('translate')
        ->withArgs(function (Meeting $_meeting, Audience $audience) use ($meeting, $englishAudience) {
            expect($_meeting->is($meeting))->toBeTrue()
                ->and($audience->is($englishAudience))->toBeTrue();
            return true;
        })
        ->andReturn('Hello! How are you?')
        ->once();
    $translator->shouldReceive('translate')
        ->withArgs(function (Meeting $_meeting, Audience $audience) use ($meeting, $spanishAudience) {
            expect($_meeting->is($meeting))->toBeTrue()
                ->and($audience->is($spanishAudience))->toBeTrue();
            return true;
        })
        ->andReturn('¿Hola, cómo estás?')
        ->once();
    app()->instance(Translator::class, $translator);

    Event::fake();

    $sentence = 'Hej! Hur mår du?';
    post(route('meetings.sentences.store', $meeting->code), [
        'sentence' => $sentence,
    ]);

    Event::assertDispatched(NewSentence::class, function (NewSentence $event) use ($meeting, $englishAudience) {
        return $event->meeting === $meeting->code &&
            $event->audience === $englishAudience->id &&
            $event->message === 'Hello! How are you?';
    });
    Event::assertDispatched(NewSentence::class, function (NewSentence $event) use ($meeting, $spanishAudience) {
        return $event->meeting === $meeting->code &&
            $event->audience === $spanishAudience->id &&
            $event->message === '¿Hola, cómo estás?';
    });
});
