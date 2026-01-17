<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentPreference;

class StudentProfileController extends Controller
{
    /**
     * Show profile creation form
     */
    public function create()
    {
        $user = Auth::user();
        $prefs = $user->preferences;
        
        // If profile already completed, redirect to dashboard
        if ($prefs && $prefs->profile_completed) {
            return redirect()->route('student.dashboard');
        }
        
        return view('student.profile-create');
    }

    /**
     * Store profile information
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'age' => 'required|integer|min:5|max:25',
            'grade_level' => 'required|string|max:50',
            'curriculum_board' => 'required|string|max:100',
            'academic_stream' => 'nullable|string|max:100',
            'learning_style' => 'required|string|in:visual,auditory,kinesthetic,reading-writing,mixed',
            'preferred_format' => 'required|string|in:textual,visual,audio,mixed',
            'learning_pace' => 'required|string|in:slow,moderate,fast',
            'preferred_language' => 'required|string|max:50',
            'motivation_level' => 'required|integer|min:1|max:5',
            'current_mood' => 'required|string|max:50',
            'study_goals' => 'nullable|string|max:500',
            'interests' => 'nullable|string|max:500',
            'challenges' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $data = $validator->validated();

        // Store profile data in preferences
        $prefs = StudentPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'grade_year' => $data['grade_level'],
                'curriculum_board' => $data['curriculum_board'],
                'learning_style' => $data['learning_style'],
                'weekly_goal' => $data['study_goals'] ?? null,
                'skill_area' => $data['academic_stream'] ?? null,
                'profile_completed' => true,
                'meta' => [
                    'age' => $data['age'],
                    'preferred_format' => $data['preferred_format'],
                    'learning_pace' => $data['learning_pace'],
                    'preferred_language' => $data['preferred_language'],
                    'motivation_level' => $data['motivation_level'],
                    'current_mood' => $data['current_mood'],
                    'interests' => $data['interests'] ?? null,
                    'challenges' => $data['challenges'] ?? null,
                    'profile_created_at' => now()->toDateTimeString(),
                ],
            ]
        );

        return redirect()->route('student.dashboard')->with('success', 'Profile created successfully! Welcome to EduFocus!');
    }
}

