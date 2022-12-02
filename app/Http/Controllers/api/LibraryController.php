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
use App\Models\library;
use App\Models\podcastsEpisodes;
use App\Models\podcast;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;
use Illuminate\Routing\UrlGenerator;



class LibraryController extends Controller
{
    public function user_add_artist(Request $request)
    {
    
        $data['user_id']=$request->user_id;
        $data['add_artist']=$request->add_artist;
        
         $find=library::select('*')->where('user_id', $request->user_id)
                         ->first();
         if(empty($find))
         {
         	$message="User selected artist is saved successfully.";
         	$library = library::create($data);
         }
         else
         {   
 	        $artist=array();
	        $artist=explode(',',$find->add_artist);
	      
	        if(in_array($request->add_artist,$artist))
	        {
	           $message='Already added artist by user';
	        }
	        else
	        {
	        $update['add_artist']=$find->add_artist.','.$request->add_artist;    
	         $updated=library::where('user_id',$request->user_id)->update($update);
	         $message='User selected artist is updated successfully';
	        } 
         	 
         }

         return response()->json([
                'success' => true,
                'Message' => $message
            ], Response::HTTP_OK);
    }

    public function user_add_artist_get($id)
    {
        $find=library::select('*')->where('user_id', $id)
                         ->first();
         	$artist=array();
	        $artist=explode(',',$find->add_artist);
	      
            $all_data=array();
            $i=1;
            foreach($artist as $value)
            {
            	$artist=Artist::select('*')->where('id', $value)
                                     ->first();
                $song=Song::select('*')->where('artist_id', $artist->id)
                                     ->first();     
                    $liked=explode(',',$song->liked);

                       $song->liked=$liked;  
                $all_data[$i]['artist']=$artist;
                $all_data[$i]['song']=$song;
                $i++;
            }

            return response()->json([
                'success' => true,
                'Message' => $all_data
            ], Response::HTTP_OK);
    }

    public function user_add_podcast(Request $request)
    {
    
        $data['user_id']=$request->user_id;
        $data['add_podcast']=$request->add_podcast;
        
         $find=library::select('*')->where('user_id', $request->user_id)
                         ->first();
         if(empty($find))
         {
            $message="User selected Podcast is saved successfully.";
            $library = library::create($data);
         }
         else
         {   
            $podcast=array();
            $podcast=explode(',',$find->add_podcast);
          
            if(in_array($request->add_podcast,$podcast))
            {
               $message='Already added podcast by user';
            }
            else
            {
            $update['add_podcast']=$find->add_podcast.','.$request->add_podcast;    
             $updated=library::where('user_id',$request->user_id)->update($update);
             $message='User selected Podcast is updated successfully';
            } 
             
         }

         return response()->json([
                'success' => true,
                'Message' => $message
            ], Response::HTTP_OK);
    }

    public function user_add_podcast_get($id)
    {
        $find=library::select('*')->where('user_id', $id)
                         ->first();
            $podcast=array();
            $podcast=explode(',',$find->add_podcast);
          
            $all_data=array();
            $i=1;
            foreach($podcast as $value)
            {
                $podcast=podcast::select('*')->where('id', $value)
                                     ->first();
                if(!empty($podcast)) 
                {                    
                $episode=podcastsEpisodes::select('*')->where('podcast', $podcast->id)
                                     ->get();  
                $podcast->episodes=$episode;
                $all_data[$i]=$podcast;
                }
                else
                {
                    unset($all_data[$i]);
                }
                $i++;
            }

            return response()->json([
                'success' => true,
                'data' => $all_data
            ], Response::HTTP_OK);
    }
}