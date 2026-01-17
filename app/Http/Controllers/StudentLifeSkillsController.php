<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\LifeSkillSchedule;
use App\Models\LifeSkillRoutine;
use App\Models\LifeSkillBudget;
use App\Models\LifeSkillCommunication;

class StudentLifeSkillsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's life skills data
        $schedule = $this->getWeeklySchedule($user);
        $routine = $this->getDailyRoutine($user);
        $budget = $this->getBudgetData($user);
        $communicationStats = $this->getCommunicationStats($user);
        
        return view('student.life-skills', compact('schedule', 'routine', 'budget', 'communicationStats'));
    }

    // Schedule Management
    public function getSchedule(Request $request)
    {
        $user = Auth::user();
        $schedule = $this->getWeeklySchedule($user);
        
        return response()->json($schedule);
    }

    public function storeSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:480',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        
        $schedule = LifeSkillSchedule::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'duration' => $request->duration,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'schedule' => $schedule,
            'message' => 'Event added to schedule successfully!'
        ]);
    }

    // Routine Management
    public function getRoutine(Request $request)
    {
        $user = Auth::user();
        $routine = $this->getDailyRoutine($user);
        
        return response()->json($routine);
    }

    public function storeRoutine(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity' => 'required|string|max:255',
            'time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:480',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        
        $routine = LifeSkillRoutine::create([
            'user_id' => $user->id,
            'activity' => $request->activity,
            'time' => $request->time,
            'duration' => $request->duration,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'routine' => $routine,
            'message' => 'Activity added to routine successfully!'
        ]);
    }

    // Budget Management
    public function getBudget(Request $request)
    {
        $user = Auth::user();
        $budget = $this->getBudgetData($user);
        
        return response()->json($budget);
    }

    public function storeBudget(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'monthly_allowance' => 'required|numeric|min:0',
            'savings_goal' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        
        $budget = LifeSkillBudget::updateOrCreate(
            ['user_id' => $user->id],
            [
                'monthly_allowance' => $request->monthly_allowance,
                'savings_goal' => $request->savings_goal,
                'updated_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'budget' => $budget,
            'message' => 'Budget data saved successfully!'
        ]);
    }

    // Communication Scenarios
    public function startCommunicationScenario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scenario_type' => 'required|string|in:group-project,teacher-meeting,parent-conversation',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        
        $communication = LifeSkillCommunication::create([
            'user_id' => $user->id,
            'scenario_type' => $request->scenario_type,
            'started_at' => now(),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'communication_id' => $communication->id,
            'message' => 'Communication scenario started!'
        ]);
    }

    public function completeCommunicationScenario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'communication_id' => 'required|exists:life_skill_communications,id',
            'reflection' => 'required|string|max:1000',
            'confidence_rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $communication = LifeSkillCommunication::where('id', $request->communication_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$communication) {
            return response()->json(['error' => 'Communication session not found'], 404);
        }

        $communication->update([
            'status' => 'completed',
            'completed_at' => now(),
            'reflection' => $request->reflection,
            'confidence_rating' => $request->confidence_rating,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Communication scenario completed successfully!'
        ]);
    }

    // Private helper methods
    private function getWeeklySchedule($user)
    {
        return LifeSkillSchedule::where('user_id', $user->id)
            ->orderBy('start_time')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'name' => $event->name,
                    'day' => $event->day,
                    'start_time' => $event->start_time,
                    'duration' => $event->duration,
                ];
            });
    }

    private function getDailyRoutine($user)
    {
        return LifeSkillRoutine::where('user_id', $user->id)
            ->orderBy('time')
            ->get()
            ->map(function ($routine) {
                return [
                    'id' => $routine->id,
                    'activity' => $routine->activity,
                    'time' => $routine->time,
                    'duration' => $routine->duration,
                ];
            });
    }

    private function getBudgetData($user)
    {
        $budget = LifeSkillBudget::where('user_id', $user->id)->first();
        
        if (!$budget) {
            return [
                'monthly_allowance' => 0,
                'savings_goal' => 0,
                'current_savings' => 0,
                'expenses' => [],
            ];
        }

        return [
            'monthly_allowance' => $budget->monthly_allowance,
            'savings_goal' => $budget->savings_goal,
            'current_savings' => $budget->current_savings ?? 0,
            'expenses' => $this->getExpenses($user),
        ];
    }

    private function getExpenses($user)
    {
        // This would be implemented when expense tracking is added
        return [];
    }

    private function getCommunicationStats($user)
    {
        $totalScenarios = LifeSkillCommunication::where('user_id', $user->id)->count();
        $completedScenarios = LifeSkillCommunication::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $averageConfidence = LifeSkillCommunication::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('confidence_rating')
            ->avg('confidence_rating');

        return [
            'total_scenarios' => $totalScenarios,
            'completed_scenarios' => $completedScenarios,
            'completion_rate' => $totalScenarios > 0 ? round(($completedScenarios / $totalScenarios) * 100) : 0,
            'average_confidence' => round($averageConfidence ?? 0, 1),
        ];
    }
}
