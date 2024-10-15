<?php

namespace App\Jobs;

use App\Contracts\Translator;
use App\Events\MeetingDone;
use App\Models\Audience;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SummarizeAndBroadcast implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly int $audienceId
    ) {
    }

    public function handle(Translator $translator): void
    {
        $audience = Audience::findOrFail($this->audienceId);
        $summary = $translator->summarize(
            meeting: $audience->meeting,
            audience: $audience,
            transcript: $audience->meeting->generateTranscriptsToString()
        );
        $audience->update([
            'summary' => $summary,
        ]);
        event(new MeetingDone(
            meeting: $audience->meeting->code,
            audience: $audience->id
        ));
    }
}
