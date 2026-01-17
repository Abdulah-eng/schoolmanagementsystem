<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentPreference;

class StudentSettingsController extends Controller
{
    public function page()
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'Unauthorized');
        }
        
        $pref = StudentPreference::firstOrCreate(
            ['user_id' => $user->id],
            [
                'grade_year' => null,
                'curriculum_board' => null,
                'learning_style' => null,
                'weekly_goal' => null,
                'skill_area' => null,
                'meta' => json_encode([
                    'notifications' => [
                        'email' => true,
                        'push' => false,
                    ],
                    'theme' => 'light',
                    'focus_defaults' => [
                        'session_type' => 'study',
                        'planned_minutes' => 25,
                    ],
                ]),
            ]
        );

        $meta = $this->decodeMeta($pref->meta);

        return view('student.settings', compact('user', 'pref', 'meta'));
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'grade_year' => 'nullable|string|max:50',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->update(['name' => $request->name]);

        $pref = StudentPreference::firstOrCreate(['user_id' => $user->id]);
        $pref->update(['grade_year' => $request->grade_year]);

        return response()->json(['success' => true]);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['errors' => ['current_password' => ['Current password is incorrect']]], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['success' => true]);
    }

    public function updatePreferences(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notifications' => 'nullable|array',
            'notifications.email' => 'nullable|boolean',
            'notifications.push' => 'nullable|boolean',
            'theme' => 'nullable|in:light,dark,system',
            'focus_defaults' => 'nullable|array',
            'focus_defaults.session_type' => 'nullable|string|max:50',
            'focus_defaults.planned_minutes' => 'nullable|integer|min:5|max:180',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $pref = StudentPreference::firstOrCreate(['user_id' => $user->id]);
        $meta = $this->decodeMeta($pref->meta);

        if ($request->has('notifications')) {
            $meta['notifications'] = array_merge($meta['notifications'] ?? [], $request->notifications);
        }
        if ($request->has('theme')) {
            $meta['theme'] = $request->theme;
        }
        if ($request->has('focus_defaults')) {
            $meta['focus_defaults'] = array_merge($meta['focus_defaults'] ?? [], $request->focus_defaults);
        }

        $pref->update(['meta' => json_encode($meta)]);

        return response()->json(['success' => true, 'meta' => $meta]);
    }

    private function decodeMeta($meta)
    {
        $data = [];
        try { $data = is_array($meta) ? $meta : json_decode($meta ?: '{}', true); } catch (\Throwable $e) { $data = []; }
        $data['notifications'] = $data['notifications'] ?? ['email' => true, 'push' => false];
        $data['theme'] = $data['theme'] ?? 'light';
        $data['focus_defaults'] = $data['focus_defaults'] ?? ['session_type' => 'study', 'planned_minutes' => 25];
        return $data;
    }
}


