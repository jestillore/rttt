<?php

namespace App\Http\Controllers;

use App\Jobs\TranslateAndBroadcast;
use App\Models\Audience;
use App\Models\Meeting;
use Illuminate\Http\Request;

class SentencesController extends Controller
{
    public function store(Meeting $meeting, Request $request)
    {
        $sentence = $request->input('sentence');
        $meeting->transcripts()
            ->create([
                'source_language' => $meeting->language,
                'destination_language' => $meeting->language,
                'content' => $sentence,
            ]);
        $meeting->audiences()->each(function (Audience $audience) use ($meeting, $sentence) {
            dispatch(new TranslateAndBroadcast(
                meetingId: $meeting->id,
                audienceId: $audience->id,
                message: $sentence
            ));
        });
        return response()->noContent();
    }
}
