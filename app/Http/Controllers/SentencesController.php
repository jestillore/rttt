<?php

namespace App\Http\Controllers;

use App\Contracts\Translator;
use App\Events\NewSentence;
use App\Models\Audience;
use App\Models\Meeting;
use Illuminate\Http\Request;

class SentencesController extends Controller
{
    public function store(Meeting $meeting, Request $request, Translator $translator)
    {
        $sentence = $request->input('sentence');
        $meeting->audiences()->each(function (Audience $audience) use ($meeting, $sentence, $translator) {
            $translatedSentence = $translator->translate($meeting, $audience, $sentence);
            event(new NewSentence(
                meeting: $meeting->code,
                audience: $audience->id,
                message: $translatedSentence
            ));
        });
        return response()->noContent();
    }
}
