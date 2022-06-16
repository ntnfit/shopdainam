<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class getcostshipvn extends Controller
{
    public function getCost()
    {
        $location = request('state');
        $rate=sc_currency_rate();
   
        $states = DB::table('province')->where('_name',$location)->first();
       
       if( $states==null)
       {
         $costship =0;
       }
       else
       {
        $location=$states->_code;
        if ($location=='SG')
        {
            $cost = DB::table('CostToVn')->where('name', 'HCM')->first();
           
                $costship=$cost->value;
           
        }
       else
           {
               $cost = DB::table('CostToVn')->where('name', 'TL')->first();
              
                
                       $costship=$cost->value;
                  
               
           }
       }
        

         
        return sc_currency_value($costship,sc_currency_rate());
    }
}
