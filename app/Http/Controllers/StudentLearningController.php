<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Course;

class StudentLearningController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $prefs = $user->preferences;
        
        // Check if profile is completed
        if (!$prefs || !$prefs->profile_completed) {
            return redirect()->route('student.profile.create');
        }
        
        // Get dynamic learning targets for the student
        $learningTargets = $this->getLearningTargets();
        $weeklyProgress = $this->getWeeklyProgress();
        
        // Get available courses
        $courses = Course::active()->get();
        
        return view('student.learning', compact('learningTargets', 'weeklyProgress', 'courses', 'prefs'));
    }

    public function explain(Request $request)
    {
        $data = Validator::make($request->all(), [
            'subject' => 'required|string|max:50',
            'topic' => 'required|string|max:120',
            'format' => 'required|in:text,visual,audio,video',
        ])->validate();

        try {
            $apiKey = env('OPENAI_API_KEY');
            if (!$apiKey) throw new \Exception('OPENAI_API_KEY missing');
            $prompt = "Explain the topic '{$data['topic']}' for subject {$data['subject']} in a student-friendly way. Keep it concise and structured. If format is 'visual' or 'video' describe visuals in bullet points; if 'audio' provide a short script. Format: {$data['format']}.";
            $res = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful education assistant.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 600,
            ]);
            if (!$res->successful()) throw new \Exception($res->body());
            $dataRes = $res->json();
            $text = $dataRes['choices'][0]['message']['content'] ?? '';
            return response()->json(['content' => $text]);
        } catch (\Throwable $e) {
            \Log::error('Learning explain failed', ['error' => $e->getMessage()]);
            
            // Provide fallback content when API fails
            $fallbackContent = $this->getFallbackExplanation($data['subject'], $data['topic'], $data['format']);
            return response()->json(['content' => $fallbackContent, 'fallback' => true]);
        }
    }

    public function quiz(Request $request)
    {
        $data = Validator::make($request->all(), [
            'topic' => 'required|string|max:120',
            'num' => 'nullable|integer|min:1|max:5',
        ])->validate();
        $num = $data['num'] ?? 1;
        try {
            $apiKey = env('OPENAI_API_KEY');
            if (!$apiKey) throw new \Exception('OPENAI_API_KEY missing');
            
            // Add stronger randomization to ensure different questions each time
            $randomSeed = time() . rand(1000, 9999) . rand(1000, 9999);
            $prompt = "Create {$num} multiple-choice question(s) with 4 options about '{$data['topic']}'. Make each question completely unique and different from any previous questions. Use random seed {$randomSeed} for maximum variety. Each question should test different aspects of the topic. Return JSON with key 'items' that is an array of {question, options (array of 4), answerIndex (0-3)}. Return only JSON, no code fences.";
            
            $res = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Return only valid compact JSON.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.9, // Maximum temperature for maximum variety
                'max_tokens' => 500,
            ]);
            if (!$res->successful()) throw new \Exception($res->body());
            $jr = $res->json();
            $json = $jr['choices'][0]['message']['content'] ?? '{}';
            $decoded = json_decode($json, true);
            if ($decoded === null) {
                if (preg_match('/\{[\s\S]*\}/', $json, $m)) {
                    $decoded = json_decode($m[0], true);
                }
            }
            return response()->json(['quiz' => $decoded ?? ['items' => []]]);
        } catch (\Throwable $e) {
            \Log::error('Learning quiz failed', ['error' => $e->getMessage()]);
            
            // Provide dynamic fallback quiz when API fails
            $fallbackQuiz = $this->getDynamicFallbackQuiz($data['topic'], $num);
            return response()->json(['quiz' => $fallbackQuiz, 'fallback' => true]);
        }
    }

    private function getFallbackExplanation($subject, $topic, $format)
    {
        $explanations = [
            'text' => "Here's a simple explanation of {$topic} in {$subject}:\n\n• {$topic} is a fundamental concept that helps us understand how things work\n• It's used in everyday life and builds the foundation for more advanced topics\n• Practice and repetition are key to mastering this concept\n\nTry to relate it to real-world examples you encounter daily!",
            'visual' => "Visual Guide for {$topic}:\n\n📊 Key Concepts:\n• Main idea: Core principle\n• Examples: Real-world applications\n• Steps: How to approach problems\n\n🎯 Visual Elements:\n• Diagrams and charts\n• Color-coded sections\n• Flow charts for processes\n\n💡 Tips: Use mind maps to connect related ideas",
            'audio' => "Audio Script for {$topic}:\n\n🎧 Introduction (10 seconds):\n'Welcome to learning about {$topic}! This concept is essential for understanding {$subject}.'\n\n📚 Main Content (30 seconds):\n'Let me break this down into simple steps. First, we need to understand the basic principle. Then, we'll look at examples. Finally, we'll practice together.'\n\n🎯 Summary (10 seconds):\n'Remember, practice makes perfect with {$topic}!'",
            'video' => "Video Outline for {$topic}:\n\n🎬 Scene 1: Introduction (0-10s)\n• Title card with topic name\n• Brief overview of what we'll learn\n\n🎬 Scene 2: Main Content (10-40s)\n• Animated diagrams\n• Step-by-step explanations\n• Real-world examples\n\n🎬 Scene 3: Summary (40-50s)\n• Key points recap\n• Practice suggestions\n• Next steps"
        ];

        return $explanations[$format] ?? $explanations['text'];
    }

    private function getDynamicFallbackQuiz($topic, $num)
    {
        // Get session key for this topic to track used questions
        $sessionKey = 'quiz_questions_' . md5($topic);
        $usedQuestions = session($sessionKey, []);
        
        // Create dynamic questions based on the topic
        $baseQuestions = [
            [
                'question' => "What is the primary goal when studying {$topic}?",
                'options' => [
                    "To memorize all facts",
                    "To understand core concepts and apply them",
                    "To pass the final exam only",
                    "To avoid other subjects"
                ],
                'answerIndex' => 1
            ],
            [
                'question' => "Which approach is most effective for mastering {$topic}?",
                'options' => [
                    "Reading the textbook once",
                    "Regular practice with real examples",
                    "Cramming the night before tests",
                    "Only attending lectures"
                ],
                'answerIndex' => 1
            ],
            [
                'question' => "How does {$topic} relate to real-world applications?",
                'options' => [
                    "It has no practical use",
                    "It's only useful in academic settings",
                    "It provides foundational skills for many careers",
                    "It's just for passing school"
                ],
                'answerIndex' => 2
            ],
            [
                'question' => "What's the best way to overcome difficulties in {$topic}?",
                'options' => [
                    "Give up and move to easier topics",
                    "Ask questions and seek help from teachers",
                    "Skip difficult problems entirely",
                    "Only study with friends"
                ],
                'answerIndex' => 1
            ],
            [
                'question' => "Why is understanding {$topic} important for future learning?",
                'options' => [
                    "It's required by the curriculum",
                    "It builds essential skills for advanced topics",
                    "Teachers expect it",
                    "It's easy to learn"
                ],
                'answerIndex' => 1
            ]
        ];

        // Add topic-specific questions
        $topicSpecificQuestions = [
            [
                'question' => "What makes {$topic} unique compared to other subjects?",
                'options' => [
                    "It's more difficult than others",
                    "It has its own specific methods and principles",
                    "It's taught by different teachers",
                    "It has more homework"
                ],
                'answerIndex' => 1
            ],
            [
                'question' => "How can you best prepare for {$topic} assessments?",
                'options' => [
                    "Study only the night before",
                    "Review regularly and practice consistently",
                    "Rely on class notes only",
                    "Ask friends for answers"
                ],
                'answerIndex' => 1
            ],
            [
                'question' => "What study strategy works best for {$topic}?",
                'options' => [
                    "Passive reading only",
                    "Active problem-solving and practice",
                    "Memorizing formulas without context",
                    "Studying in noisy environments"
                ],
                'answerIndex' => 1
            ],
            [
                'question' => "How should you approach {$topic} homework?",
                'options' => [
                    "Complete it quickly without checking",
                    "Work through problems step by step",
                    "Copy answers from friends",
                    "Skip difficult problems entirely"
                ],
                'answerIndex' => 1
            ]
        ];

        // Combine all questions and shuffle
        $allQuestions = array_merge($baseQuestions, $topicSpecificQuestions);
        shuffle($allQuestions);
        
        // Filter out recently used questions
        $availableQuestions = array_filter($allQuestions, function($q) use ($usedQuestions) {
            return !in_array($q['question'], $usedQuestions);
        });
        
        // If we don't have enough unique questions, reset the used questions
        if (count($availableQuestions) < $num) {
            $availableQuestions = $allQuestions;
            $usedQuestions = [];
        }
        
        // Select questions and mark them as used
        $selectedQuestions = array_slice($availableQuestions, 0, $num);
        foreach ($selectedQuestions as $q) {
            $usedQuestions[] = $q['question'];
        }
        
        // Store updated used questions in session
        session([$sessionKey => $usedQuestions]);
        
        return ['items' => $selectedQuestions];
    }

    private function getLearningTargets()
    {
        $user = Auth::user();
        
        // Get student preferences to personalize targets
        $preferences = $user->preferences()->first();
        $gradeYear = $preferences?->grade_year ?? 'Grade 10';
        $curriculumBoard = $preferences?->curriculum_board ?? 'General';
        $skillArea = $preferences?->skill_area ?? 'Mathematics';
        
        // Generate dynamic targets based on current date and student level
        $currentWeek = now()->weekOfYear;
        $targets = [
            [
                'title' => "Complete {$skillArea} Basics",
                'status' => 'pending',
                'due' => now()->addDays(7)->format('l'),
                'priority' => 'high'
            ],
            [
                'title' => "Read Chapter " . ($currentWeek % 20 + 1),
                'status' => $currentWeek % 3 === 0 ? 'completed' : 'pending',
                'due' => now()->addDays(3)->format('l'),
                'priority' => 'medium'
            ],
            [
                'title' => "Practice {$skillArea} Problems",
                'status' => 'pending',
                'due' => now()->addDays(5)->format('l'),
                'priority' => 'high'
            ],
            [
                'title' => "Review Previous Topics",
                'status' => $currentWeek % 2 === 0 ? 'completed' : 'pending',
                'due' => now()->addDays(2)->format('l'),
                'priority' => 'low'
            ]
        ];

        // Shuffle targets for variety
        shuffle($targets);
        
        return array_slice($targets, 0, 4);
    }

    private function getWeeklyProgress()
    {
        $user = Auth::user();
        
        // Calculate progress based on completed focus sessions and learning activities
        $totalSessions = $user->focusSessions()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $completedSessions = $user->focusSessions()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('status', 'completed')->count();
        
        // Base progress on focus sessions completed this week
        $progress = $totalSessions > 0 ? ($completedSessions / max($totalSessions, 1)) * 100 : 65;
        
        // Ensure progress is between 0 and 100
        $progress = max(0, min(100, $progress));
        
        return round($progress);
    }
}
