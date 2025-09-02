<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $name
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string|null $suffix
 * @property string $gender
 * @property string $employee_number
 * @property string|null $street
 * @property string|null $city
 * @property string|null $province
 * @property string|null $zip_code
 * @property string|null $phone_number
 * @property string|null $telegram_chat_id
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string $status
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $detachment_id
 * @property int|null $primary_role_id
 * @property-read \App\Models\Detachment|null $detachment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Role|null $primaryRole
 * @property-read string $profile_photo_url
 * @property-read \App\Models\RequirementTransmittalForm|null $requirementTransmittalForm
 * @property-read \App\Models\Submission|null $requirementTransmittalFormSubmission
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Submission> $submittedBy
 * @property-read int|null $submitted_by_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Suspension> $suspensions
 * @property-read int|null $suspensions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDetachmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmployeeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePrimaryRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSuffix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTelegramChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'gender',
        'street',
        'city',
        'province',
        'zip_code',
        'phone_number',
        'employee_number',
        'email',
        'email_verified_at',
        'password',
        'detachment_id',
        'primary_role_id',
        'status',
        'sss_number',
        'philhealth_number',
        'pagibig_number',
        'birthdate',
        'telegram_chat_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the detachment that the user belongs to.
     */
    public function detachment(): BelongsTo
    {
        return $this->belongsTo(Detachment::class);
    }

    /**
     * Get all the submissions created by this user.
     */
    public function submittedBy(): HasMany
    {
        // Explicitly define the foreign key to match the 'submissions' table schema.
        return $this->hasMany(Submission::class, 'submitted_by');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date:Y-m-d',
        ];
    }

    /**
     * Get the user's primary role.
     */
    public function primaryRole()
    {
        return $this->belongsTo(Role::class, 'primary_role_id');
    }

    /**
     * Set the user's primary role.
     * Ensures the user actually has the role before setting it as primary.
     */
    public function setPrimaryRole(string|int $role): static
    {
        $roleId = is_numeric($role) ? $role : Role::findByName($role)->id;

        if ($this->hasRole($roleId)) {
            $this->primary_role_id = $roleId;
            $this->save();
        }

        return $this;
    }

    /**
     * Get all the suspensions for the User.
     */
    public function suspensions(): HasMany
    {
        return $this->hasMany(Suspension::class);
    }

    /**
     * Check if the user is an assigned officer in any detachment.
     */
    public function isAssignedOfficer(): bool
    {
        // This will return true if the user's ID exists in the 'assigned officer'
        // column of the 'detachments' table, and false otherwise.
        return Detachment::where('assigned_officer', $this->id)->exists();
    }

    /**
     * Checks if the user's profile has all the required information.
     */
    public function isProfileComplete(): bool
    {
        // Define all fields that are required for a profile to be considered complete.
        $requiredFields = [
            'first_name',
            'last_name',
            'phone_number',
            'street',
            'city',
            'province',
            'zip_code',
            'sss_number',
            'philhealth_number',
            'pagibig_number',
            'birthdate',
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->{$field})) {
                return false; // If any required field is empty, the profile is incomplete.
            }
        }

        return true;
    }

    /**
     * Get the requirement transmittal form associated with the user.
     */
    public function requirementTransmittalForm(): HasOne
    {
        return $this->hasOne(RequirementTransmittalForm::class, 'employee_id');
    }

    /**
     * Get the first month performance evaluation form associated with the user.
     */
    public function firstMonthPerformanceEvaluationForm(): HasOne
    {
        return $this->hasOne(FirstMonthPerformanceEvaluationForm::class, 'employee_id');
    }

    /**
     * Get the third month performance evaluation form associated with the user.
     */
    public function thirdMonthPerformanceEvaluationForm(): HasOne
    {
        return $this->hasOne(ThirdMonthPerformanceEvaluationForm::class, 'employee_id');
    }

    /**
     * Get the sixth month performance evaluation form associated with the user.
     */
    public function sixthMonthPerformanceEvaluationForm(): HasOne
    {
        return $this->hasOne(SixthMonthPerformanceEvaluationForm::class, 'employee_id');
    }

    /**
     * Get the ID application form associated with the user.
     */
    public function idApplicationForm(): HasMany
    {
        return $this->hasMany(IdApplicationForm::class, 'employee_id');
    }
}
