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
use App\Models\podcast;
use App\Models\podcastsEpisodes;
use App\Models\episodesRecord;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use DB;
use Artisan;
use Illuminate\Routing\UrlGenerator;
use Carbon\Carbon;



class PodcastController extends Controller
{ 
	public function index()
    {

     $data=podcast::get(); 

       foreach ($data as $value_id) {

                $song_data = DB::table('podcasts_episodes')
                ->where('podcast', '=', $value_id->id)
                ->get();

              $value_id->episodes = $song_data;
        }

      return response()->json([
                'success' => true,
                'Data'   =>$data
            ], Response::HTTP_OK);
        
        
    }

    public function user_podcast_like(Request $request)
    {
       
      
      $find=DB::table('podcasts_episodes')
            ->select('liked')
            ->where('id', '=',$request->podcastID)
            ->first();
        if(!empty($find))
        {  
           if($find->liked == '')
           {
             $data['liked']=$request->userID;    
             $song=podcastsEpisodes::where('id',$request->podcastID)->update($data);
             $message='Liked Podcast Successfully';
           }
           else
           {
              $users=array();
              $users=explode(',',$find->liked);
              
              if(in_array($request->userID,$users))
              {
                 $message='Already liked by user';
              }
              else
              {

              $data['liked']=$find->liked.','.$request->userID;    
               $song=podcastsEpisodes::where('id',$request->podcastID)->update($data);
               $message='Liked Podcast Successfully';
              } 
            }

        }
        else
        {
             $message='The Podcast id is not found';
        }

                return response()->json([
                    'Message' => $message
                ], Response::HTTP_OK);
        
    }

    public function user_podcast_unlike(Request $request)
    {
       
      
      $find=DB::table('podcasts_episodes')
            ->select('liked')
            ->where('id', '=',$request->podcastID)
            ->first();
        if(!empty($find))
        {
        $users=array();
        $users=explode(',',$find->liked);

          
        foreach (array_keys($users, $request->userID) as $key) {
            unset($users[$key]);
        }

        $users_liked['liked']=implode(',',$users); 
         $song=podcastsEpisodes::where('id',$request->podcastID)->update($users_liked);
         $message='unliked podcast Successfully';
        }
        else
        {
            $message='podcast episode not found';
        }
            return response()->json([
                'success' => true,
                'Message' => $message,
            ], Response::HTTP_OK);
        
    }

    public function user_podcasts_like_get($id)
    {
       $data=DB::table('podcasts_episodes')
            ->select('*')
            ->get();
     
      $all_podcast=array();
    foreach($data as $value)
    { 
      
    $users=array();
    $users=explode(',',$value->liked);
        
        if(in_array($id,$users))
        {
           $all_podcast[]=$value;
    }


    }

              foreach ($all_podcast as $value) {


                $podcast=podcast::select('*')->where('id', $value->podcast)
                                     ->first();
                if($podcast != '')
                {
                $artist=Artist::select('*')->where('id', $podcast->artist_id)
                                     ->first();
                  $value->artist_id=$artist;
                }
                   
                    $liked=explode(',',$value->liked);

                      $value->liked=$liked;
                      // $all_featuring=array();
                      //   if($value->featuring != "")
                      //   { 
                      //     $data_featuring= explode(',',$value->featuring);
                      //     foreach($data_featuring as $value_fea){

                      //       $artist=Artist::select('*')->where('id', $value_fea)
                      //                        ->first();
                      //        $all_featuring[]=$artist;               
                      //     }
                      //   }
                      //  $value->featuring = $all_featuring;

               }
      return response()->json([
                'success' => true,
                'Message' => 'All podcast Episodes liked by user',
                'Data'   =>$all_podcast
            ], Response::HTTP_OK);
        
        
    }

    public function episode_played_date_store(Request $request)
    {
      //echo "test";die('heree');

       
        $Message='';
        
        $find='';
        if($request->episode_id != '' && $request->user_id != '')
        { 
          $get_played=podcastsEpisodes::select("played")
                  ->where('id', '=', $request->episode_id)
                  ->first();
          if(!empty($get_played))  
          {
              $data['played']=$get_played->played+1;
              $song=podcastsEpisodes::where('id',$request->episode_id)->update($data);
          }
          $played_store=Artist::select("played")
                  ->where('id', '=', $request->artist_id)
                  ->first(); 
          if(!empty($played_store))  
          {
          $artist_data['played']=$played_store->played+1;
          $artist=Artist::where('id',$request->artist_id)->update($artist_data);
          }

          $date=date('Y-m-d');
          $find=episodesRecord::select('*')->where('user_id', '=', $request->user_id)
                               ->where('episode_id', '=',$request->episode_id)  
                               ->where('played_date','=', $date)
                               ->first();
   
          if(isset($find))
          {    
              $updata['played']=$find->played+1;
              $song=episodesRecord::where('id',$find->id)->update($updata);

          }
          else
          {  
              $data=$request->all();
              $date=date('Y-m-d');
              $data['played_date']=date('Y-m-d');
              $data['played']=1;
              $songs = episodesRecord::create($data);
          }
          $Message='Song record added Successfully';
        }
        else{
          $Message="Please enter all the params";
        }

         return response()->json([
                'success' => true,
                'Message' => $Message,
                'data'=>$find
            ], Response::HTTP_OK);
    }
}
