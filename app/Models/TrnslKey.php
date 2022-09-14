<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrnslKey extends Model
{
    //
    use SoftDeletes;

    protected $table = "trnsl_keys";

    protected $primaryKey = "id";
    protected $guarded = [ 'id'];

    protected $dates = ['deleted_at'];

    public function transIbmUrlKey()
    {
        return $this->hasOne(TransIbmUrlKey::class, 'trans_key_id');
    }

}
