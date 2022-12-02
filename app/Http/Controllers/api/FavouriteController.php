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
use App\Models\Album;
use App\Models\podcastsEpisodes;
use App\Models\podcast;
use App\Models\Language;
use App\Models\Category;
use App\Models\featuredPlaylists;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;
use Illuminate\Routing\UrlGenerator;



class FavouriteController extends Controller
{
    public function add_favourite_artist(Request $request)
    {
         $find=Artist::select('*')->where('id', $request->artist_id)
                         ->first();
         if(empty($find))
         {
         	$message="Artist is not exist";
         	
         }
         else
         {   
 	        $users=array();
	        $users=explode(',',$find->favourite);
	      
	        if(in_array($request->user_id,$users))
	        {
	           $message='Already added the artist to the favourite list.';
	        }
	        else
	        {
	        $update['favourite']=$find->favourite.','.$request->user_id;    
	         $updated=Artist::where('id',$request->artist_id)->update($update);
	         $message='Artist is added to the favourite list.';
	        } 
         	 
         }

         return response()->json([
                'success' => true,
                'Message' => $message
            ], Response::HTTP_OK);
    }

        public function add_unfavourite_artist(Request $request)
    {
         $find=Artist::select('*')->where('id', $request->artist_id)
                         ->first();
         if(empty($find))
         {
            $message="Artist is not exist";
            
         }
         else
         {   
            $users=array();
            $users=explode(',',$find->favourite);
            $i=0;
            foreach($users as $user)
            {
              if($user == $request->user_id)
              {
                unset($users[$i]);
              }
              $i++;
            }
           
            $update['favourite']=implode(',',$users);   
             $updated=Artist::where('id',$request->artist_id)->update($update);
             $message='Artist is unfavourite successfully.';
            
             
         }

         return response()->json([
                'success' => true,
                'Message' => $message
            ], Response::HTTP_OK);
    }

    public function add_favourite_playlist(Request $request)
    {        
         $find_featured=featuredPlaylists::select('*')->where('id', $request->playlist_id)
                         ->first();


        if(empty($find_featured))
        {
            $message="Featured Playlist is not exist";    
        }
         else
         {   
            $users=array();
            $users=explode(',',$find_featured->favourite);
          
            if(in_array($request->user_id,$users))
            {
               $message='Already added the playlist to the favourite list.';
            }
            else
            {
            $update['favourite']=$find_featured->favourite.','.$request->user_id;    
             $updated=featuredPlaylists::where('id',$request->playlist_id)->update($update);
             $message='Favourite is added to the favourite list.';
            } 
             
         }

         return response()->json([
                'success' => true,
                'Message' => $message
            ], Response::HTTP_OK);
    }

    public function add_unfavourite_playlist(Request $request)
    {        
         $find_featured=featuredPlaylists::select('*')->where('id', $request->playlist_id)
                         ->first();


        if(empty($find_featured))
        {
            $message="Featured Playlist is not exist";    
        }
         else
         {   
            $users=array();
            $users=explode(',',$find_featured->favourite);
            $i=0;
            foreach($users as $user)
            {
              if($user == $request->user_id)
              {
                unset($users[$i]);
              }
              $i++;
            }
           
            $update['favourite']=implode(',',$users);   
             $updated=featuredPlaylists::where('id',$request->playlist_id)->update($update);
             $message='unfavourite the playlist successfully.';
         
             
         }

         return response()->json([
                'success' => true,
                'Message' => $message
            ], Response::HTTP_OK);
    }

    public function add_unfavourite_album(Request $request)
    {
    
         $find=Album::select('*')->where('id',$request->album_id)
                         ->first();   
            $users=array();
            $users=explode(',',$find->favourite);
            $i=0;
            foreach($users as $user)
            {
              if($user == $request->user_id)
              {
                unset($users[$i]);
              }
              $i++;
            }
           
            $update['favourite']=implode(',',$users);     
             $updated=Album::where('id',$request->album_id)->update($update);
             $message='The Album is unfavourite successfully';
            

         return response()->json([
                'success' => true,
                'Message' => $message
            ], Response::HTTP_OK);
    }

    public function add_favourite_album(Request $request)
    {
    
         $find=Album::select('*')->where('id', $request->album_id)
                         ->first();
         if(empty($find))
         {
            $message="Album is not exist";
            
         }
         else
         {   
            $users=array();
            $users=explode(',',$find->favourite);
          
            if(in_array($request->user_id,$users))
            {
               $message='Already added the Album to the favourite list.';
            }
            else
            {
            $update['favourite']=$find->favourite.','.$request->user_id;    
             $updated=Album::where('id',$request->album_id)->update($update);
             $message='Album is added to the favourite list.';
            } 
             
         }

         return response()->json([
                'success' => true,
                'Message' => $message
            ], Response::HTTP_OK);
    }

    public function get_favourite_artist($userid)
    {
        $Artist=array();
         $find=Artist::get();
            foreach($find as $value) 
            { 
                $users=array();
                $users=explode(',',$value->favourite);
              
                if(in_array($userid,$users))
                {
                   $Artist[]=$value;
                }
            }

         return response()->json([
                'success' => true,
                'Data' => $Artist,
            ], Response::HTTP_OK);
    }

    public function get_favourite_playlist($userid)
    {
        $featured=array();
         $find=featuredPlaylists::get();
            foreach($find as $value) 
            { 
                $users=array();
                $users=explode(',',$value->favourite);
              
                if(in_array($userid,$users))
                {
                   $featured[]=$value;
                }
            }
            
            $i=0;
            $songs_alldata=array(); 
            foreach($featured as $data_featured)
            { 
               $data= explode(',',$data_featured->song);

                
                foreach ($data as $value_data) {


                    if($value_data != '')
                    { 
                    $value=Song::select('*')->where('id', $value_data)
                                ->first();

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
                     
                      $songs_alldata[]=$value;
                 
                    }
                }

                 $data_featured->song=$songs_alldata;  
                 $i++;
            }
            

         return response()->json([
                'success' => true,
                'Data' => $featured,
            ], Response::HTTP_OK);
    }

    public function get_favourite_album($userid)
    {
        $Album=array();
         $find=Album::get();
            foreach($find as $value) 
            { 
                $users=array();
                $users=explode(',',$value->favourite);
              
                if(in_array($userid,$users))
                {
                   $Album[]=$value;
                }
            }

         return response()->json([
                'success' => true,
                'Data' => $Album,
            ], Response::HTTP_OK);
    }

}