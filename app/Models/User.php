<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'parent_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is parent
     */
    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Get student record if user is a student
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get student preferences
     */
    public function preferences()
    {
        return $this->hasOne(StudentPreference::class);
    }

    /**
     * Focus sessions for the user
     */
    public function focusSessions()
    {
        return $this->hasMany(FocusSession::class);
    }

    public function breathingSessions()
    {
        return $this->hasMany(BreathingSession::class);
    }

    public function cognitiveSessions()
    {
        return $this->hasMany(CognitiveSession::class);
    }

    public function cognitiveScores()
    {
        return $this->hasMany(CognitiveScore::class);
    }

    public function creativeStories()
    {
        return $this->hasMany(CreativeStory::class);
    }

    public function lifeSkillSchedules()
    {
        return $this->hasMany(LifeSkillSchedule::class);
    }

    public function lifeSkillRoutines()
    {
        return $this->hasMany(LifeSkillRoutine::class);
    }

    public function lifeSkillBudget()
    {
        return $this->hasOne(LifeSkillBudget::class);
    }

    public function lifeSkillCommunications()
    {
        return $this->hasMany(LifeSkillCommunication::class);
    }

    /**
     * Get AI interactions for this user
     */
    public function aiInteractions()
    {
        return $this->hasMany(AiInteraction::class);
    }

    /**
     * Get courses taught by this user (if teacher)
     */
    public function taughtCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    /**
     * Get projects for this user
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get parent user (if this user is a student)
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get children users (if this user is a parent)
     */
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }
}
