<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\ProjectComment;
use App\Models\ProjectShowcase;

class StudentProjectsController extends Controller
{
    public function page()
    {
        $user = Auth::user();
        $projects = $user->projects()->with(['tasks', 'comments'])->latest()->get();
        $showcases = ProjectShowcase::where('is_public', true)->latest()->take(6)->get();
        
        return view('student.projects', compact('projects', 'showcases'));
    }

    public function list()
    {
        $user = Auth::user();
        $projects = $user->projects()->with(['tasks', 'comments'])->latest()->get();
        
        return response()->json([
            'projects' => $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'title' => $project->title,
                    'subject' => $project->subject,
                    'due_date' => $project->due_date?->format('Y-m-d'),
                    'progress_percent' => $project->progress_percent,
                    'tasks_count' => $project->tasks->count(),
                    'completed_tasks' => $project->tasks->where('is_done', true)->count(),
                    'comments_count' => $project->comments->count(),
                    'created_at' => $project->created_at->format('Y-m-d'),
                ];
            })
        ]);
    }

    public function data()
    {
        $user = Auth::user();
        
        // Get user's projects
        $projects = $user->projects()->with(['tasks', 'comments.user'])->latest()->get();
        
        $projectsData = $projects->map(function($project) {
            return [
                'id' => $project->id,
                'title' => $project->title,
                'subject' => $project->subject,
                'due_date' => $project->due_date?->format('Y-m-d'),
                'progress_percent' => $project->progress_percent ?? 0,
                'tasks_count' => $project->tasks->count(),
                'completed_tasks' => $project->tasks->where('is_done', true)->count(),
                'comments_count' => $project->comments->count(),
                'created_at' => $project->created_at->format('Y-m-d'),
            ];
        });

        // Get recent comments from all user's projects
        $recentComments = ProjectComment::whereHas('project', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('user')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user_name' => $comment->user->name ?? 'Unknown',
                    'created_at' => $comment->created_at->diffForHumans(),
                ];
            });

        // Get public showcases
        $showcase = ProjectShowcase::where('is_public', true)
            ->latest()
            ->limit(6)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'author' => $item->author,
                    'grade' => $item->grade,
                    'youtube_url' => $item->youtube_url,
                ];
            });

        // Weekly challenge (hardcoded for now, can be made dynamic later)
        $weeklyChallenge = [
            'title' => 'Creative Problem Solving',
            'description' => 'This week\'s challenge focuses on creative problem-solving skills. Create a project that demonstrates innovative thinking.',
            'requirements' => [
                'Must include at least 3 different solutions to a problem',
                'Include visual or interactive elements',
                'Document your creative process',
                'Share your project by Friday'
            ]
        ];

        return response()->json([
            'projects' => $projectsData,
            'recent_comments' => $recentComments,
            'showcase' => $showcase,
            'weekly_challenge' => $weeklyChallenge,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:100',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $project = $user->projects()->create($validator->validated());

        return response()->json(['success' => true, 'project' => $project]);
    }

    public function addTask(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if user owns this project
        if ($project->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task = $project->tasks()->create([
            'title' => $request->title,
            'order' => $project->tasks()->max('order') + 1,
        ]);

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function toggleTask(Request $request, ProjectTask $task)
    {
        // Check if user owns this task's project
        if ($task->project->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->update(['is_done' => !$task->is_done]);
        
        // Update project progress
        $project = $task->project;
        $totalTasks = $project->tasks()->count();
        $completedTasks = $project->tasks()->where('is_done', true)->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        $project->update(['progress_percent' => $progress]);

        return response()->json(['success' => true, 'task' => $task, 'progress' => $progress]);
    }

    public function comment(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if user owns this project
        if ($project->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment = $project->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true, 
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user_name' => $comment->user->name ?? 'Unknown',
                'created_at' => $comment->created_at->diffForHumans(),
            ]
        ]);
    }

    public function show(Project $project)
    {
        $user = Auth::user();
        
        // Check if user owns this project
        if ($project->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        $project->load(['tasks' => function($query) {
            $query->orderBy('order');
        }, 'comments.user']);
        
        return view('student.project-detail', compact('project'));
    }
    
    public function update(Request $request, Project $project)
    {
        $user = Auth::user();
        
        // Check if user owns this project
        if ($project->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'subject' => 'nullable|string|max:100',
            'due_date' => 'nullable|date',
            'progress_percent' => 'sometimes|integer|min:0|max:100',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $project->update($validator->validated());
        
        return response()->json(['success' => true, 'project' => $project]);
    }
    
    public function deleteTask(ProjectTask $task)
    {
        $user = Auth::user();
        
        // Check if user owns this task's project
        if ($task->project->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $project = $task->project;
        $task->delete();
        
        // Recalculate progress
        $totalTasks = $project->tasks()->count();
        $completedTasks = $project->tasks()->where('is_done', true)->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        $project->update(['progress_percent' => $progress]);
        
        return response()->json(['success' => true, 'progress' => $progress]);
    }
    
    public function submitShowcase(Request $request, Project $project)
    {
        $user = Auth::user();
        
        // Check if user owns this project
        if ($project->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'youtube_url' => 'required|url|max:500',
            'grade' => 'nullable|string|max:50',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $showcase = ProjectShowcase::create([
            'title' => $project->title,
            'author' => $user->name,
            'grade' => $request->grade,
            'youtube_url' => $request->youtube_url,
            'is_public' => true,
        ]);
        
        return response()->json(['success' => true, 'showcase' => $showcase]);
    }

    public function addShowcase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'grade' => 'nullable|string|max:50',
            'youtube_url' => 'required|url|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $showcase = ProjectShowcase::create($validator->validated());

        return response()->json(['success' => true, 'showcase' => $showcase]);
    }
}

