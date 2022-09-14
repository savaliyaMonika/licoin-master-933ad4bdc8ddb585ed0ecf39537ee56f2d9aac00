<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainSslDetails extends Model
{
    protected $table = "domain_ssl_details";
    protected $primaryKey = "id";
    protected $guarded = ['id']; //← the field name inside the array is not mass-assignable

    /**
     * Get the subscriber record associated with the domain.
     */
    public function subscriber()
    {
        return $this->hasMany('App\Models\SubscriberSslExpire', 'ssl_id', 'id');
    }

    public function getAdditionalDomainsAttribute($value)
    {
        return explode(",", $value);
    }

    public function setAdditionalDomainsAttribute($value)
    {
        $this->attributes['additional_domains'] = implode(",", $value);
    }
}
