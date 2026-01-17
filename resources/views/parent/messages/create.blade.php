@extends('parent.layouts.app')

@section('title', 'Compose Message')
@section('page_title', 'Compose Message')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('parent.messages.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Messages
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h1 class="text-xl font-semibold text-gray-900">Compose Message</h1>
            <p class="text-sm text-gray-600 mt-1">Send a message to your child's teacher</p>
        </div>
        
        <form action="{{ route('parent.messages.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label for="recipient_id" class="block text-sm font-medium text-gray-700 mb-2">
                    To (Teacher)
                </label>
                <select name="recipient_id" id="recipient_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select a teacher...</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('recipient_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
                @error('recipient_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    Subject
                </label>
                <input type="text" name="subject" id="subject" required 
                       value="{{ old('subject') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Enter message subject...">
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Message
                </label>
                <textarea name="content" id="content" rows="8" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                          placeholder="Type your message here...">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('parent.messages.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

