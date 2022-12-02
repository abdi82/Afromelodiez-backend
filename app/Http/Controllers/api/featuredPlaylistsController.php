<?php

namespace App\Http\Controllers\api;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\songsRecord;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Artist;
use App\Models\Category;
use App\Models\featuredPlaylists;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use DB;
use Artisan;
use Illuminate\Routing\UrlGenerator;
use Carbon\Carbon;



class featuredPlaylistsController extends Controller
{ 
      public function __construct()
    {
        $this->middleware('jwt.verify',['except' => ['index']]);
    }
    
	public function index()
    {

     $data=featuredPlaylists::get(); 
     
    $i=0;
	  foreach($data as $value_featured)
	  { 
	    
		$song=array();
		$song=explode(',',$value_featured->song);
        
        foreach($song as $song_id)
        {
           $all_songs[]=$song_id;
        }
	  

       foreach ($all_songs as $value_id) {


                $song_data = DB::table('songs')
                ->where('id', '=', $value_id)
                ->first();
               if(!empty($song_data))
               { 

                $artist=Artist::select('*')->where('id', $song_data->artist_id)
                                     ->first();

                $language=Language::select('*')->where('id', $song_data->language_id)
                                     ->first();
                 $category=Category::select('*')->where('id', $song_data->category_id)
                 ->first();

                    $song_data->artist_id=$artist;
                    $song_data->language_id=$language;
                   $song_data->category_id=$category;

                $liked=explode(',',$song_data->liked);

                $song_data->liked=$liked;

                $all_featuring=array();
                if($song_data->featuring != "")
                { 
                  $data_featuring= explode(',',$song_data->featuring);
                  foreach($data_featuring as $value_fea){

                    $artist=Artist::select('*')->where('id', $value_fea)
                                     ->first();
                     $all_featuring[]=$artist;               
                  }
                }
               $song_data->featuring = $all_featuring;

              $all_song_data[] = $song_data;
             }
        }
         $value_featured->song= $all_song_data;
         $i++;
    }
      return response()->json([
                'success' => true,
                'Data'   =>$data
            ], Response::HTTP_OK);
        
        
    }
}
