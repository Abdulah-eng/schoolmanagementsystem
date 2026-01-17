<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\CognitiveSession;
use App\Models\CognitiveScore;
use App\Models\CreativeStory;
use App\Models\StudentPreference;

class StudentCognitiveController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get student's cognitive skills progress
        $progress = $this->getCognitiveProgress($user);
        
        // Get recent achievements
        $achievements = $this->getRecentAchievements($user);
        
        // Get current level for each skill
        $skillLevels = $this->getSkillLevels($user);
        
        return view('student.cognitive-skills', compact('progress', 'achievements', 'skillLevels'));
    }

    public function startMemoryChallenge(Request $request)
    {
        $user = Auth::user();
        
        // Create a new memory challenge session
        $session = CognitiveSession::create([
            'user_id' => $user->id,
            'skill_type' => 'memory',
            'difficulty_level' => $this->getCurrentLevel($user, 'memory'),
            'status' => 'active',
            'started_at' => now(),
        ]);

        // Try AI-generated pattern with fallback
        $pattern = $this->generateMemoryPatternAI($session->difficulty_level);
        if (!$pattern) {
            $pattern = $this->generateMemoryPattern($session->difficulty_level);
        }
        
        return response()->json([
            'session_id' => $session->id,
            'pattern' => $pattern,
            'difficulty' => $session->difficulty_level
        ]);
    }

    public function completeMemoryChallenge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:cognitive_sessions,id',
            'pattern' => 'required|string',
            'user_pattern' => 'required|string',
            'time_taken' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $session = CognitiveSession::where('id', $request->session_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $isCorrect = $request->pattern === $request->user_pattern;
        $score = $this->calculateMemoryScore($isCorrect, $session->difficulty_level, $request->time_taken);

        // Update session
        $session->update([
            'status' => 'completed',
            'completed_at' => now(),
            'score' => $score,
            'is_correct' => $isCorrect,
            'time_taken' => $request->time_taken,
        ]);

        // Update cognitive score
        $this->updateCognitiveScore($user, 'memory', $score);

        // Check for level up
        $levelUp = $this->checkLevelUp($user, 'memory', $score);

        return response()->json([
            'success' => true,
            'score' => $score,
            'is_correct' => $isCorrect,
            'level_up' => $levelUp,
            'new_level' => $this->getCurrentLevel($user, 'memory')
        ]);
    }

    public function startPlanningPuzzle(Request $request)
    {
        $user = Auth::user();
        
        // Create a new planning puzzle session
        $session = CognitiveSession::create([
            'user_id' => $user->id,
            'skill_type' => 'planning',
            'difficulty_level' => $this->getCurrentLevel($user, 'planning'),
            'status' => 'active',
            'started_at' => now(),
        ]);

        // Generate puzzle based on difficulty
        $puzzle = $this->generatePlanningPuzzle($session->difficulty_level);
        
        return response()->json([
            'session_id' => $session->id,
            'puzzle' => $puzzle,
            'difficulty' => $session->difficulty_level
        ]);
    }

    public function completePlanningPuzzle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:cognitive_sessions,id',
            'solution' => 'required|array',
            'time_taken' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $session = CognitiveSession::where('id', $request->session_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $isCorrect = $this->validatePlanningSolution($request->solution, $session->difficulty_level);
        $score = $this->calculatePlanningScore($isCorrect, $session->difficulty_level, $request->time_taken);

        // Update session
        $session->update([
            'status' => 'completed',
            'completed_at' => now(),
            'score' => $score,
            'is_correct' => $isCorrect,
            'time_taken' => $request->time_taken,
        ]);

        // Update cognitive score
        $this->updateCognitiveScore($user, 'planning', $score);

        return response()->json([
            'success' => true,
            'score' => $score,
            'is_correct' => $isCorrect,
            'new_level' => $this->getCurrentLevel($user, 'planning')
        ]);
    }

    public function startFlexibilityTest(Request $request)
    {
        $user = Auth::user();
        
        // Create a new flexibility test session
        $session = CognitiveSession::create([
            'user_id' => $user->id,
            'skill_type' => 'flexibility',
            'difficulty_level' => $this->getCurrentLevel($user, 'flexibility'),
            'status' => 'active',
            'started_at' => now(),
        ]);

        // Try AI-generated test with fallback
        $test = $this->generateFlexibilityTestAI($session->difficulty_level);
        if (!$test) {
            $test = $this->generateFlexibilityTest($session->difficulty_level);
        }
        
        return response()->json([
            'session_id' => $session->id,
            'test' => $test,
            'difficulty' => $session->difficulty_level
        ]);
    }

    public function completeFlexibilityTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:cognitive_sessions,id',
            'answers' => 'required|array',
            'time_taken' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $session = CognitiveSession::where('id', $request->session_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $correctAnswers = $this->validateFlexibilityAnswers($request->answers, $session->difficulty_level);
        $score = $this->calculateFlexibilityScore($correctAnswers, $session->difficulty_level, $request->time_taken);

        // Update session
        $session->update([
            'status' => 'completed',
            'completed_at' => now(),
            'score' => $score,
            'is_correct' => $correctAnswers > 0,
            'time_taken' => $request->time_taken,
        ]);

        // Update cognitive score
        $this->updateCognitiveScore($user, 'flexibility', $score);

        return response()->json([
            'success' => true,
            'score' => $score,
            'correct_answers' => $correctAnswers,
            'new_level' => $this->getCurrentLevel($user, 'flexibility')
        ]);
    }

    public function submitStory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'story' => 'required|string|min:50|max:1000',
            'words_used' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        
        // Create creative story entry
        $story = CreativeStory::create([
            'user_id' => $user->id,
            'story_content' => $request->story,
            'words_used' => json_encode($request->words_used),
            'word_count' => str_word_count($request->story),
            'submitted_at' => now(),
        ]);

        // Update creative thinking score
        $this->updateCognitiveScore($user, 'creative', 20);

        return response()->json([
            'success' => true,
            'story_id' => $story->id,
            'message' => 'Story submitted successfully!'
        ]);
    }

    public function getProgress(Request $request)
    {
        $user = Auth::user();
        $progress = $this->getCognitiveProgress($user);
        
        return response()->json($progress);
    }

    private function getCognitiveProgress($user)
    {
        // Get scores for different cognitive skills
        $memoryScore = CognitiveScore::where('user_id', $user->id)
            ->where('skill_type', 'memory')
            ->orderBy('created_at', 'desc')
            ->first();

        $planningScore = CognitiveScore::where('user_id', $user->id)
            ->where('skill_type', 'planning')
            ->orderBy('created_at', 'desc')
            ->first();

        $flexibilityScore = CognitiveScore::where('user_id', $user->id)
            ->where('skill_type', 'flexibility')
            ->orderBy('created_at', 'desc')
            ->first();

        $creativeScore = CognitiveScore::where('user_id', $user->id)
            ->where('skill_type', 'creative')
            ->orderBy('created_at', 'desc')
            ->first();

        return [
            'memory' => $memoryScore ? $memoryScore->current_score : 0,
            'planning' => $planningScore ? $planningScore->current_score : 0,
            'flexibility' => $flexibilityScore ? $flexibilityScore->current_score : 0,
            'creative' => $creativeScore ? $creativeScore->current_score : 0,
        ];
    }

    private function getCurrentLevel($user, $skillType)
    {
        $score = CognitiveScore::where('user_id', $user->id)
            ->where('skill_type', $skillType)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$score) {
            return 1;
        }

        // Calculate level based on score (every 100 points = 1 level)
        return min(10, max(1, intval($score->current_score / 100) + 1));
    }

    private function generateMemoryPattern($level)
    {
        $length = 3 + ($level - 1);
        $symbols = ['🔴', '🔵', '🟡', '🟢', '🟣'];
        $pattern = '';
        
        for ($i = 0; $i < $length; $i++) {
            $pattern .= $symbols[array_rand($symbols)];
        }
        
        return $pattern;
    }

    private function generatePlanningPuzzle($level)
    {
        $puzzles = [
            1 => [
                'description' => 'Arrange these steps in the correct order to make a sandwich:',
                'steps' => ['Put bread on plate', 'Add cheese', 'Add meat', 'Close sandwich', 'Cut diagonally'],
                'correct_order' => [0, 2, 1, 3, 4]
            ],
            2 => [
                'description' => 'Order these steps for morning routine:',
                'steps' => ['Brush teeth', 'Get dressed', 'Eat breakfast', 'Pack bag', 'Leave house'],
                'correct_order' => [1, 0, 2, 3, 4]
            ],
            3 => [
                'description' => 'Sequence for solving a math problem:',
                'steps' => ['Read problem', 'Identify variables', 'Choose method', 'Solve', 'Check answer'],
                'correct_order' => [0, 1, 2, 3, 4]
            ]
        ];

        return $puzzles[min($level, 3)] ?? $puzzles[1];
    }

    private function generateFlexibilityTest($level)
    {
        $tests = [
            1 => [
                'instruction' => 'The rule has changed! Now you must click the OPPOSITE of what you see:',
                'options' => ['Click RED if you see BLUE', 'Click BLUE if you see RED', 'Click GREEN if you see YELLOW'],
                'correct_answers' => [0, 1, 2]
            ],
            2 => [
                'instruction' => 'New rule: Click the SECOND option when you see the FIRST:',
                'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
                'correct_answers' => [1, 2, 3, 0]
            ]
        ];

        return $tests[min($level, 2)] ?? $tests[1];
    }

    private function generateMemoryPatternAI(int $level): ?string
    {
        try {
            $length = 3 + ($level - 1);
            $prompt = 'Generate a random memory pattern of length ' . $length . ' using ONLY these emoji symbols: 🔴 🔵 🟡 🟢 🟣. Return a single line string exactly of that length, no spaces, no extra text.';
            $response = Http::timeout(12)
                ->withToken(env('OPENAI_API_KEY'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You generate compact JSON-free outputs when asked.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 20,
                ]);
            if ($response->ok()) {
                $text = trim($response->json('choices.0.message.content'));
                $text = preg_replace('/\s+/', '', $text);
                // Validate allowed symbols and exact length
                if (preg_match('/^[🔴🔵🟡🟢🟣]{' . $length . '}$/u', $text)) {
                    return $text;
                }
            }
        } catch (\Throwable $e) {
            // ignore and fallback
        }
        return null;
    }

    private function generateFlexibilityTestAI(int $level): ?array
    {
        try {
            $seed = time() . rand(1000, 9999);
            $prompt = 'Create a short cognitive flexibility mini-test JSON with fields: instruction (string), options (array of 3-5 short strings), correct_answers (array of integer indices referencing options). Keep it concise for a student. Vary rules. Seed: ' . $seed;
            $response = Http::timeout(12)
                ->withToken(env('OPENAI_API_KEY'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Only output valid minified JSON object. No markdown.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.8,
                    'max_tokens' => 200,
                ]);
            if ($response->ok()) {
                $json = $response->json('choices.0.message.content');
                $data = json_decode($json, true);
                if (is_array($data) && isset($data['instruction'], $data['options']) && isset($data['correct_answers'])) {
                    // sanity checks
                    $data['options'] = array_values(array_filter($data['options'], fn($o) => is_string($o) && strlen($o) <= 60));
                    if (count($data['options']) >= 3 && is_array($data['correct_answers'])) {
                        return [
                            'instruction' => (string) $data['instruction'],
                            'options' => $data['options'],
                            'correct_answers' => array_values(array_filter($data['correct_answers'], fn($i) => is_int($i) && $i >= 0 && $i < count($data['options']))),
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore and fallback
        }
        return null;
    }

    private function calculateMemoryScore($isCorrect, $level, $timeTaken)
    {
        if (!$isCorrect) {
            return 0;
        }

        $baseScore = 50 + ($level * 25);
        $timeBonus = max(0, 30 - $timeTaken) * 2;
        
        return $baseScore + $timeBonus;
    }

    private function calculatePlanningScore($isCorrect, $level, $timeTaken)
    {
        if (!$isCorrect) {
            return 0;
        }

        $baseScore = 60 + ($level * 30);
        $timeBonus = max(0, 45 - $timeTaken) * 1.5;
        
        return $baseScore + $timeBonus;
    }

    private function calculateFlexibilityScore($correctAnswers, $level, $timeTaken)
    {
        $baseScore = $correctAnswers * 40 + ($level * 20);
        $timeBonus = max(0, 60 - $timeTaken);
        
        return $baseScore + $timeBonus;
    }

    private function validatePlanningSolution($solution, $level)
    {
        $puzzle = $this->generatePlanningPuzzle($level);
        return $solution == $puzzle['correct_order'];
    }

    private function validateFlexibilityAnswers($answers, $level)
    {
        $test = $this->generateFlexibilityTest($level);
        $correct = 0;
        
        foreach ($answers as $index => $answer) {
            if (in_array($answer, $test['correct_answers'])) {
                $correct++;
            }
        }
        
        return $correct;
    }

    private function updateCognitiveScore($user, $skillType, $score)
    {
        $cognitiveScore = CognitiveScore::where('user_id', $user->id)
            ->where('skill_type', $skillType)
            ->first();

        if ($cognitiveScore) {
            $cognitiveScore->update([
                'current_score' => $cognitiveScore->current_score + $score,
                'highest_score' => max($cognitiveScore->highest_score, $score),
                'total_sessions' => $cognitiveScore->total_sessions + 1,
                'updated_at' => now(),
            ]);
        } else {
            CognitiveScore::create([
                'user_id' => $user->id,
                'skill_type' => $skillType,
                'current_score' => $score,
                'highest_score' => $score,
                'total_sessions' => 1,
            ]);
        }
    }

    private function checkLevelUp($user, $skillType, $score)
    {
        $oldLevel = $this->getCurrentLevel($user, $skillType);
        $this->updateCognitiveScore($user, $skillType, $score);
        $newLevel = $this->getCurrentLevel($user, $skillType);
        
        return $newLevel > $oldLevel;
    }

    private function getRecentAchievements($user)
    {
        // Get recent high scores and achievements
        $recentScores = CognitiveSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('score', '>', 0)
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get();

        $achievements = [];
        foreach ($recentScores as $score) {
            if ($score->score >= 100) {
                $achievements[] = [
                    'type' => 'high_score',
                    'skill' => $score->skill_type,
                    'score' => $score->score,
                    'date' => $score->completed_at->format('M d')
                ];
            }
        }

        return $achievements;
    }

    private function getSkillLevels($user)
    {
        return [
            'memory' => $this->getCurrentLevel($user, 'memory'),
            'planning' => $this->getCurrentLevel($user, 'planning'),
            'flexibility' => $this->getCurrentLevel($user, 'flexibility'),
            'creative' => $this->getCurrentLevel($user, 'creative'),
        ];
    }
}
