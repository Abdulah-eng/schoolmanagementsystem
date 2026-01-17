<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\BreathingSession;

class StudentBreathingController extends Controller
{
    public function start(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cycles' => 'nullable|integer|min:1|max:20',
            'inhale_seconds' => 'nullable|integer|min:1|max:20',
            'hold_seconds' => 'nullable|integer|min:0|max:20',
            'exhale_seconds' => 'nullable|integer|min:1|max:30',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Cancel any running breathing session
        BreathingSession::where('user_id', Auth::id())
            ->where('status', 'running')->delete();

        $data = array_merge([
            'cycles' => 5,
            'inhale_seconds' => 4,
            'hold_seconds' => 4,
            'exhale_seconds' => 6,
        ], $validator->validated());

        $session = BreathingSession::create([
            'user_id' => Auth::id(),
            'cycles' => $data['cycles'],
            'inhale_seconds' => $data['inhale_seconds'],
            'hold_seconds' => $data['hold_seconds'],
            'exhale_seconds' => $data['exhale_seconds'],
            'status' => 'running',
            'started_at' => now(),
        ]);

        return response()->json($session, 201);
    }

    public function complete(BreathingSession $breathingSession)
    {
        abort_if($breathingSession->user_id !== Auth::id(), 403);
        // delete record after completion per requirement
        $breathingSession->delete();
        return response()->noContent();
    }
}
