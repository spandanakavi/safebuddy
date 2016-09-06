<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use App\Tracking;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'ace_number', 'email', 'password',
        'project_id', 'is_parent', 'child_email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function accessToken()
    {
        return $this->hasOne('App\OauthAccessToken');
    }

    public function contacts()
    {
        // Parents
        $parents = $this->parents();

        // Project manager
        $pm = $this->projectManager();

        // Buddies
        $buddies = $this->buddies();

        // Merge contacts
        $usersInSystem = $parents->merge($pm);
        $result = $this->buildContacts($usersInSystem, $buddies);

        return $result;
    }

    private function parents()
    {
        $parents = DB::table('users')->where('is_parent', 1)->where('child_email', $this->email)->get();
        return $parents;
    }

    private function projectManager()
    {
        $pm = DB::table('users')
                ->join('projects', 'users.project_id', '=', 'projects.id')
                ->join('users AS pm_user', 'projects.pm_id', '=', 'pm_user.id')
                ->where('users.id', $this->id)
                ->get();
        return $pm;
    }

    private function buddies()
    {
        $buddies = DB::table('users')
                    ->join('buddies', 'buddies.buddy_of_user_id', '=', 'users.id')
                    ->where('users.id', $this->id)
                    ->get();
        return $buddies;
    }

    // Build the list of contacts from system users (parents and pm)
    // and buddies.
    private function buildContacts($usersInSystem, $buddies)
    {
        $result = array();

        foreach ($usersInSystem as $userInSystem) {
            $contactUser = array();
            $contactUser['first_name'] = $userInSystem->first_name;
            $contactUser['last_name'] = $userInSystem->last_name;
            $contactUser['email'] = $userInSystem->email;
            $contactUser['mobile'] = $userInSystem->mobile;
            $contactUser['is_parent'] = $userInSystem->is_parent ? true : false;
            $contactUser['is_pm'] = !$contactUser['is_parent'];
            $result[] = $contactUser;
        }

        foreach ($buddies as $buddy) {
            $contactUser = array();
            $contactUser['first_name'] = $buddy->first_name;
            $contactUser['last_name'] = $buddy->last_name;
            $contactUser['email'] = $buddy->email;
            $contactUser['mobile'] = $buddy->mobile;
            $contactUser['is_parent'] = false;
            $contactUser['is_pm'] = false;
            $result[] = $contactUser;
        }

        return $result;
    }
}
