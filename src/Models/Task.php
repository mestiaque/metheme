<?php

namespace Isotope\CRM\Models;

use App\Models\User;

class Task extends BaseModel
{
    protected $guarded = [];

    // public function project()
    // {
    //     return $this->belongsTo(Project::class);
    // }

    // public function assignedTo()
    // {
    //     return $this->belongsTo(User::class, 'assigned_to');
    // }

    // public function collaborators()
    // {
    //     return $this->belongsToMany(User::class, 'task_collaborators');
    // }
}
