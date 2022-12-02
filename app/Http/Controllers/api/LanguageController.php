<?php

namespace App\Http\Controllers\api;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;


class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify');
    }
    /*
    |--------------------------------------------------------------------------
    | Betting Form Page In admin Panel
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $language = Language::get();
        return response()->json([
            'success' => true,
            'data' => $language
        ], Response::HTTP_OK);
    }
    public function user_to_language(Request $request)
    {

       DB::table('user_to_language')->where('user_id',$request->user_id)->delete();
        DB::table('users')->where('id',$request->user_id)->update(['lang_status'=>'1']);
            foreach($request->language as $lang){

              DB::table('user_to_language')->insert(['user_id'=>$request->user_id,'language_id'=>$lang]);

            }


        return response()->json([
            'success' => true,
            'message' => 'New Language Saved'
        ], Response::HTTP_OK);
    }
   
 public function get_user_language(){

        $uid=auth()->user()->id;
        $userlanguage=Language::select('languages.name','languages.id')
            ->join('user_to_language','user_to_language.language_id','=','languages.id')
            ->where('user_to_language.user_id',$uid)->get();
     return response()->json([
         'success' => true,
         'data' => $userlanguage
     ], Response::HTTP_OK);
          }

}
