@extends('parent.layouts.app')

@section('title', 'Sent Messages')
@section('page_title', 'Sent Messages')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-semibold">Sent Messages</h1>
            <span class="bg-gray-100 text-gray-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                {{ $messages->total() }} sent
            </span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('parent.messages.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                Inbox
            </a>
            <a href="{{ route('parent.messages.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                Compose
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100">
        @if($messages->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($messages as $message)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4 flex-1">
                                <div class="flex-shrink-0">
                                    <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">
                                            To: {{ $message->recipient->name }}
                                        </h3>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 mb-1">
                                        {{ $message->subject }}
                                    </p>
                                    <p class="text-sm text-gray-500 line-clamp-2">
                                        {{ Str::limit($message->content, 100) }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="text-xs text-gray-500">
                                    {{ $message->created_at->format('M j, Y') }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $message->created_at->format('g:i A') }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('parent.messages.show', $message) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                                View Message →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $messages->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No sent messages</h3>
                <p class="mt-1 text-sm text-gray-500">You haven't sent any messages yet.</p>
                <div class="mt-6">
                    <a href="{{ route('parent.messages.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Compose Message
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

