@extends('parent.layouts.app')

@section('title', 'View Message')
@section('page_title', 'View Message')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('parent.messages.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Messages
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('parent.messages.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                Reply
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900 mb-2">
                        {{ $message->subject }}
                    </h1>
                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">From:</span>
                            <span>{{ $message->sender->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-medium">Date:</span>
                            <span>{{ $message->created_at->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                        @if($message->is_read)
                            <div class="flex items-center gap-2">
                                <span class="font-medium">Read:</span>
                                <span>{{ $message->read_at->format('M j, Y \a\t g:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($message->is_read)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Read
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Unread
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="prose max-w-none">
                <div class="whitespace-pre-wrap text-gray-700 leading-relaxed">
                    {{ $message->content }}
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-2xl">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Message ID: {{ $message->id }}
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('parent.messages.create') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                        Reply to {{ $message->sender->name }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

