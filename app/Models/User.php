<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;
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
        'requirement_transmittal_form_id',
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
     * Get all the RequirementTransmittalForms for the user.
     * This establishes a one-to-many relationship.
     */
    public function requirementTransmittalForms(): HasOne
    {
        return $this->hasOne(RequirementTransmittalForm::class);
    }

    /**
    /**
     * Get all the Submission for the user.
     * This establishes a one-to-many relationship.
     */
    public function submission(): HasMany
    {
        return $this->hasMany(Submission::class);
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
  public function  setPrimaryRole(string|int $role)
  {
    $roleId = is_numeric($role) ? $role : Role::findByName($role)->id;

    if ($this->hasRole($roleId)) {
      $this->primary_role_id = $roleId;
      $this->save();
    }
    return $this;
  }
}
