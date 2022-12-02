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
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use DB;
use Artisan;
use Illuminate\Routing\UrlGenerator;
use Carbon\Carbon;
use function Matrix\diagonal;


class SongController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.verify',['except' => ['song_by_id','latest_songs','index','artist_song_list','language_song_list','category_song_list','album_song_list','most_played_song','mix_songs','get_trending_songs','autoplay_songs','get_albums','monthly_listeners']]);
    }

    public function song_by_id($id)
    {
      
        $song=Song::select('*')->where('id', $id)->where('status',0)
                        ->first();
                       
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
                 $data = auth()->guard('api')->user(); 

                    $is_liked="";
                    foreach ($liked as $key => $value) {
                      if($value == $data->id)
                      {
                        $is_liked =true;
                      }
                      else
                      {
                        $is_liked = false;
                      }
                    }
                   $song->is_liked = $is_liked;
						
                            return response()->json([
                                'success' => true,
                                'Message' => $song,
                            ], Response::HTTP_OK);
                  }
                  else
                  {
                    return response()->json([
                                'success' => true,
                                'Message' => 'Song is not available'
                            ], Response::HTTP_OK);
                  }                 


    }
    
    public function latest_songs()
    {

        $collection= Song::leftJoin('artists','songs.artist_id','=','artists.id')
          ->select('songs.*')
          ->where('artists.isVerified','=','1')
          ->where('songs.status','=','0')
          ->orderBy('songs.created_at','asc')
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
                        
                        // $songs[$songKey]->liked = $liked;
                         $value->liked=$liked;
                         //$data = auth()->guard('api')->user(); 

			            //     $is_liked="";
			            // foreach ($liked as $key => $value) {
			            // 	if($value = $data->id)
			            // 	{
			            // 		$is_liked =true;
			            // 	}
			            // 	else
			            // 	{
			            // 		$is_liked=false;
			            // 	}
			            // }
			            // $song->is_liked = $is_liked;

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

                return response()->json([
                    'success' => true,
                    'Message' => $collection
                ], Response::HTTP_OK);

    }


    public function index(Request $request)
    {
        if(!empty($request->skip) && !empty($request->take))
        {

        $song= Song::leftJoin('artists','songs.artist_id','=','artists.id')
          ->select('songs.*')
          ->where('artists.isVerified','=','1')
          ->skip($request->skip)->take($request->take)
          ->where('song','!=','')
          ->where('songs.status',0)
          ->get();

        }
        else
        { 
          $song= Song::leftJoin('artists','songs.artist_id','=','artists.id')
          ->select('songs.*')
          ->where('artists.isVerified','=','1')
          ->where('song','!=','')
          ->get();
        }

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
        return response()->json([
            'success' => true,
            'data' => $song,
        ], Response::HTTP_OK);
         
    }

    public function artist_song_list($id)
    {
      $songs = $artist = $users = [];
      $artist = DB::table('artists')
        ->where('id', '=', $id)
        ->where('isVerified','=','1')
        ->first();

      if(!blank($artist))
      {
        $songs = Song::where('artist_id', '=', $id)
          ->where('song','!=','')
          ->where('status',0)
          ->get();
        foreach ($songs as $songKey => $song) {
          $language=Language::select('*')
            ->where('id', $song->language_id)
            ->first();
          
          $category=Category::select('*')
          ->where('id', $song->category_id)
          ->first();

          $songs[$songKey]->artist_id=$artist;
          $songs[$songKey]->language_id=$language;
          $songs[$songKey]->category_id=$category;
          $liked=explode(',',$song->liked);

          $songs[$songKey]->liked = $liked;
          $data = auth()->guard('api')->user(); 

                $is_liked="";
            foreach ($liked as $key => $value) {
            	if($value = $data->id)
            	{
            		$is_liked =true;
            	}
            	else
            	{
            		$is_liked=false;
            	}
            }
            $song->is_liked = $is_liked;

          $all_featuring=array();
          if($song->featuring != "")
          {
            $data_featuring= explode(',',$song->featuring);
            foreach($data_featuring as $value_fea){
              $featuringArtist = Artist::select('*')
                ->where('id', $value_fea)
                ->first();
              $all_featuring[]=$featuringArtist;               
            }
          }
          $songs[$songKey]->featuring = $all_featuring;
        }

        $followers = explode(',',$artist->followers);
        foreach($followers as $userId)
        {
          $user = User::select('id','name','email')->where('id',$userId)->first();
          if ($user) {
            $users[] = $user;
          }

        }
        $artist->followers=$users;
      } 

          return response()->json([
            'success' => true,
            'Artist' =>  $artist,
            'data' => $songs
        ], Response::HTTP_OK);    
      



    }

    public function language_song_list($id)
    {

        $song= Song::leftJoin('artists','songs.artist_id','=','artists.id')
          ->select('songs.*')
          ->where('artists.isVerified','=','1')
          ->where('songs.language_id', '=', $id)
          ->where('song','!=','')
            ->where('status',0)
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
        $languages = DB::table('languages')
                ->where('id', '=', $id)
                ->get(); 

            return response()->json([
            'success' => true,
            'language' => $languages,
            'data' => $song
        ], Response::HTTP_OK);

    }

    public function category_song_list($id)
    {

        $song= Song::leftJoin('artists','songs.artist_id','=','artists.id')
          ->select('songs.*')
          ->where('artists.isVerified','=','1')
          ->where('songs.category_id', '=', $id)
          ->where('song','!=','')
            ->where('songs.status',0)
          ->get();

        $category = DB::table('categories')
                ->where('id', '=', $id)
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

            return response()->json([
            'success' => true,
            'Category' => $category,
            'data' => $song
        ], Response::HTTP_OK);

    }

    public function album_song_list($id)
    {


        $song= Song::leftJoin('artists','songs.artist_id','=','artists.id')
          ->select('songs.*')
          ->where('artists.isVerified','=','1')
          ->where('songs.album', '=', $id)
          ->where('song','!=','')
            ->where('songs.status',0)
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

        $category = DB::table('albums')
                ->where('id', '=', $id)
                ->get(); 

            return response()->json([
            'success' => true,
            'Album' => $category,
            'Songs' => $song
        ], Response::HTTP_OK);

    }

    public function most_played_song()
    {

      $song= Song::leftJoin('artists','songs.artist_id','=','artists.id')
          ->select('songs.*')
          ->where('artists.isVerified','=','1')
          ->orderBy('played','desc')
          ->where('song','!=','')
          ->where('songs.status',0)
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

        return response()->json([
                'success' => true,
                'data' => $song
            ], Response::HTTP_OK);

    }



    public function mix_songs()
    { 

          $songs= Song::leftJoin('artists','songs.artist_id','=','artists.id')
          ->select('songs.*')
          ->where('artists.isVerified','=','1')
          ->where('song','!=','')
          ->where('songs.status',0)
          ->inRandomOrder()
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

            return response()->json([
                'success' => true,
                'data' => $songs
            ], Response::HTTP_OK);
    }
    
    public function song_played_date_store(Request $request)
    { 
        $Message='';
        $find='';
        if($request->song_id != '' && $request->artist_id != '' && $request->user_id != '')
        { 
          $get_played=Song::select("played")
                  ->where('id', '=', $request->song_id)
                   ->where('status',0)
                  ->first();
          if(!empty($get_played))  
          {
              $data['played']=$get_played->played+1;
              $song=Song::where('id',$request->song_id)->update($data);
          }
          $played_store=Artist::select("played")
                  ->where('id', '=', $request->artist_id)
                  ->first(); 
          if(!empty($played_store))  
          {
            if($played_store->played == '')
                {
                    $played_store->played=0;
                }
          $artist_data['played']=$played_store->played+1;
          $artist=Artist::where('id',$request->artist_id)->update($artist_data);
          }

          $date=date('Y-m-d');
          $find=songsRecord::select('*')->where('user_id', '=', $request->user_id)
                               ->where('song_id', '=',$request->song_id)  
                               ->where('played_date','=', $date)
                               ->first();
   
          
          if(isset($find))
          {    
              $updata['played']=$find->played+1;
              $song=songsRecord::where('id',$find->id)->update($updata);

          }
          else
          {  
              $data=$request->all();
              $date=date('Y-m-d');
              $data['played_date']=date('Y-m-d');
              $data['played']=1;
              $songs = songsRecord::create($data);
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

    public function song_played_by_date($id)
    {  
        $response=array();
        $skip=0;
         
        $date_array = array();
        $date_count = array();
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
                            ->where('user_id', $id)
                            ->get();
            }
        }
             $i=0;
              foreach ($find as $value_data) {
                  
                $songID=$value_data->song_id;

                $song=Song::select('*')->where('id', $songID)
                        ->where('song','!=','')
                    ->where('songs.status',0)
                        ->first();
                if(empty($song))
                {   
                    $delete_entry=DB::table('songs_records')->where('song_id', $songID)->delete();
                } 
              }
              if(! empty( $date_array ) ){

                foreach($date_array as $date){
                      $all_data = songsRecord::where( 'played_date', '>', $date )
                            ->where('user_id', $id)
                            ->orderBy('played_date', 'DESC')
                            ->get();
                }
              }
              foreach ($all_data as $value_data) {
                  
                $songID=$value_data->song_id;

                $song=Song::select('*')->where('id', $songID)
                        ->where('song','!=','')
                    ->where('status',0)
                        ->first();

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
                           
                      $response[$value_data['played_date']][]=$song;


              
               }

                 return response()->json([
                'success' => true,
                'songs' => $response
            ], Response::HTTP_OK);
    }

    public function get_trending_songs()
    {  
         
        $date_array = array();
        $date_count = array();
        $all_songs= array();
        $find=array();
        $i = 0;
        while ($i < 10) {
            $today = Carbon::today();
            array_push( $date_array, $today->subDays($i)->format('Y-m-d') );
            $i++;
        }

        if(! empty( $date_array ) ){
            foreach($date_array as $date){
                $find = DB::table('songs_records')
                            ->select(DB::raw('DISTINCT(song_id)'))
                            ->where( 'played_date', '>', $date )
                            ->orderBy('played','desc')
                            ->get();

            }
        }
             $i=0;
              foreach ($find as $value_data) {
                  
                $songID=$value_data->song_id;

                $song=Song::select('*')->where('id', $songID)
                        ->where('song','!=','')
                    ->where('status',0)
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
                            //$songs[$songKey]->liked = $liked;
				          $data = auth()->guard('api')->user(); 

				                $is_liked="";
				            foreach ($liked as $key => $value) {
				            	if($value = $data->id)
				            	{
				            		$is_liked =true;
				            	}
				            	else
				            	{
				            		$is_liked=false;
				            	}
				            }
				            $song->is_liked = $is_liked;

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

                 return response()->json([
                'success' => true,
                'song' => $all_songs
            ], Response::HTTP_OK);
    }

    public function user_song_like(Request $request)
    {
       
      
      $find=DB::table('songs')
            ->select('liked')
            ->where('id', '=',$request->songID)
          ->where('status',0)
            ->first(); 

        if(!empty($find))
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
             $song=Song::where('id',$request->songID)->update($data);
             $message='Liked Song Successfully';
            } 

        }
        else
        {
             $message='The song id is not found';
        }

                return response()->json([
                    'Message' => $message
                ], Response::HTTP_OK);
        
    }

    public function user_song_unlike(Request $request)
    {
       
      
      $find=DB::table('songs')
            ->select('liked')
            ->where('id', '=',$request->songID)
          ->where('status',0)
            ->first();
        if(!empty($find))
        {
        $users=array();
        $users=explode(',',$find->liked);

          
        foreach (array_keys($users, $request->userID) as $key) {
            unset($users[$key]);
        }

        $users_liked['liked']=implode(',',$users); 
         $song=Song::where('id',$request->songID)->update($users_liked);
         $message='unliked Song Successfully';
        }
        else
        {
            $message='Song not found';
        }

        
            return response()->json([
                'success' => true,
                'Message' => $message,
            ], Response::HTTP_OK);
        
    }
	public function user_song_like_get($id)
    {
      

        $data= Song::leftJoin('artists','songs.artist_id','=','artists.id')
        ->select('songs.*')
        ->where('artists.isVerified','=','1')
        ->where('song','!=','')
            ->where('songs.status',0)
        ->get();
	   
      $all_songs=array();
	  foreach($data as $value)
	  { 
	    
		$users=array();
		$users=explode(',',$value->liked);
        
        if(in_array($id,$users))
        {
           $all_songs[]=$value;
		}


	  }

              foreach ($all_songs as $value) {


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
      return response()->json([
                'success' => true,
                'Message' => 'All liked songs by user',
                'Data'   =>$all_songs
            ], Response::HTTP_OK);
        
        
    }

    public function artist_played_count($id)
    {     


            $artist=songsRecord::select('*')->where('artist_id', $id)
                                             ->get();

            $sum=0;                                 
            foreach($artist as $value)
            {   
                $sum=$sum+$value->played;   
            }
             return response()->json([
                'success' => true,
                'played'   =>$sum
            ], Response::HTTP_OK);
        
        
    } 

    public function popular_videos()
    { 

        $song= Song::leftJoin('artists','songs.artist_id','=','artists.id')
        ->select('songs.*')
        ->where('artists.isVerified','=','1')
        ->where('songs.video','!=','')
        ->orderBy('songs.played','desc')
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

        return response()->json([
                'success' => true,
                'data' => $song
            ], Response::HTTP_OK);

    }

    public function get_albums()
    { 
        $albums = DB::table('albums')
                    ->get();
        
        foreach($albums as $album)
        { 

            $song= Song::leftJoin('artists','songs.artist_id','=','artists.id')
            ->select('songs.*')
            ->where('artists.isVerified','=','1')
            ->where('songs.album','=',$album->id)
            ->where('songs.song','!=','')
                ->where('songs.status',0)
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
        return response()->json([
                'success' => true,
                'data' => $albums
            ], Response::HTTP_OK);

    }

    public function autoplay_songs()
    { 
          $songs= Song::leftJoin('artists','songs.artist_id','=','artists.id')
            ->select('songs.*')
            ->where('artists.isVerified','=','1')
            ->where('song','!=','')
            ->inRandomOrder()
            ->limit('7')
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

            return response()->json([
                'success' => true,
                'data' => $songs
            ], Response::HTTP_OK);
    }
    
}