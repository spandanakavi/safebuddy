@extends('layouts.app')

@section('content')

<div class="container" style="width:80%;">
<!-- <div class="submit"><a href ="/dashboard"><input type="button" value="Map View" name="Map View"/></a></div> -->
    <div class="row">
        <div class="col-md-12">            
            <table>
                <thead>               
                    <tr>
                        <td>id</td>
                        <td>Name</td>
                        <td>Ace No</td>
                        <td>Vehicle Name</td>
                        <td>Current Location </td>
                        <td>Source </td>
                        <td>Destination</td>                        
                        <td> Sos </td>
                        <td>Speed (km/hr) </td>
                        <td>Start Time</td>
                        <td>End Time</td>               
                    </tr>
                </thead> 
                <tbody>
                    
                    <?php $a = 1; ?> 
                    @foreach ($tripDetails as $trip=>$values)
                    @if ( !empty($values['trackings']) && $values['trackings']->is_sos === 1)
                    <tr class="sos">
                    @else
                    <tr>
                    @endif                    
                        <td><a href="/view" target="_blank"><?php echo $a++; ?> </a> </td>
                        
                        
                        @if (! empty($values['user']))
                        <td> {{ $values['user']->first_name }} 
                            {{ $values['user']->last_name }}
                        </td>                        
                        <td> {{ $values['user']->ace_number }} </td>
                        @else 
                        <td> - </td>
                        <td> - </td>                        
                        @endif
                        @if ( !empty($values['trackings']))
                            <td> {{ $values['vehicle']->name }} </td>
                            <td>{{  $values['location'] }}</td>                            
                        @else
                             <td> - </td>
                             <td> - </td>
                        @endif

                        <td>{{$values['source']}}</td>
                        <td>{{$values['destination']}}</td>
                        
                        @if ( !empty($values['trackings']))
                         <td>{{$values['trackings']->is_sos}}</td>
                         <td>{{$values['trackings']->kmph}}</td>
                        @else
                          <td> - </td>
                          <td> - </td>
                        @endif
                        <td>{{  $values['start_time'] }}</td>
                        <td>{{  $values['end_time'] }}</td>
                    </tr>    
                    @endforeach 
                </tbody>
            </table>
        </div>
    </div>
    @endsection
