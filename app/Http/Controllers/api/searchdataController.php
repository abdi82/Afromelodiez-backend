<?php

namespace App\Http\Controllers\api;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Album;
use App\Models\songsRecord;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Language;
use App\Models\AdminPanel;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;
use Illuminate\Routing\UrlGenerator;



class searchdataController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.verify');
    }

    public function contentsearch($search)
    {  
       $data=array();

       $song = Song::where('name','LIKE', $search.'%')
                  ->get();

            foreach ($song as $value) {
                        $artist=Artist::select('*')->where('id', $value->artist_id)
                                             ->get();

                        $language=Language::select('*')->where('id', $value->language_id)
                                             ->get();
                        $category=Category::select('*')->where('id', $value->category_id)
                         ->get();

                        $value->artist_id=$artist;
                        $value->language_id=$language;
                        $value->category_id=$category;

                        $liked=explode(',',$value->liked);

                       $value->liked=$liked;
                  
                  
                }

      $artist=Artist::where('name','LIKE', $search.'%')
                  ->get(); 

      $album=Album::where('name','LIKE', $search.'%')
                  ->get();    
                             
                return response()->json([
                    'success' => true,
                    'Song' => $song,
                    'Artist' =>$artist,
                    'Album' =>$album
                ], Response::HTTP_OK);
    }



}