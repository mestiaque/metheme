<?php

namespace Isotope\CRM\Models;

use Isotope\CRM\Models\Proposal;
use Isotope\CRM\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class ProposalItem extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'quantity',
        'unit_type',
        'rate',
        'total',
        'sort',
        'proposal_id',
        'item_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'rate' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relationships
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}
