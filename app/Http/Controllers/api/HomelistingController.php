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
use App\Models\Language;
use App\Models\Banner;
use App\Models\featuredPlaylists;
use App\Models\featuredArtists;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use DB;
use Artisan;
use Illuminate\Routing\UrlGenerator;
use Carbon\Carbon;




class HomelistingController extends Controller
{
   public function home_listing($userid)
    { 

            $songs= Song::leftJoin('artists','songs.artist_id','=','artists.id')
            ->select('songs.*')
            ->where('artists.isVerified','=','1')
            ->where('song','!=','')
            ->inRandomOrder()
            ->limit('10')
            ->get();

                foreach ($songs as $value) {


                $artist=Artist::select('*')->where('id', $value->artist_id)
                                     ->first();

                $language=Language::select('*')->where('id', $value->language_id)
                                     ->first();
                 $category=Category::select('*')->where('id', $value->category_id)
                 ->first();

                    $value->artist_id=$artist;
                    $value->language_id=$language;
                   $value->category_id=$category;

                $liked=explode(',',$value->liked);

                $value->liked=$liked;

                $all_featuring=array();
                        if($value->featuring != "")
                        { 
                          $data_featuring= explode(',',$value->featuring);
                          foreach($data_featuring as $value_fea){

                            $artist=Artist::select('*')->where('id', $value_fea)
                                             ->first();
                             $all_featuring[]=$artist;               
                          }
                        }
                       $value->featuring = $all_featuring;


               }


                 
            $all_artist = DB::table('artists')
                              ->limit('10')
                              ->where('isVerified','=','1')
                              ->get(); 

            // $most = DB::table('songs')
            //         ->where('song','!=','')
            //         ->orderBy('played','desc')
            //         ->limit('10')
            //         ->get();

            $most= Song::leftJoin('artists','songs.artist_id','=','artists.id')
            ->select('songs.*')
            ->where('artists.isVerified','=','1')
            ->where('songs.song','!=','')
            ->orderBy('songs.played','desc')
            ->limit('10')            
            ->get();

            foreach ($most as $most_value) {


                $artist=Artist::select('*')->where('id', $most_value->artist_id)
                                     ->first();

                $language=Language::select('*')->where('id', $most_value->language_id)
                                     ->first();
                 $category=Category::select('*')->where('id', $most_value->category_id)
                 ->first();

                    $most_value->artist_id=$artist;
                    $most_value->language_id=$language;
                   $most_value->category_id=$category;

                $liked=explode(',',$most_value->liked);

                $most_value->liked=$liked;

                $all_featuring=array();
                if($most_value->featuring != "")
                { 
                  $data_featuring= explode(',',$most_value->featuring);
                  foreach($data_featuring as $value_fea){

                    $artist=Artist::select('*')->where('id', $value_fea)
                                     ->first();
                     $all_featuring[]=$artist;               
                  }
                }
               $most_value->featuring = $all_featuring;

               }
            
       $albums = DB::table('albums')
                    ->limit('10')
                    ->get();
        
        foreach($albums as $album)
        { 

            $song= Song::leftJoin('artists','songs.artist_id','=','artists.id')
            ->select('songs.*')
            ->where('artists.isVerified','=','1')
            ->where('songs.song','!=','')
            ->where('songs.album','=',$album->id)        
            ->get();    

                foreach ($song as $value) {


                    $artist=Artist::select('*')->where('id', $value->artist_id)
                                         ->first();

                    $language=Language::select('*')->where('id', $value->language_id)
                                         ->first();
                     $category=Category::select('*')->where('id', $value->category_id)
                     ->first();

                        $value->artist_id=$artist;
                        $value->language_id=$language;
                       $value->category_id=$category;

                $liked=explode(',',$value->liked);

                $value->liked=$liked;

                $all_featuring=array();
                if($value->featuring != "")
                { 
                  $data_featuring= explode(',',$value->featuring);
                  foreach($data_featuring as $value_fea){

                    $artist=Artist::select('*')->where('id', $value_fea)
                                     ->first();
                     $all_featuring[]=$artist;               
                  }
                }
               $value->featuring = $all_featuring;

                }
                $album->songs=$song;
        } 

        $catlist = Category::limit('10')->get();

        $topArtist = Artist::orderBy('played','DESC')->limit('10')->get(); 

         $data=featuredPlaylists::get(); 
     $all_song_data=[];
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


                // $song_data = DB::table('songs')
                // ->where('id', '=', $value_id)
                // ->first();

            $song_data= Song::leftJoin('artists','songs.artist_id','=','artists.id')
            ->where('artists.isVerified','=','1')
            ->where('songs.id', '=', $value_id)
            ->where('songs.song','!=','')
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

    $collection= Song::leftJoin('artists','songs.artist_id','=','artists.id')
        ->select('songs.*')
        ->where('artists.isVerified','=','1')
        ->where('songs.song','!=','')
        ->orderBy('songs.created_at','asc')
        ->limit('10')
        ->get();

     foreach ($collection as $value) {
                $artist=Artist::select('*')->where('id', $value->artist_id)
                                     ->first();

                $language=Language::select('*')->where('id', $value->language_id)
                                     ->first();
                $category=Category::select('*')->where('id', $value->category_id)
                 ->first();

                $value->artist_id=$artist;
                $value->language_id=$language;
                $value->category_id=$category;
          
                $liked=explode(',',$value->liked);

                $value->liked=$liked; 
                $all_featuring=array();
                if($value->featuring != "")
                { 
                  $data_featuring= explode(',',$value->featuring);
                  foreach($data_featuring as $value_fea){

                    $artist=Artist::select('*')->where('id', $value_fea)
                                     ->first();
                     $all_featuring[]=$artist;               
                  }
                }
               $value->featuring = $all_featuring;
        }

        // Trending SOngs 

        $date_array = array();
        $date_count = array();
        $all_songs = array();
        $find=array();
        $i = 0;
        while ($i < 10) {
            $today = Carbon::today();
            array_push( $date_array, $today->subDays($i)->format('Y-m-d') );
            $i++;
        }

        if(! empty( $date_array ) ){
            foreach($date_array as $date){
                $find = songsRecord::where( 'played_date', '>', $date )
                            ->orderBy('played','desc')
                            ->limit('10')
                            ->get();
            }
        }
             $i=0;
              foreach ($find as $value_data) {
                  
                $songID=$value_data->song_id;

                $song= Song::leftJoin('artists','songs.artist_id','=','artists.id')
                ->where('artists.isVerified','=','1')
                ->where('songs.id', $songID)
                ->where('songs.song','!=','')
                ->first(); 

                if(empty($song))
                {
                    unset($find[$i]);
                }
                else
                { 
                        $artist=Artist::select('*')->where('id', $song->artist_id)
                                             ->first();

                        $language=Language::select('*')->where('id', $song->language_id)
                                             ->first();
                        $category=Category::select('*')->where('id', $song->category_id)
                         ->first();

                            $song->artist_id=$artist;
                            $song->language_id=$language;
                           $song->category_id=$category;
                           $liked=explode(',',$song->liked);

                            $song->liked=$liked;  

                            $all_featuring=array();
                            if($song->featuring != "")
                            { 
                              $data_featuring= explode(',',$song->featuring);
                              foreach($data_featuring as $value_fea){
            
                                $artist=Artist::select('*')->where('id', $value_fea)
                                                 ->first();
                                 $all_featuring[]=$artist;               
                              }
                            }
                           $song->featuring = $all_featuring;

                    $all_songs[]=$song;
                }
                   $i++;
               }

        //featured Artists
        $Featartist=Artist::where('isVerified','=','1')
                        ->get(); 
        $FeatArtistsongs=array();
        $i=0;
        foreach($Featartist as $Featvalue)
        {
            $featuredArtistsdata= featuredArtists::where('artist_id','=',$Featvalue['id'])
                                    ->limit('30')
                                    ->inRandomOrder()
                                    ->get();
            if(!blank($featuredArtistsdata))
            {
                foreach($featuredArtistsdata as $Songvalue)
                {
                  $song= Song::where('id',$Songvalue['song_id'])->first();

                  $artist=Artist::select('*')->where('id', $song->artist_id)
                  ->first();
                    if($artist->isVerified == 1)
                    {
                    $language=Language::select('*')->where('id', $song->language_id)
                                    ->first();
                    $category=Category::select('*')->where('id', $song->category_id)
                    ->first();

                    $song->artist_id=$artist;
                    $song->language_id=$language;
                    $song->category_id=$category;


                    $all_featuring=array();
                    if($song->featuring != "")
                    { 
                    $data_featuring= explode(',',$song->featuring);
                    foreach($data_featuring as $value_fea){

                    $artist=Artist::select('*')->where('id', $value_fea)
                                    ->first();
                    $all_featuring[]=$artist;               
                    }
                    }
                    $song->featuring = $all_featuring;
                    $liked=explode(',',$song->liked);

                    $song->liked=$liked;

                  $FeatArtistsongs[]=$song;
                  }
                }

                    $response[]= array('Artist' => $Featvalue , 'Song' => $FeatArtistsongs);

                    unset($FeatArtistsongs);      
            } 
            
            $i++;
        }
            
             return response()->json([
                'success' => true,
                'Mix Songs' => $songs,
                'Artists'  =>$all_artist,
                'Popular Songs' =>$most,
                'Albums' => $albums,
                'Categories' =>$catlist,
                'Best of Artists' => $topArtist,
                'Selected Playlists' => $data,
                'Latest Songs' => $collection,
                'Trending Songs' => $all_songs,
                'Featured Artists' =>$response
            ], Response::HTTP_OK);

    }

    public function Banner_get()
    {
        $banners=Banner::all();
        foreach($banners as $value)
        {
            $data[]=$value->banner;
        }
        return response()->json([
                'success' => true,
                'Banners' => $data
            
            ], Response::HTTP_OK);
    }

}