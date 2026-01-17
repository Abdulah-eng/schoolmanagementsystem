<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentMessageController extends Controller
{
    public function index()
    {
        $messages = Message::where('recipient_id', Auth::id())
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('parent.messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        if ($message->recipient_id !== Auth::id()) {
            abort(403);
        }

        if (!$message->is_read) {
            $message->markAsRead();
        }

        return view('parent.messages.show', compact('message'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('parent.messages.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'subject' => $request->subject,
            'content' => $request->content,
        ]);

        return redirect()->route('parent.messages.index')
            ->with('success', 'Message sent successfully!');
    }

    public function sent()
    {
        $messages = Message::where('sender_id', Auth::id())
            ->with('recipient')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('parent.messages.sent', compact('messages'));
    }
}
