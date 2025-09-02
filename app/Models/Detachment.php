<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property string|null $category
 * @property int|null $assigned_officer
 * @property string $status
 * @property int|null $approved_by
 * @property string|null $approved_at
 * @property string $street
 * @property string $city
 * @property string $province
 * @property string $zip_code
 * @property string $phone_number
 * @property int|null $hours_per_shift
 * @property int|null $max_hrs_duty
 * @property int|null $max_ot
 * @property string|null $hr_rate
 * @property string|null $ot_rate
 * @property string|null $nd_rate
 * @property string|null $rdd_rate
 * @property string|null $rdd_ot_rate
 * @property string|null $hol_rate
 * @property string|null $hol_ot_rate
 * @property string|null $sh_rate
 * @property string|null $sh_ot_rate
 * @property string|null $rd_hol_rate
 * @property string|null $rd_hol_ot_rate
 * @property string|null $rd_sh_rate
 * @property string|null $rd_sh_ot_rate
 * @property string|null $cash_bond
 * @property string|null $sil
 * @property string|null $ecola
 * @property string|null $retirement_pay
 * @property string|null $thirteenth_month_pay
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $assignedOfficer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FirstMonthPerformanceEvaluationForm> $firstMonthPerformanceEvaluationForms
 * @property-read int|null $first_month_performance_evaluation_forms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RequirementTransmittalForm> $requirementTransmittalForm
 * @property-read int|null $requirement_transmittal_form_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\DetachmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereAssignedOfficer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereCashBond($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereEcola($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereHolOtRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereHolRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereHoursPerShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereHrRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereMaxHrsDuty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereMaxOt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereNdRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereOtRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereRdHolOtRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereRdHolRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereRdShOtRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereRdShRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereRddOtRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereRddRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereRetirementPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereShOtRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereShRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereSil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereThirteenthMonthPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Detachment whereZipCode($value)
 * @mixin \Eloquent
 */
class Detachment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'assigned_officer',
        'street',
        'city',
        'province',
        'zip_code',
        'hours_per_shift',
        'max_hrs_duty',
        'max_ot',
        'hr_rate',
        'ot_rate',
        'nd_rate',
        'rdd_rate',
        'rdd_ot_rate',
        'hol_rate',
        'hol_ot_rate',
        'sh_rate',
        'sh_ot_rate',
        'rd_hol_rate',
        'rd_hol_ot_rate',
        'rd_sh_rate',
        'rd_sh_ot_rate',
        'cash_bond',
        'sil',
        'ecola',
        'retirement_pay',
        'thirteenth_month_pay',
        'phone_number',
    ];

    /**
     * Get the users for the detachment.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'detachment_id', 'id');
    }

    /**
     * Get all the performance evaluation forms for the Detachment.
     */
    public function firstMonthPerformanceEvaluationForms(): HasMany
    {
        // This defines that a Detachment has many forms.
        // We specify 'deployment' as the foreign key on the other table.
        return $this->hasMany(FirstMonthPerformanceEvaluationForm::class, 'detachment_id', 'id');
    }

    /**
     * Get all the requirement transmittal forms for the Detachment.
     */
    public function requirementTransmittalForm(): HasMany
    {
        // This defines that a Detachment has many forms.
        // We specify 'deployment' as the foreign key on the other table.
        return $this->hasMany(RequirementTransmittalForm::class, 'detachment_id', 'id');
    }

    public function assignedOfficer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'assigned_officer');
    }
}
