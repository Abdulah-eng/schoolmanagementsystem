<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AiServiceController;

// Public routes
Route::get('/', function () {
    return view('home.index');
})->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Admin routes
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\AdminUserController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\AdminUserController::class, 'store'])->name('store');
            Route::get('/{user}', [\App\Http\Controllers\AdminUserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [\App\Http\Controllers\AdminUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [\App\Http\Controllers\AdminUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/toggle-status', [\App\Http\Controllers\AdminUserController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // System Management
        Route::prefix('system')->name('system.')->group(function () {
            Route::get('/', [\App\Http\Controllers\AdminSystemController::class, 'index'])->name('index');
            Route::post('/update', [\App\Http\Controllers\AdminSystemController::class, 'update'])->name('update');
            Route::post('/clear-cache', [\App\Http\Controllers\AdminSystemController::class, 'clearCache'])->name('clear-cache');
            Route::post('/maintenance', [\App\Http\Controllers\AdminSystemController::class, 'runMaintenance'])->name('maintenance');
            Route::get('/logs', [\App\Http\Controllers\AdminSystemController::class, 'getLogs'])->name('logs');
            Route::get('/health', [\App\Http\Controllers\AdminSystemController::class, 'getHealth'])->name('health');
        });
        
        // Analytics
        Route::get('/analytics', function () {
            return view('admin.analytics');
        })->name('analytics');
        Route::get('/analytics/data', [\App\Http\Controllers\AdminDashboardController::class, 'getAnalytics'])->name('analytics.data');
        
        // Course Management
        Route::get('/courses', function () {
            return view('admin.courses');
        })->name('courses');
        
        // System Logs
        Route::get('/logs', function () {
            return view('admin.logs');
        })->name('logs');
        
        // Settings
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');
        
        // Enrollment Management
        Route::get('/enrollments', [\App\Http\Controllers\EnrollmentController::class, 'index'])->name('enrollments');
        Route::get('/courses/{course}/enroll', [\App\Http\Controllers\EnrollmentController::class, 'show'])->name('courses.enroll');
        Route::post('/enrollment/{course}/enroll', [\App\Http\Controllers\EnrollmentController::class, 'enroll'])->name('enrollment.enroll');
        Route::post('/enrollment/{course}/students/{student}/unenroll', [\App\Http\Controllers\EnrollmentController::class, 'unenroll'])->name('enrollment.unenroll');
    });
    
    // Parent routes
    Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\ParentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/child-performance', [\App\Http\Controllers\ParentDashboardController::class, 'getChildPerformance'])->name('child-performance');
        
        Route::get('/children', function () {
            return view('parent.children');
        })->name('children');
        
        Route::get('/progress', [\App\Http\Controllers\ParentProgressController::class, 'index'])->name('progress');

        Route::get('/settings', [\App\Http\Controllers\ParentSettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [\App\Http\Controllers\ParentSettingsController::class, 'update'])->name('settings.update');

        // Focus Mode
        Route::prefix('focus')->name('focus.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ParentFocusController::class, 'index'])->name('index');
            Route::post('/start', [\App\Http\Controllers\ParentFocusController::class, 'startSession'])->name('start');
            Route::get('/stats', [\App\Http\Controllers\ParentFocusController::class, 'getStats'])->name('stats');
            Route::get('/active', [\App\Http\Controllers\ParentFocusController::class, 'getActiveSession'])->name('active');
            Route::post('/sessions/{session}/complete', [\App\Http\Controllers\ParentFocusController::class, 'completeSession'])->name('complete');
            Route::post('/sessions/{session}/cancel', [\App\Http\Controllers\ParentFocusController::class, 'cancelSession'])->name('cancel');
        });
        
        // Screen Time Management
        Route::prefix('screen-time')->name('screen-time.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ParentScreenTimeController::class, 'index'])->name('index');
            Route::post('/set-limits', [\App\Http\Controllers\ParentScreenTimeController::class, 'setLimits'])->name('set-limits');
            Route::get('/usage', [\App\Http\Controllers\ParentScreenTimeController::class, 'getUsage'])->name('usage');
            Route::post('/toggle', [\App\Http\Controllers\ParentScreenTimeController::class, 'toggleRestrictions'])->name('toggle');
        });

        // Messages
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ParentMessageController::class, 'index'])->name('index');
            Route::get('/sent', [\App\Http\Controllers\ParentMessageController::class, 'sent'])->name('sent');
            Route::get('/create', [\App\Http\Controllers\ParentMessageController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\ParentMessageController::class, 'store'])->name('store');
            Route::get('/{message}', [\App\Http\Controllers\ParentMessageController::class, 'show'])->name('show');
        });
    });
    
    // Teacher routes
    Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\TeacherDashboardController::class, 'index'])->name('dashboard');
        Route::get('/courses', [\App\Http\Controllers\TeacherCourseController::class, 'index'])->name('courses.index');
        Route::post('/courses', [\App\Http\Controllers\TeacherCourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}', [\App\Http\Controllers\TeacherCourseController::class, 'show'])->name('courses.show');
        
        // Enrollment
        Route::get('/courses/{course}/enroll', [\App\Http\Controllers\EnrollmentController::class, 'show'])->name('courses.enroll');
        Route::post('/enrollment/{course}/enroll', [\App\Http\Controllers\EnrollmentController::class, 'enroll'])->name('enrollment.enroll');
        Route::post('/enrollment/{course}/students/{student}/unenroll', [\App\Http\Controllers\EnrollmentController::class, 'unenroll'])->name('enrollment.unenroll');

        Route::get('/students', [\App\Http\Controllers\TeacherStudentController::class, 'index'])->name('students');
        Route::get('/progress', [\App\Http\Controllers\TeacherProgressController::class, 'index'])->name('progress');

        Route::get('/messages', [\App\Http\Controllers\TeacherMessageController::class, 'index'])->name('messages.index');
        Route::post('/messages', [\App\Http\Controllers\TeacherMessageController::class, 'store'])->name('messages.store');
        Route::get('/settings', function () {
            return view('teacher.settings');
        })->name('settings');
        
        // Assignments
        Route::prefix('assignments')->name('assignments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\TeacherAssignmentController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\TeacherAssignmentController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\TeacherAssignmentController::class, 'store'])->name('store');
            Route::get('/{assignment}', [\App\Http\Controllers\TeacherAssignmentController::class, 'show'])->name('show');
            Route::post('/{assignment}/grade', [\App\Http\Controllers\TeacherAssignmentController::class, 'gradeSubmission'])->name('grade');
            Route::get('/{assignment}/stats', [\App\Http\Controllers\TeacherAssignmentController::class, 'getStats'])->name('stats');
        });
        
        // Class performance
        Route::get('/class-performance', [\App\Http\Controllers\TeacherDashboardController::class, 'getClassPerformance'])->name('class-performance');
    });
    
    // Student routes
    Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
        // Profile creation (accessible before profile completion)
        Route::get('/profile/create', [\App\Http\Controllers\StudentProfileController::class, 'create'])->name('profile.create');
        Route::post('/profile', [\App\Http\Controllers\StudentProfileController::class, 'store'])->name('profile.store');
        
        Route::get('/dashboard', [\App\Http\Controllers\StudentDashboardController::class, 'index'])->name('dashboard');
        Route::post('/preferences', [\App\Http\Controllers\StudentDashboardController::class, 'savePreferences'])->name('preferences.save');
        
        // Focus API
        Route::prefix('focus')->name('focus.')->group(function () {
            Route::get('/', [\App\Http\Controllers\StudentFocusController::class, 'index'])->name('index');
            Route::post('/start', [\App\Http\Controllers\StudentFocusController::class, 'start'])->name('start');
            Route::post('/{focusSession}/pause', [\App\Http\Controllers\StudentFocusController::class, 'pause'])->name('pause');
            Route::post('/{focusSession}/resume', [\App\Http\Controllers\StudentFocusController::class, 'resume'])->name('resume');
            Route::post('/{focusSession}/complete', [\App\Http\Controllers\StudentFocusController::class, 'complete'])->name('complete');
            Route::post('/{focusSession}/elapsed', [\App\Http\Controllers\StudentFocusController::class, 'updateElapsed'])->name('elapsed');
            Route::post('/cancel-all', [\App\Http\Controllers\StudentFocusController::class, 'cancelAll'])->name('cancel_all');

            // Goals
            Route::get('/goals', [\App\Http\Controllers\StudentFocusController::class, 'goalsIndex'])->name('goals.index');
            Route::post('/goals', [\App\Http\Controllers\StudentFocusController::class, 'goalsStore'])->name('goals.store');
            Route::post('/goals/{goal}/toggle', [\App\Http\Controllers\StudentFocusController::class, 'goalsToggle'])->name('goals.toggle');
            Route::delete('/goals/{goal}', [\App\Http\Controllers\StudentFocusController::class, 'goalsDestroy'])->name('goals.destroy');

            // Micro breaks
            Route::post('/micro-break', [\App\Http\Controllers\StudentFocusController::class, 'logMicroBreak'])->name('micro_break');
        });

        // Breathing API
        Route::prefix('breathing')->name('breathing.')->group(function () {
            Route::post('/start', [\App\Http\Controllers\StudentBreathingController::class, 'start'])->name('start');
            Route::post('/{breathingSession}/complete', [\App\Http\Controllers\StudentBreathingController::class, 'complete'])->name('complete');
        });
        
        // Integrated Session (40-minute structured session)
        Route::get('/session', [\App\Http\Controllers\StudentSessionController::class, 'index'])->name('session');
        Route::post('/session/start', [\App\Http\Controllers\StudentSessionController::class, 'start'])->name('session.start');
        Route::post('/session/breathing/start', [\App\Http\Controllers\StudentSessionController::class, 'startBreathing'])->name('session.start-breathing');
        Route::post('/session/breathing/complete', [\App\Http\Controllers\StudentSessionController::class, 'completeBreathing'])->name('session.complete-breathing');
        Route::post('/session/learning/start', [\App\Http\Controllers\StudentSessionController::class, 'startLearning'])->name('session.start-learning');
        Route::post('/session/learning/complete', [\App\Http\Controllers\StudentSessionController::class, 'completeLearning'])->name('session.complete-learning');
        Route::post('/session/cognitive/start', [\App\Http\Controllers\StudentSessionController::class, 'startCognitive'])->name('session.start-cognitive');
        Route::post('/session/cognitive/complete', [\App\Http\Controllers\StudentSessionController::class, 'completeCognitive'])->name('session.complete-cognitive');
        Route::post('/session/life-skills/start', [\App\Http\Controllers\StudentSessionController::class, 'startLifeSkills'])->name('session.start-life-skills');
        Route::post('/session/life-skills/complete', [\App\Http\Controllers\StudentSessionController::class, 'completeLifeSkills'])->name('session.complete-life-skills');
        Route::post('/session/complete', [\App\Http\Controllers\StudentSessionController::class, 'complete'])->name('session.complete');
        Route::get('/session/status', [\App\Http\Controllers\StudentSessionController::class, 'status'])->name('session.status');
        
        Route::get('/focus-mode', function () {
            return view('student.focus-mode');
        })->name('focus-mode');
        
        Route::get('/learning', [\App\Http\Controllers\StudentLearningController::class, 'index'])->name('learning');
        Route::post('/learning/explain', [\App\Http\Controllers\StudentLearningController::class, 'explain'])->name('learning.explain');
        Route::post('/learning/quiz', [\App\Http\Controllers\StudentLearningController::class, 'quiz'])->name('learning.quiz');
        
        // Enhanced learning with neuroscience features
        Route::post('/learning/session/start', [\App\Http\Controllers\StudentEnhancedLearningController::class, 'startSession'])->name('learning.session.start');
        Route::post('/learning/session/break', [\App\Http\Controllers\StudentEnhancedLearningController::class, 'completeBreak'])->name('learning.session.break');
        Route::get('/learning/session/next-interval', [\App\Http\Controllers\StudentEnhancedLearningController::class, 'getNextInterval'])->name('learning.session.next-interval');
        Route::post('/learning/session/complete', [\App\Http\Controllers\StudentEnhancedLearningController::class, 'completeSession'])->name('learning.session.complete');
        
        // Cognitive Skills
        Route::get('/cognitive-skills', [App\Http\Controllers\StudentCognitiveController::class, 'index'])->name('cognitive-skills');
        Route::post('/cognitive-skills/memory/start', [App\Http\Controllers\StudentCognitiveController::class, 'startMemoryChallenge'])->name('cognitive-skills.memory.start');
        Route::post('/cognitive-skills/memory/complete', [App\Http\Controllers\StudentCognitiveController::class, 'completeMemoryChallenge'])->name('cognitive-skills.memory.complete');
        Route::post('/cognitive-skills/planning/start', [App\Http\Controllers\StudentCognitiveController::class, 'startPlanningPuzzle'])->name('cognitive-skills.planning.start');
        Route::post('/cognitive-skills/planning/complete', [App\Http\Controllers\StudentCognitiveController::class, 'completePlanningPuzzle'])->name('cognitive-skills.planning.complete');
        Route::post('/cognitive-skills/flexibility/start', [App\Http\Controllers\StudentCognitiveController::class, 'startFlexibilityTest'])->name('cognitive-skills.flexibility.start');
        Route::post('/cognitive-skills/flexibility/complete', [App\Http\Controllers\StudentCognitiveController::class, 'completeFlexibilityTest'])->name('cognitive-skills.flexibility.complete');
        Route::post('/cognitive-skills/story', [App\Http\Controllers\StudentCognitiveController::class, 'submitStory'])->name('cognitive-skills.story');
        Route::get('/cognitive-skills/progress', [App\Http\Controllers\StudentCognitiveController::class, 'getProgress'])->name('cognitive-skills.progress');
        
        Route::get('/life-skills', [App\Http\Controllers\StudentLifeSkillsController::class, 'index'])->name('life-skills');
        Route::get('/life-skills/schedule', [App\Http\Controllers\StudentLifeSkillsController::class, 'getSchedule'])->name('life-skills.schedule');
        Route::post('/life-skills/schedule', [App\Http\Controllers\StudentLifeSkillsController::class, 'storeSchedule'])->name('life-skills.schedule.store');
        Route::get('/life-skills/routine', [App\Http\Controllers\StudentLifeSkillsController::class, 'getRoutine'])->name('life-skills.routine');
        Route::post('/life-skills/routine', [App\Http\Controllers\StudentLifeSkillsController::class, 'storeRoutine'])->name('life-skills.routine.store');
        Route::get('/life-skills/budget', [App\Http\Controllers\StudentLifeSkillsController::class, 'getBudget'])->name('life-skills.budget');
        Route::post('/life-skills/budget', [App\Http\Controllers\StudentLifeSkillsController::class, 'storeBudget'])->name('life-skills.budget.store');
        Route::post('/life-skills/communication/start', [App\Http\Controllers\StudentLifeSkillsController::class, 'startCommunicationScenario'])->name('life-skills.communication.start');
        Route::post('/life-skills/communication/complete', [App\Http\Controllers\StudentLifeSkillsController::class, 'completeCommunicationScenario'])->name('life-skills.communication.complete');
        
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/', [\App\Http\Controllers\StudentProjectsController::class, 'page'])->name('index');
            Route::get('/data', [\App\Http\Controllers\StudentProjectsController::class, 'data'])->name('data');
            Route::post('/', [\App\Http\Controllers\StudentProjectsController::class, 'create'])->name('create');
            Route::get('/{project}', [\App\Http\Controllers\StudentProjectsController::class, 'show'])->name('show');
            Route::match(['put', 'patch'], '/{project}', [\App\Http\Controllers\StudentProjectsController::class, 'update'])->name('update');
            Route::post('/{project}/comment', [\App\Http\Controllers\StudentProjectsController::class, 'comment'])->name('comment');
            Route::post('/{project}/tasks', [\App\Http\Controllers\StudentProjectsController::class, 'addTask'])->name('tasks.add');
            Route::post('/tasks/{task}/toggle', [\App\Http\Controllers\StudentProjectsController::class, 'toggleTask'])->name('tasks.toggle');
            Route::match(['delete', 'post'], '/tasks/{task}', [\App\Http\Controllers\StudentProjectsController::class, 'deleteTask'])->name('tasks.delete');
            Route::post('/{project}/submit-showcase', [\App\Http\Controllers\StudentProjectsController::class, 'submitShowcase'])->name('submit-showcase');
        });
        
        Route::get('/progress', [\App\Http\Controllers\StudentProgressController::class, 'page'])->name('progress');
        
        // Assignments
        Route::get('/assignments', [\App\Http\Controllers\StudentAssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/assignments/{assignment}', [\App\Http\Controllers\StudentAssignmentController::class, 'show'])->name('assignments.show');
        Route::post('/assignments/{assignment}/submit', [\App\Http\Controllers\StudentAssignmentController::class, 'submit'])->name('assignments.submit');
        Route::get('/progress/data', [\App\Http\Controllers\StudentProgressController::class, 'data'])->name('progress.data');
        
        Route::get('/settings', [\App\Http\Controllers\StudentSettingsController::class, 'page'])->name('settings');
        Route::post('/settings/profile', [\App\Http\Controllers\StudentSettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::post('/settings/password', [\App\Http\Controllers\StudentSettingsController::class, 'updatePassword'])->name('settings.password');
        Route::post('/settings/preferences', [\App\Http\Controllers\StudentSettingsController::class, 'updatePreferences'])->name('settings.preferences');
        
        Route::get('/profile', function () {
            return view('student.profile');
        })->name('profile');
    });
    
    // AI Service routes (available to all authenticated users)
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/homework-help', [AiServiceController::class, 'showHomeworkHelp'])->name('homework-help');
        Route::post('/homework-help', [AiServiceController::class, 'getHomeworkHelp']);
        
        Route::get('/study-plan', [AiServiceController::class, 'showStudyPlan'])->name('study-plan');
        Route::post('/study-plan', [AiServiceController::class, 'generateStudyPlan']);
    });
});

// Fallback route
Route::fallback(function () {
    return view('errors.404');
});
