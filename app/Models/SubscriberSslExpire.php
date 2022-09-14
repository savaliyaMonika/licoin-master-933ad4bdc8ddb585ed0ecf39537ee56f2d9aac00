<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriberSslExpire extends Model
{
    protected $table = "subscriber_ssl_expire";
    protected $primaryKey = "id";
    protected $guarded = ['id']; //← the field name inside the array is not mass-assignable

}
