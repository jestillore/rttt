<?php

namespace App\Translators;

use App\Contracts\Translator;
use App\Models\Audience;
use App\Models\Meeting;
use Illuminate\Support\Facades\Http;

class OpenAi implements Translator
{

    public function translate(Meeting $meeting, Audience $audience, string $sentence): string
    {
        $systemPrompt = <<<PROMPT
        You are a helpful assistant that translates the following text to a destination language.
        The source language is $meeting->language.
        The destination language is $audience->language.
        Translate the following text to the destination language.
        Don't add additional text or introduction.
        Just send the translation directly.

        If it helps, the topic context of the text is: $meeting->context
        PROMPT;

        $response = Http::withToken(config('services.openai.token'))
            ->timeout(300)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => $sentence,
                    ],
                ],
            ]);
        return $response->json('choices.0.message.content');
    }

    public function summarize(Meeting $meeting, Audience $audience, string $transcript): string
    {
        $systemPrompt = <<<PROMPT
        You are a helpful assistant that generates a summary the following transcription to a destination language.
        The transcript is written in the source language: $meeting->language.
        The destination language is $audience->language.
        Generate a summary of the transcript and write it to the destination language.
        Don't add additional text or introduction.
        Just send the summary directly.

        If it helps, the topic context of the text is: $meeting->context
        PROMPT;

        $response = Http::withToken(config('services.openai.token'))
            ->timeout(300)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => $transcript,
                    ],
                ],
            ]);
        return $response->json('choices.0.message.content');
    }
}
