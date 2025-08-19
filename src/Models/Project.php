<?php

namespace Isotope\CRM\Models;

class Project extends BaseModel
{

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }
}
