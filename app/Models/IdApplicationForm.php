<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class IdApplicationForm extends BaseFormModel
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'job_title',
        'emergency_contact_name',
        'emergency_contact_relation',
        'emergency_contact_address',
        'emergency_contact_number',
        'is_info_complete',
        'has_id_picture',
        'is_for_filing',
        'is_encoded',
        'is_card_done',
        'is_delivered',
        'completed_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_info_complete' => 'boolean',
        'has_id_picture' => 'boolean',
        'is_for_filing' => 'boolean',
        'is_encoded' => 'boolean',
        'is_card_done' => 'boolean',
        'is_delivered' => 'boolean',
    ];

    public function submission(): MorphOne
    {
        // This tells Laravel that this form is "submittable"
        return $this->morphOne(Submission::class, 'submittable');
    }

    /**
     * Get the employee (user) that this form is for.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Get the user who submitted the form.
     */
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the HR user who completed the processing of this form
     */
    public function completeddBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
