<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
  protected $table = "jobs";
  protected $primaryKey = "id";
  protected $guarded = ['id']; //← the field name inside the array is not mass-assignable

  /**
   * Get the subscriber record associated with the domain.
   */

}
