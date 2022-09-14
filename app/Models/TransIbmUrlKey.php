<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransIbmUrlKey extends Model
{
    public function trnslKey()
    {
        return $this->belongsTo(TrnslKey::class);
    }
}
