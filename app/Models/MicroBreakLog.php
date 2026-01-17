<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MicroBreakLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','activity','duration_seconds','performed_at'];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}








