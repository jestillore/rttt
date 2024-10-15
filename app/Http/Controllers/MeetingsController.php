<?php

namespace App\Http\Controllers;

use App\Jobs\SummarizeAndBroadcast;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MeetingsController extends Controller
{
    public function store(Request $request)
    {
        $uuid = Str::uuid();
        $meeting = Meeting::query()
            ->create([
                'code' => $uuid,
                'language' => $request->input('language'),
            ]);
        return redirect()->route('meetings.show', $meeting->code);
    }

    public function show(Meeting $meeting)
    {
        $joinUrl = route('meetings.join', $meeting->code);
        $qrCode = QrCode::size(300)
            ->generate($joinUrl);
        return view('meeting', [
            'meeting' => $meeting,
            'joinQrCode' => $qrCode,
        ]);
    }

    public function join(Meeting $meeting)
    {
        return view('join', [
            'meeting' => $meeting,
        ]);
    }

    public function finish(Meeting $meeting)
    {
        $meeting->audiences()->each(function ($audience) {
            SummarizeAndBroadcast::dispatch($audience->id);
        });
        return view('transcript', [
            'meeting' => $meeting,
        ]);
    }
}
