<?php

namespace App\Jobs;

use App\Contracts\Translator;
use App\Events\NewSentence;
use App\Models\Audience;
use App\Models\Meeting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TranslateAndBroadcast implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly int $meetingId,
        public readonly int $audienceId,
        public readonly string $originalMessage
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(Translator $translator): void
    {
        $meeting = Meeting::findOrFail($this->meetingId);
        $audience = Audience::findOrFail($this->audienceId);
        $translatedMessage = $translator->translate(
            meeting: $meeting,
            audience: $audience,
            sentence: $this->originalMessage
        );
        Log::info('TRANSLATED: ' . $translatedMessage);

        $fileName = Str::random(32) . '.mp3';
        $response = Http::withToken(config('services.openai.token'))
            ->post('https://api.openai.com/v1/audio/speech', [
                'model' => 'tts-1',
                'input' => $translatedMessage,
                'voice' => 'onyx',
            ]);
        Storage::put($fileName, $response->body());

        event(new NewSentence(
            meeting: $meeting->code,
            audience: $audience->id,
            originalMessage: $this->originalMessage,
            translatedMessage: $translatedMessage,
            audioUrl: Storage::temporaryUrl($fileName, now()->addHour())
        ));
    }
}
