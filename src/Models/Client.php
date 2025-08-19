<?php

namespace Isotope\CRM\Models;

class Client extends BaseModel
{
    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }
}
