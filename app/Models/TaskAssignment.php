<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    protected $fillable = [
        'task_id',
        'agent_id',
        'assigned_by',
        'assignment_type',
        'priority_score',
        'match_score',
        'status'
    ];
}
