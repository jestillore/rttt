<?php

namespace App\Jobs;

use App\Contracts\Translator;
use App\Events\NewSentence;
use App\Models\Audience;
use App\Models\Meeting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TranslateAndBroadcast implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly int $meetingId,
        public readonly int $audienceId,
        public readonly string $message
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(Translator $translator): void
    {
        $meeting = Meeting::findOrFail($this->meetingId);
        $audience = Audience::findOrFail($this->audienceId);
        $translatedSentence = $translator->translate(
            meeting: $meeting,
            audience: $audience,
            sentence: $this->message
        );
        Log::info('TRANSLATED: ' . $translatedSentence);
        event(new NewSentence(
            meeting: $meeting->code,
            audience: $audience->id,
            message: $translatedSentence
        ));
    }
}
