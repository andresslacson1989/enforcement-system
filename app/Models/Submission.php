<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Submission extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'user_id',
    'submittable_id',
    'submittable_type',
  ];

  /**
   * Get the owning submittable model.
   */
  public function submittable(): MorphTo
  {
    return $this->morphTo();
  }

  /**
   * Get the user who submitted the form.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }


//  /**
//   * Get the user associated with the submission.
//   * This acts as a dynamic accessor.
//   */
//  public function getUserAttribute()
//  {
//    return $this->submittable?->user;
//  }
}
