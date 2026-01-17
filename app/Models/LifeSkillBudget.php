<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifeSkillBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'monthly_allowance',
        'savings_goal',
        'current_savings',
        'total_expenses',
        'last_updated',
    ];

    protected $casts = [
        'monthly_allowance' => 'decimal:2',
        'savings_goal' => 'decimal:2',
        'current_savings' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'last_updated' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->savings_goal <= 0) {
            return 0;
        }
        
        $progress = ($this->current_savings / $this->savings_goal) * 100;
        return min(100, round($progress, 1));
    }

    public function getRemainingToGoalAttribute()
    {
        return max(0, $this->savings_goal - $this->current_savings);
    }

    public function getMonthlySavingsRateAttribute()
    {
        if ($this->monthly_allowance <= 0) {
            return 0;
        }
        
        $savingsRate = ($this->current_savings / $this->monthly_allowance) * 100;
        return round($savingsRate, 1);
    }
}
