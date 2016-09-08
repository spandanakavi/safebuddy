@extends('layouts.app')

@section('content')

<div class="container">
<div class="submit"><a href ="/dashboard"><input type="button" value="Map View" name="Map View"/></a></div>
    <div class="row">
        <div class="col-md-12">            
            <table>
                <thead>               
                    <tr>
                        <td>id</td>
                        <td>Name</td>
                        <td>Ace No</td>
                        <!--<td>email</td>
                        <td>mobile Number</td> -->
                        <td>Vehicle Name</td>
                        <!--<td>Vehicle Registration Number </td>-->
                        <td>Source </td>
                        <td>Destination</td>
                        <td>Current Location     </td>
                        <td> Sos </td>
                        <td>Speed (km/hr) </td>
                        <td>Start Time</td>
                        <td>End Time</td>               
                    </tr>
                </thead> 
                <tbody>

                    <?php $a = 1; ?>
                    @foreach ($tripDetails as $trip=>$values)
                    @if ( $values['trackings']->is_sos === 1)
                    <tr class="sos">
                    @else
                    <tr>
                    @endif
                    
                        <td><a href="/view" target="_blank"><?php echo $a++; ?> </a> </td>
                        <td> {{ $values['user']->first_name }} 
                            {{ $values['user']->last_name }}
                        </td>
                        <td> {{ $values['user']->ace_number }} </td>
                        <!--<td> {{ $values['user']->email }} </td>
                        <td> {{ $values['user']->mobile }} </td> -->

                        <td> {{ $values['vehicle']->name }} </td>
                        <!--<td> {{ $values['vehicle']->registration_number }} </td>-->

                        <td>{{$values['source']}}</td>
                        <td>{{$values['destination']}}</td>

                        <td>{{  $values['location'] }}</td>
                        @if ( $values['trackings']->is_sos === 1)
                        <td style="color:red;">!</td>
                        @else
                        <td></td>
                        @endif
                        
                        <td>{{  $values['trackings']->kmph }}</td>

                        <td>{{  $values['start_time'] }}</td>
                        <td>{{  $values['end_time'] }}</td>
                    </tr>    
                    @endforeach 
                </tbody>
            </table>

        </div>
    
    </div>
    @endsection
