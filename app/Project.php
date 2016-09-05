<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'mobile', 'buddy_of_user_id'
    ];
}
