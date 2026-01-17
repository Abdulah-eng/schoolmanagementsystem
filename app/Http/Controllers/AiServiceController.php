<?php

namespace App\Http\Controllers;

use App\Models\AiInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiServiceController extends Controller
{
    /**
     * Show AI homework help form
     */
    public function showHomeworkHelp()
    {
        return view('ai.homework-help');
    }

    /**
     * Get AI homework help
     */
    public function getHomeworkHelp(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:100',
            'question' => 'required|string|max:1000',
            'grade_level' => 'required|string|max:20',
        ]);

        try {
            $response = $this->getAiResponse(
                "You are a helpful educational tutor. Help a {$request->grade_level} student with their {$request->subject} homework. Question: {$request->question}. Provide a clear, step-by-step explanation that's appropriate for their grade level."
            );

            // Log the interaction
            AiInteraction::create([
                'user_id' => Auth::id(),
                'interaction_type' => 'homework_help',
                'user_input' => json_encode([
                    'subject' => $request->subject,
                    'question' => $request->question,
                    'grade_level' => $request->grade_level,
                ]),
                'ai_response' => $response,
                'metadata' => [
                    'subject' => $request->subject,
                    'grade_level' => $request->grade_level,
                ],
                'interaction_time' => now(),
            ]);

            return response()->json([
                'success' => true,
                'response' => $response,
            ]);

        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the AI service is temporarily unavailable. Please try again later.',
            ], 500);
        }
    }

    /**
     * Show AI study plan generator
     */
    public function showStudyPlan()
    {
        return view('ai.study-plan');
    }

    /**
     * Generate AI study plan
     */
    public function generateStudyPlan(Request $request)
    {
        $request->validate([
            'subjects' => 'required|array',
            'subjects.*' => 'string|max:100',
            'study_hours' => 'required|integer|min:1|max:24',
            'exam_date' => 'required|date|after:today',
        ]);

        try {
            $subjects = implode(', ', $request->subjects);
            $daysUntilExam = now()->diffInDays($request->exam_date);
            
            $response = $this->getAiResponse(
                "Create a detailed study plan for a student preparing for exams in: {$subjects}. Available study time: {$request->study_hours} hours per day. Days until exam: {$daysUntilExam}. Include daily schedules, topic breakdown, and study techniques."
            );

            // Log the interaction
            AiInteraction::create([
                'user_id' => Auth::id(),
                'interaction_type' => 'study_plan',
                'user_input' => json_encode($request->all()),
                'ai_response' => $response,
                'metadata' => [
                    'subjects' => $request->subjects,
                    'study_hours' => $request->study_hours,
                    'exam_date' => $request->exam_date,
                ],
                'interaction_time' => now(),
            ]);

            return response()->json([
                'success' => true,
                'response' => $response,
            ]);

        } catch (\Exception $e) {
            Log::error('AI Study Plan Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the AI service is temporarily unavailable. Please try again later.',
            ], 500);
        }
    }

    /**
     * Get AI response from OpenAI
     */
    private function getAiResponse(string $prompt): string
    {
        $apiKey = config('openai.api_key');
        
        if (!$apiKey) {
            throw new \Exception('OpenAI API key not configured');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 1000,
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? 'No response generated';
        }

        throw new \Exception('OpenAI API request failed: ' . $response->body());
    }
}
