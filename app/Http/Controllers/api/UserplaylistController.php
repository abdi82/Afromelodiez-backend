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
use App\Models\Language;
use App\Models\Category;
use App\Models\userPlaylist;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;
use Illuminate\Routing\UrlGenerator;



class UserplaylistController extends Controller
{

    public function user_add_playlist(Request $request)
    {
    
        $data['user_id']=$request->userID;
        $data['playlist_name']=$request->playlistName;
        
        
         $find=userPlaylist::select('*')->where('user_id', $request->userID)
                         ->where('playlist_name','=',$request->playlistName)
                         ->get();
               
        if($find == '[]')
        {
            $message="User created playlist is saved successfully.";
            $userPlaylist = userPlaylist::create($data);
            $find=$userPlaylist;
        
        }
        else
        {
            $message='User already created playlist with this name.';
              $id=$find[0]->id;
        } 

            return response()->json([
                'success' => true,
                'Message' => $message,
                'Data stored id' =>$find
            ], Response::HTTP_OK);
    }

    public function user_add_song_playlist(Request $request)
    {   
        if($request->user_id != '' && $request->playlistID != '' && $request->songID != '')
        {
    	$find=userPlaylist::select('*')->where('user_id', $request->user_id)
    	                ->where('id',$request->playlistID)
                         ->first();
        
        if(!empty($find))
        {
         if($find->song_ID == '')
         {  

            $update['song_ID']=$request->songID;
         	$updated=userPlaylist::where('user_id',$request->user_id)
             ->where('id',$request->playlistID)
             ->update($update);
         	$message="Song added to the playlist.";
         	
         }
         else
         {   
 	        $list=array();
	        $list=explode(',',$find->song_ID);
	        $song_id=array();
            $all_songs=explode(',',$request->songID);

            foreach($all_songs as $song)
            {  
    	        if(in_array($song,$list))
    	        {
    	           $message='Already added song in the playlist';
    	        }
    	        else
    	        {
    	          $song_id[]=$song;
               
    	        } 
            }
            if(!empty($song_id))
            {
                $songs_add=implode(',',$song_id);

                $update['song_ID']=$find->song_ID.','.$songs_add;

                $updated=userPlaylist::where('user_id',$request->user_id)
                     ->where('id',$request->playlistID)
                     ->update($update);

                $message='Song added to the playlist';
            }
	      }

           return response()->json([
                'success' => true,
                'Message' => $message
            ], Response::HTTP_OK);
        }
        else
        {
            return response()->json([
                'success' => false,
                'Message' => 'Playlist is not available'
            ], Response::HTTP_OK);
        }
      }
    }

    public function delete_user_playlist_song(Request $request)
    {   
        $find=userPlaylist::select('*')->where('user_id', $request->user_id)
                        ->where('id',$request->playlistID)
                         ->first();
        

         if($find->song_ID == '')
         {  
            $message="Playlist not available.";
            
         }
         else
         {   
            $list=[];
            $list=explode(',',$find->song_ID);
            $song_id=array();
            $all_songs=explode(',',$request->songID);
            $p=0;
            foreach($list as $song)
            {  
                if($song == $request->songID)
                { 
                    unset($list[$p]);
                }
                $p++;
                // if($song == '')
                // {
                //      unset($song);
                // }
            }

                $update['song_ID']=implode(',',$list);

                $updated=userPlaylist::where('user_id',$request->user_id)
                     ->where('id',$request->playlistID)
                     ->update($update);

                $message='Song removed from the playlist';
            
          }


            
            return response()->json([
                'success' => true,
                'Message' => $message
            ], Response::HTTP_OK);
    }
                           
    public function user_playlist_get($userID)
    {
        $find=array();
        $find=userPlaylist::select('*')->where('user_id', $userID)
                         ->get();
        
        foreach($find as $playlist_data)
        { 
            $playlist_find=userPlaylist::select('*')->where('user_id', $playlist_data['user_id'])
                        ->where('id', $playlist_data['id'])
                         ->get();

            $playlist=array();
            $playlist=explode(',',$playlist_find[0]->song_ID);

            $all_data=array();
            //$i=1;
            foreach($playlist as $songValue)
            {
                $value=Song::select('*')->where('id', $songValue)
                                             ->first();
                if($value != '')
                {                            
                 
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

                     $all_data[]=$value;
                }
                
                //$i++;
            }
            $playlist_data['song_ID']=$all_data;
        }    

            return response()->json([
                'success' => true,
                'Data' => $find
            ], Response::HTTP_OK);
    }  

   public function user_all_playlist_get($userID)
   {
        $find=userPlaylist::select('*')->where('user_id', $userID)
                         ->get();


            return response()->json([
                'success' => true,
                'Data' => $find
            ], Response::HTTP_OK);

   }

   public function user_playlist_delete($ID)
   {
        $find=userPlaylist::where('id', $ID)
                         ->delete();
                         
            return response()->json([
                'success' => true,
                'Data' => 'User playlist Deleted Successfully'
            ], Response::HTTP_OK);

   }

}