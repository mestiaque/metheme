<?php

namespace Isotope\CRM\Models;

use Isotope\CRM\Models\BaseModel;
use Isotope\CRM\Models\ProposalItem;
use Isotope\CRM\Models\ProposalTemplate;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Proposal extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'proposal_date',
        'valid_until',
        'note',
        'last_email_sent_date',
        'status',
        'tax_id',
        'tax_id2',
        'discount_type',
        'discount_amount',
        'discount_amount_type',
        'content',
        'public_key',
        'accepted_by',
        'meta_data',
        'company_id',
    ];

    protected $casts = [
        'proposal_date' => 'date',
        'valid_until' => 'date',
        'last_email_sent_date' => 'date',
        'meta_data' => 'array',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(ProposalItem::class);
    }

    public function template()
    {
        return $this->belongsTo(ProposalTemplate::class, 'template_id');
    }
}
