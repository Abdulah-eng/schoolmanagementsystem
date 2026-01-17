<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;

class AdminUserController extends Controller
{
    /**
     * Show all users
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter by role
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }
        
        // Search by name or email
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->with('student')->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show user details
     */
    public function show(User $user)
    {
        $user->load(['student', 'focusSessions', 'cognitiveSessions']);
        
        $stats = [
            'total_focus_sessions' => $user->focusSessions->count(),
            'total_focus_minutes' => round($user->focusSessions->sum('elapsed_seconds') / 60, 1),
            'total_cognitive_sessions' => $user->cognitiveSessions->count(),
            'avg_cognitive_score' => $user->cognitiveSessions->count() > 0 
                ? round($user->cognitiveSessions->avg('score'), 1) 
                : 0,
            'last_activity' => $user->focusSessions->max('created_at'),
        ];
        
        return view('admin.users.show', compact('user', 'stats'));
    }
    
    /**
     * Show create user form
     */
    public function create()
    {
        return view('admin.users.create');
    }
    
    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,teacher,parent,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'email_verified_at' => now(),
        ]);
        
        // Create student record if role is student
        if ($request->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'student_id' => Student::generateStudentId(),
                'grade_level' => $request->grade_level ?? 'Grade 1',
                'section' => $request->section ?? 'A',
                'enrollment_date' => now(),
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'medical_info' => $request->medical_info,
            ]);
        }
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User created successfully');
    }
    
    /**
     * Show edit user form
     */
    public function edit(User $user)
    {
        $user->load('student');
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:student,teacher,parent,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        
        // Update student record if role is student
        if ($request->role === 'student' && $user->student) {
            $user->student->update([
                'grade_level' => $request->grade_level ?? $user->student->grade_level,
                'section' => $request->section ?? $user->student->section,
                'parent_name' => $request->parent_name ?? $user->student->parent_name,
                'parent_phone' => $request->parent_phone ?? $user->student->parent_phone,
                'medical_info' => $request->medical_info ?? $user->student->medical_info,
            ]);
        }
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully');
    }
    
    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current admin
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Cannot delete your own account');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
    
    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        // This would require an 'is_active' field in the users table
        // For now, we'll just return success
        return response()->json(['success' => true]);
    }
}
