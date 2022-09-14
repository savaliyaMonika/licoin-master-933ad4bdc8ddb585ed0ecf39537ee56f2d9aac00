<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class TrnslRequests extends Model
{
    //
    use SoftDeletes;
    protected $primaryKey = "id";
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        return config('siteconfig.URL.TRANSLATION_FILE');
    }

    public function getUpdatedAtAttribute()
    {
      return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['updated_at'])->format('d-m-Y H:i:s');
    }
}
