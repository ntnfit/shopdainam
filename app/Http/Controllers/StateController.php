<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use WisdomDiala\Countrypkg\Models\Country;
use WisdomDiala\Countrypkg\Models\State;

class StateController extends Controller
{
     public function getAllStates()
    {
    	$short_name = request('country');
        $country= Country::where('short_name',$short_name)->first();
       
        $states = State::where('country_id',$country['id'])->get();
       $option ="<option>select state</option>";
      
        foreach($states as $state)
        {
            $option .='<option value="'.$state->name.'">'.$state->name.'</option>';
        }
        return $option;
    }
}
