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
    
    
    public function buildDetails($tripDetails)
    {
        $userDetail= array();
        foreach($tripDetails as $trip=>$values){
             $vehicleDetails = $this->vehicles($values['vehicle_id'])->toArray();
             $userDetails = $this->users($values['user_id'])->toArray();
             $trackingDetails =$this->trackings($values['id'])->toArray();

             $tripDetails[$trip]['user'] = $userDetails[0];
             $tripDetails[$trip]['vehicle'] = $vehicleDetails[0];
             $tripDetails[$trip]['trackings'] = $trackingDetails[0];
            
             $latlong= $trackingDetails[0]->lat.','.$trackingDetails[0]->lng;
             
             $geolocation = $this->geoLocation($latlong);
             $tripDetails[$trip]['location'] = $geolocation;
             
        } 
        
        
        
        return $tripDetails;
    }
    
    private function vehicles($id)
    {
        $users = DB::table('vehicles')->where('id', $id)->select('name','registration_number')->get();
        return $users;
    }
    
    private function users($id)
    {
        $users = DB::table('users')->where('id', $id)->select('first_name','last_name','email','mobile','ace_number')->get();
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
    
     private function tracks()
    {
        $buddies = DB::table('users')
                    ->join('trackings', 'trackings.user_id', '=', 'users.id')
                    ->where('users.id', $this->id)
                    ->get();
        return $buddies;
    }
    
    private function geoLocation($latlong)
    {
        $key = 'AIzaSyDjOJnuUxi5x9t1UChVGrzKnDC3DJut0IQ';
        $geoLocUrl = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='
                      .$latlong
                      .'&key='.$key;
       
        
        $geoLocation =json_decode(file_get_contents($geoLocUrl), true);
        
        //Find Location name from json
        foreach ($geoLocation['results'][0]['address_components'] as $location) {
            if (in_array('sublocality_level_1', $location['types'])) {     
                $locality_name = $location['long_name'];  
            }
            if (empty($locality_name) && in_array('locality', $location['types'])) {
                $locality_name = $location['long_name'];
            }
        }

        return  $locality_name;
    }
    
        
}            
