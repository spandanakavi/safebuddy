<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthAccessToken extends Model
{
    public $incrementing = false;
    
    public function owner()
    {
        return $this->belongsTo('App\User');
    }
}
