<?php

namespace Isotope\CRM\Models;

use Isotope\CRM\Models\Proposal;
use Isotope\CRM\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class ProposalTemplate extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'template',
    ];

    // Relationships
    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }
}
