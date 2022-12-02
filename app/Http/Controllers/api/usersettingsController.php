<?php

namespace App\Http\Controllers\api;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\userSettings;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;


class usersettingsController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Betting Form Page In admin Panel
    |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->middleware('jwt.verify');
    }
    public function index($id)
    {
        $userSettings = userSettings::where('userID',$id)->get();

        if($userSettings == "[]")
        {
            $userSettings="Unable settings";
        }

        return response()->json([
            'success' => true,
            'data' => $userSettings
        ], Response::HTTP_OK);
    }
    
    public function store(Request $request)
    {
        $data=$request->all();

        $find=userSettings::select('id')->where('userID', $request->userID)->first();
        
        if(empty($find)) 
        {  
            $userSettings = userSettings::create($data); 
        }
        else
        { 
           $delete= DB::table('user_settings')->where('userID', $request->userID)->delete(); 
            $userSettings = userSettings::create($data); 
        }
         
        return response()->json([
            'success' => true,
            'data' => $userSettings
        ], Response::HTTP_OK);

    }
}