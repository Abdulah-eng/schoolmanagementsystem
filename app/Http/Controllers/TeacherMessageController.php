<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherMessageController extends Controller
{
    /**
     * Show messages for the teacher and provide contact list.
     */
    public function index()
    {
        $teacher = Auth::user();

        $messages = Message::where(function ($query) use ($teacher) {
                $query->where('recipient_id', $teacher->id)
                    ->orWhere('sender_id', $teacher->id);
            })
            ->with(['sender', 'recipient'])
            ->latest()
            ->get();

        $studentContacts = Student::whereHas('courses', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter();

        return view('teacher.messages', [
            'messages' => $messages,
            'contacts' => $studentContacts,
        ]);
    }

    /**
     * Send a new message from the teacher.
     */
    public function store(Request $request)
    {
        $teacher = Auth::user();

        $data = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject'      => 'nullable|string|max:255',
            'content'      => 'required|string|max:2000',
        ]);

        // Ensure the teacher is messaging someone they are allowed to reach (one of their students)
        $allowedRecipient = Student::whereHas('courses', function ($query) use ($teacher, $data) {
                $query->where('teacher_id', $teacher->id);
            })
            ->whereHas('user', function ($query) use ($data) {
                $query->where('id', $data['recipient_id']);
            })
            ->exists();

        if (!$allowedRecipient) {
            return back()->withErrors(['recipient_id' => 'You can only message your students.']);
        }

        Message::create([
            'sender_id'    => $teacher->id,
            'recipient_id' => $data['recipient_id'],
            'subject'      => $data['subject'] ?? 'Message from your teacher',
            'content'      => $data['content'],
            'is_read'      => false,
        ]);

        return redirect()->route('teacher.messages.index')
            ->with('success', 'Message sent successfully.');
    }
}
