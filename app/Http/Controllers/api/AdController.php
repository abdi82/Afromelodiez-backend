<?php

namespace App\Http\Controllers\api;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\advertisements;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\userPlaylist;
use App\Models\continent;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;
use App\Models\AdRecord;


class AdController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Betting Form Page In admin Panel
    |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->middleware('jwt.verify',['except' => ['index']]);
    }
    public function index()
    {

        $ad = advertisements::get();
            $all_location=array();
       
        // foreach($ad as $value)
        // {        
        //         $type=$value->location_type;  
        //         $data='';
        //         if($type == 'Continent')
        //         {
        //               $data=continent::where('ad_id',$value->id)->get();
        //               foreach($data as $value_data)
        //               {
        //                $all_location[]=$value_data->name;
        //               }
                       

        //         }
        //         $value['location']=$all_location;

        // }

        return response()->json([
            'success' => true,
            'data' => $ad
        ], Response::HTTP_OK);
    }

    public function location_ad_display(Request $request)
    {      
            //$geoAddress= "30.469301, 70.969324";
            $deal_lat=30.469301;
            $deal_long=70.969324;
            $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$deal_lat.','.$deal_long.'&sensor=false&key=AIzaSyCvNkmkAeRrdYNif8-WAxZisfdY4A_HNc4');

            $output= json_decode($geocode);

    //         $url = 'http://maps.google.com/maps/api/geocode/json?address=' . urlencode($geoAddress) .'&sensor=false&key=AIzaSyCvNkmkAeRrdYNif8-WAxZisfdY4A_HNc4'; 
    // $get     = file_get_contents($url);
    // $geoData = json_decode($get);

            // for($j=0;$j<count($output->results[0]->address_components);$j++){

            //     $cn=array($output->results[0]->address_components[$j]->types[0]);

            //     if(in_array("country", $cn)){
            //         $country= $output->results[0]->address_components[$j]->long_name;
            //     }
            // }

            // echo $country;
       // $ad = advertisements::get();
        return response()->json([
            'success' => true,
            'data' => $output
        ], Response::HTTP_OK);
    }

    public function users_visit_store(Request $request)
    { 
        if($request->user_id != '' && $request->ad_id != '' )
        {
            $user= AdRecord::where('user_id',$request->user_id)
                           ->where('ad_id',$request->ad_id)
                           ->first();
                           $users_visit =AdRecord::count();
            //$users_visit =AdRecord::whereBetween('created_at',[Carbon::now()->subDays(30),Carbon::now()])->groupBy('user_id')->get()->count();
             
           if(!empty($user))
           {
           $played= $user->played;
            $data['played']= $played+1;
           $update = AdRecord::where('user_id',$request->user_id)->update($data);
           $message= "Data is updated successfully";
           }  
           else
           { 

                $data['user_id']= $request->user_id;
                $data['ad_id']= $request->ad_id; 
                $data['played']=1;
                $record=AdRecord::create($data);
                $message= "Data is saved successfully";
            }
        }
        else
        {
            $message="Please enter valid data";
        }  

                return response()->json([
            'success' => true,
            'message' => $message
        ], Response::HTTP_OK);      
    }
  

}