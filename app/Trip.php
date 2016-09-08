<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use App\Tracking;


class Trip extends Model
{
    //

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'vehicle_id', 'surce', 'destination', 'start_time',
        'end_time'
    ];


    public function buildDetails($tripDetails, $isAdmin, $projectIds)
    {
        $userDetail = array();
        foreach ($tripDetails as $trip => $values) {
            $vehicleDetails = $this->vehicles($values['vehicle_id'])->toArray();
            $trackingDetails = $this->trackings($values['id'])->toArray();

            if ($isAdmin) {
                $userDetails = $this->users($values['user_id'])->toArray();
            } else {
                $userDetails = $this->pmUsers($values['user_id'], $projectIds)->toArray();
            }

            if (!empty($userDetails)) {
                $tripDetails[$trip]['user'] = $userDetails[0];
                $tripDetails[$trip]['vehicle'] = isset($vehicleDetails[0])
                                                    ? $vehicleDetails[0]
                                                    : array();

                if (isset($trackingDetails[0])) {
                    $tripDetails[$trip]['trackings'] = $trackingDetails[0];
                    $latlong = $trackingDetails[0]->lat . ',' . $trackingDetails[0]->lng;
                    $geolocation = $this->geoLocation($latlong);
                    $tripDetails[$trip]['location'] = $geolocation;
                }
            } else {
                unset($tripDetails[$trip]);
            }
        }

        return $tripDetails;
    }

    private function vehicles($id)
    {
        $users = DB::table('vehicles')
                 ->where('id', $id)
                 ->select('name','registration_number')
                 ->get();

        return $users;
    }

    private function users($id)
    {
        $users = DB::table('users')
                ->select('first_name', 'project_id', 'last_name', 'email', 'mobile', 'ace_number')
                ->where('id', $id)
                ->get();

        return $users;
    }

    private function pmUsers($id, $projectIds)
    {
        $users = DB::table('users')
                ->select('first_name', 'project_id', 'last_name', 'email', 'mobile', 'ace_number')
                ->where('id', $id)
                ->whereIn('project_id', $projectIds)
                ->get();

        return $users;
    }

    private function trackings($id)
    {
        $trackings = DB::table('trackings')->where('trip_id', $id)
                     ->select('lat','lng','is_sos','kmph')
                     ->orderBy('id', 'desc')
                     ->get();
        return $trackings;
    }

    private function geoLocation($latlong)
    {
        $key = 'AIzaSyDjOJnuUxi5x9t1UChVGrzKnDC3DJut0IQ';
        $geoLocUrl = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='
                . $latlong
                . '&key=' . $key;

        $geoLocation = json_decode(file_get_contents($geoLocUrl), true);

        //Find Location name from json
        foreach ($geoLocation['results'][0]['address_components'] as $location) {
            if (in_array('sublocality_level_1', $location['types'])) {
                $locality_name = $location['long_name'];
            }
            if (empty($locality_name) && in_array('locality', $location['types'])) {
                $locality_name = $location['long_name'];
            }
        }

        return $locality_name;
    }

    public function projects($id)
    {
        $projects = DB::table('projects')
                ->where('projects.pm_id', $id)
                ->get()
                ->toArray();

        return $projects;
    }

}
