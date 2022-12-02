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
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;
use Illuminate\Routing\UrlGenerator;



class UseraddpodcastController extends Controller
{   
    public function index()
    {

        $song = Song::paginate(10);

        return view('admin.song.index',compact('song'));
    }

    public function user_add_podcast(Request $request)
    {
    
        $data['user_id']=$request->user_id;
        $data['add_podcast']=$request->add_podcast;
        
         $find=library::select('*')->where('user_id', $request->user_id)
                         ->get();
         if($find[0]->id == '')
         {
            $message="User selected artist is saved successfully.";
            $library = library::create($data);
         }
         else
         {   
            $podcast=array();
            $podcast=explode(',',$find[0]->add_podcast);
          
            if(in_array($request->add_artist,$artist))
            {
               $message='Already added podcast by user';
            }
            else
            {
            $update['add_podcast']=$find[0]->add_podcast.','.$request->add_podcast;    
             $updated=library::where('user_id',$request->user_id)->update($update);
             $message='User selected podcast is updated successfully';
            } 
             
         }

         return response()->json([
                'success' => true,
                'Message' => $message
            ], Response::HTTP_OK);
    }

    public function get_podcast_create_form()
    {
        return view('admin.podcast.add');
    }

    public function user_add_artist_get($id)
    {
        $find=library::select('*')->where('user_id', $id)
                         ->get();
            $podcast=array();
            $podcast=explode(',',$find[0]->add_podcast);
          
            $all_data=array();
            $i=1;
            foreach($podcast as $value)
            {
                $artist=Podcast::select('*')->where('id', $value)
                                     ->get();
                $song=Song::select('*')->where('artist_id', $artist[0]->id)
                                     ->get();       
                $all_data[$i]['artist']=$artist;
                $all_data[$i]['song']=$song;
                $i++;
            }

            return response()->json([
                'success' => true,
                'Message' => $all_data
            ], Response::HTTP_OK);
    }
}