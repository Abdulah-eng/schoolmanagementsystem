<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Your Profile - EduFocus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-3xl">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Your Profile</h1>
                    <p class="text-gray-600">Help us personalize your learning experience</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('student.profile.store') }}" class="space-y-6">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <ul class="list-disc list-inside text-red-800 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-900 border-b pb-2">Personal Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Age *</label>
                                <input type="number" name="age" value="{{ old('age') }}" required min="5" max="25" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Grade Level *</label>
                                <select name="grade_level" required 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Grade</option>
                                    <option value="Grade 1" {{ old('grade_level') == 'Grade 1' ? 'selected' : '' }}>Grade 1</option>
                                    <option value="Grade 2" {{ old('grade_level') == 'Grade 2' ? 'selected' : '' }}>Grade 2</option>
                                    <option value="Grade 3" {{ old('grade_level') == 'Grade 3' ? 'selected' : '' }}>Grade 3</option>
                                    <option value="Grade 4" {{ old('grade_level') == 'Grade 4' ? 'selected' : '' }}>Grade 4</option>
                                    <option value="Grade 5" {{ old('grade_level') == 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
                                    <option value="Grade 6" {{ old('grade_level') == 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
                                    <option value="Grade 7" {{ old('grade_level') == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
                                    <option value="Grade 8" {{ old('grade_level') == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
                                    <option value="Grade 9" {{ old('grade_level') == 'Grade 9' ? 'selected' : '' }}>Grade 9</option>
                                    <option value="Grade 10" {{ old('grade_level') == 'Grade 10' ? 'selected' : '' }}>Grade 10</option>
                                    <option value="Grade 11" {{ old('grade_level') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                    <option value="Grade 12" {{ old('grade_level') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                    <option value="College Year 1" {{ old('grade_level') == 'College Year 1' ? 'selected' : '' }}>College Year 1</option>
                                    <option value="College Year 2" {{ old('grade_level') == 'College Year 2' ? 'selected' : '' }}>College Year 2</option>
                                    <option value="College Year 3" {{ old('grade_level') == 'College Year 3' ? 'selected' : '' }}>College Year 3</option>
                                    <option value="College Year 4" {{ old('grade_level') == 'College Year 4' ? 'selected' : '' }}>College Year 4</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Curriculum Board *</label>
                            <select name="curriculum_board" required 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Curriculum</option>
                                <option value="CBSE" {{ old('curriculum_board') == 'CBSE' ? 'selected' : '' }}>CBSE</option>
                                <option value="ICSE" {{ old('curriculum_board') == 'ICSE' ? 'selected' : '' }}>ICSE</option>
                                <option value="IB" {{ old('curriculum_board') == 'IB' ? 'selected' : '' }}>IB (International Baccalaureate)</option>
                                <option value="State Board" {{ old('curriculum_board') == 'State Board' ? 'selected' : '' }}>State Board</option>
                                <option value="IGCSE" {{ old('curriculum_board') == 'IGCSE' ? 'selected' : '' }}>IGCSE</option>
                                <option value="Other" {{ old('curriculum_board') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Academic Stream (if applicable)</label>
                            <select name="academic_stream" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Stream (Optional)</option>
                                <option value="Science" {{ old('academic_stream') == 'Science' ? 'selected' : '' }}>Science</option>
                                <option value="Commerce" {{ old('academic_stream') == 'Commerce' ? 'selected' : '' }}>Commerce</option>
                                <option value="Arts/Humanities" {{ old('academic_stream') == 'Arts/Humanities' ? 'selected' : '' }}>Arts/Humanities</option>
                                <option value="Not Applicable" {{ old('academic_stream') == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                            </select>
                        </div>
                    </div>

                    <!-- Learning Preferences -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-900 border-b pb-2">Learning Preferences</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Learning Style *</label>
                            <select name="learning_style" required 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Learning Style</option>
                                <option value="visual" {{ old('learning_style') == 'visual' ? 'selected' : '' }}>Visual (I learn by seeing)</option>
                                <option value="auditory" {{ old('learning_style') == 'auditory' ? 'selected' : '' }}>Auditory (I learn by hearing)</option>
                                <option value="kinesthetic" {{ old('learning_style') == 'kinesthetic' ? 'selected' : '' }}>Kinesthetic (I learn by doing)</option>
                                <option value="reading-writing" {{ old('learning_style') == 'reading-writing' ? 'selected' : '' }}>Reading/Writing (I learn by reading and writing)</option>
                                <option value="mixed" {{ old('learning_style') == 'mixed' ? 'selected' : '' }}>Mixed (Combination of styles)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Format *</label>
                            <select name="preferred_format" required 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Format</option>
                                <option value="textual" {{ old('preferred_format') == 'textual' ? 'selected' : '' }}>Textual (Written explanations)</option>
                                <option value="visual" {{ old('preferred_format') == 'visual' ? 'selected' : '' }}>Visual (Diagrams, charts, videos)</option>
                                <option value="audio" {{ old('preferred_format') == 'audio' ? 'selected' : '' }}>Audio (Spoken explanations)</option>
                                <option value="mixed" {{ old('preferred_format') == 'mixed' ? 'selected' : '' }}>Mixed (All formats)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Learning Pace *</label>
                            <select name="learning_pace" required 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Pace</option>
                                <option value="slow" {{ old('learning_pace') == 'slow' ? 'selected' : '' }}>Slow (I need more time to understand)</option>
                                <option value="moderate" {{ old('learning_pace') == 'moderate' ? 'selected' : '' }}>Moderate (Average pace works for me)</option>
                                <option value="fast" {{ old('learning_pace') == 'fast' ? 'selected' : '' }}>Fast (I can learn quickly)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Language *</label>
                            <input type="text" name="preferred_language" value="{{ old('preferred_language', 'English') }}" required 
                                placeholder="e.g., English, Hindi, Spanish"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Emotional & Motivation -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-900 border-b pb-2">How Are You Feeling?</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Motivation Level (1-5) *</label>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">Low</span>
                                <div class="flex-1 flex space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex-1 cursor-pointer">
                                            <input type="radio" name="motivation_level" value="{{ $i }}" required 
                                                {{ old('motivation_level') == $i ? 'checked' : '' }}
                                                class="sr-only peer">
                                            <div class="w-full h-10 bg-gray-200 rounded-lg flex items-center justify-center peer-checked:bg-blue-500 peer-checked:text-white transition-colors">
                                                {{ $i }}
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600">High</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Mood *</label>
                            <select name="current_mood" required 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">How are you feeling today?</option>
                                <option value="excited" {{ old('current_mood') == 'excited' ? 'selected' : '' }}>😊 Excited</option>
                                <option value="calm" {{ old('current_mood') == 'calm' ? 'selected' : '' }}>😌 Calm</option>
                                <option value="focused" {{ old('current_mood') == 'focused' ? 'selected' : '' }}>🎯 Focused</option>
                                <option value="tired" {{ old('current_mood') == 'tired' ? 'selected' : '' }}>😴 Tired</option>
                                <option value="anxious" {{ old('current_mood') == 'anxious' ? 'selected' : '' }}>😟 Anxious</option>
                                <option value="motivated" {{ old('current_mood') == 'motivated' ? 'selected' : '' }}>💪 Motivated</option>
                            </select>
                        </div>
                    </div>

                    <!-- Goals & Interests -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-900 border-b pb-2">Goals & Interests</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Study Goals (Optional)</label>
                            <textarea name="study_goals" rows="3" 
                                placeholder="What do you want to achieve? e.g., Improve math skills, prepare for exams..."
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('study_goals') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Interests & Hobbies (Optional)</label>
                            <textarea name="interests" rows="2" 
                                placeholder="What are you interested in? e.g., Sports, music, coding, reading..."
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('interests') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Learning Challenges (Optional)</label>
                            <textarea name="challenges" rows="2" 
                                placeholder="What subjects or topics do you find difficult?"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('challenges') }}</textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Create Profile & Start Learning
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

