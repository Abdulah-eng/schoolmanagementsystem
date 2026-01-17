@extends('teacher.layouts.app')

@section('title', 'Messages - Teacher Portal')

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
        <p class="text-gray-600 mt-2">Communicate with students and parents</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Compose -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Send a message</h2>
        <form method="POST" action="{{ route('teacher.messages.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Recipient</label>
                <select name="recipient_id" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Select a student</option>
                    @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}" {{ old('recipient_id') == $contact->id ? 'selected' : '' }}>
                            {{ $contact->name }} ({{ $contact->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Subject</label>
                <input name="subject" value="{{ old('subject') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Optional" />
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Message</label>
                <textarea name="content" rows="3" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('content') }}</textarea>
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    Send
                </button>
            </div>
        </form>
    </div>

    <!-- Messages List -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Recent Messages</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($messages as $message)
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">
                                {{ $message->sender_id === auth()->id() ? 'You → ' . ($message->recipient->name ?? 'Unknown') : ($message->sender->name ?? 'Unknown') }}
                            </h3>
                            <p class="text-sm text-gray-500">{{ $message->subject }}</p>
                            <p class="text-sm text-gray-700 mt-1">{{ $message->content }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">No messages yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
