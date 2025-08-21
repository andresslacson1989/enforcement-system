<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Detachment extends Model
{
  use HasFactory;
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
    ];

  /**
   * Get the users for the detachment.
   */
  public function users(): HasMany
  {
    return $this->hasMany(User::class, 'detachment_id', 'id');
  }

}
