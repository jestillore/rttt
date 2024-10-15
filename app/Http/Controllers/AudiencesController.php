<?php

namespace App\Http\Controllers;

use App\Models\Audience;
use App\Models\Meeting;
use Illuminate\Http\Request;

class AudiencesController extends Controller
{
    public function store(Meeting $meeting, Request $request)
    {
        $audience = $meeting->audiences()
            ->create([
                'language' => $request->input('language'),
            ]);
        return redirect()->route('meetings.audiences.show', [
            'meeting' => $meeting->code,
            'audience' => $audience->id,
        ]);
    }

    public function show(Meeting $meeting, Audience $audience)
    {
        return view('audience', [
            'meeting' => $meeting,
            'audience' => $audience,
        ]);
    }
}
