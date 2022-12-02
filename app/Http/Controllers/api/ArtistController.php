<?php

namespace App\Http\Controllers\api;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Song;
use App\Models\featuredArtists;
use App\Models\BetReport;
use App\Models\Category;
use App\Models\Language;

use App\Models\Emoji;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;


class ArtistController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Betting Form Page In admin Panel
    |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->middleware('jwt.verify',['except' => ['index','best_artist','featured_artist']]);
    }
    public function index($skip)
    {   

        $limit=15;

        if($skip != '')
        {
           $artist = Artist::
                  skip($skip)->take($limit)
                  ->where('isVerified','1')
                  ->get(); 
        }
        else
        {
            $artist = Artist::where('isVerified','1')
                       ->get(); 
        }
        foreach($artist as $value)
            {
                $followers=explode(',',$value->followers);
                  
                $value->followers=$followers;
            }
        return response()->json([
            'success' => true,
            'data' => $artist
        ], Response::HTTP_OK);
    }
    public function store(Request $request)
    {
        $data=$request->all();

        if ($request->hasFile('image')) {
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $data['image'] = $images;
            $upload = $request->file('image')->storeAs('public/users', $images);
        }
        else{
            $data['image'] ='';
        }


        $artist = Artist::create($data);

        return response()->json([
            'success' => true,
            'data' => $artist
        ], Response::HTTP_OK);

    }
    public function get_artist_form()
    {

        return view('admin.artist.add');
    }
    public function edit($id)
    {
          $artist=Artist::find($id);
        return response()->json([
            'success' => true,
            'data' => $artist
        ], Response::HTTP_OK);
    }
    public function update(Request $request ,$id)
    {
        $data = request()->except(['_token']);

        if ($request->hasFile('image')) {
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $data['image'] = $images;
            $upload = $request->file('image')->storeAs('public/users', $images);
        }

          $artist=Artist::where('id',$id)->update($data);
          
        return response()->json([
            'success' => true,
            'message' => 'Update Successfully'
        ], Response::HTTP_OK);
    }
    public function delete($id)
    {
        $user=Artist::find($id);
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Artist Deleted Successfully'
        ], Response::HTTP_OK);
       
    }

    public function best_artist()
    {     

            $topArtist = Artist::orderBy('played','DESC')
                            ->where('isVerified','1')
                            ->limit(10)->get();

            foreach($topArtist as $value)
            {
                $followers=explode(',',$value->followers);
                  
                $value->followers=$followers;
            }
            return response()->json([
                'success' => true,
                'topArtist'   =>$topArtist
            ], Response::HTTP_OK);
        
        
    } 

    public function follow_artist(Request $request)
    {
         
      $find=DB::table('artists')
            ->select('followers')
            ->where('id', '=',$request->artistID)
            ->first();
        if(!empty($find))
        {
            $users=array();
            if($find->followers == '')
            {
                $data['followers']=$request->userID;    
                $song=Artist::where('id',$request->artistID)->update($data);
                $message='User follow the artist Successfully';
            }
            else
            { 
                $users=explode(',',$find->followers);
                
                if(in_array($request->userID,$users))
                {
                   $message='Already followed artist by user';
                }
                else
                {
                $data['followers']=$find->followers.','.$request->userID;    
                $song=Artist::where('id',$request->artistID)->update($data);
                $message='User follow the artist Successfully';
                } 
            }

        }
        else
        {
             $message='The Artist is not available';
        }

                return response()->json([
                    'Message' => $message
                ], Response::HTTP_OK);
        
    }

    public function unfollow_artist(Request $request)
    {
       
      $find=DB::table('artists')
            ->select('followers')
            ->where('id', '=',$request->artistID)
            ->first();
        if(!empty($find))
        {
        $users=array();
        $users=explode(',',$find->followers);

          
        foreach (array_keys($users, $request->userID) as $key) {
            unset($users[$key]);
        }

        $users_liked['followers']=implode(',',$users); 
         $song=Artist::where('id',$request->artistID)->update($users_liked);
         $message='Unfollow Artist Successfully';
        }
        else
        {
            $message='Artist is not found';
        }
    
            return response()->json([
                'success' => true,
                'Message' => $message,
            ], Response::HTTP_OK);
        
    }
    public function get_all_follow_artist($id)
    {
       $data=DB::table('artists')
            ->select('*')
            ->where('isVerified','1')
            ->get();
       
      $all_artist=array();
      foreach($data as $value)
      { 
        
        $users=array();
        $users=explode(',',$value->followers);
        if($users == '')
        {
            $all_artist[]="";
        }
        else
        {
            if(in_array($id,$users))
            {
                $followers=explode(',',$value->followers);
                $value->followers=$followers;  
               $all_artist[]=$value;
            }
        }

      }
      return response()->json([
                'success' => true,
                'Message' => 'All artist followed by the user',
                'Data'   =>$all_artist
            ], Response::HTTP_OK);
           
    }

    public function featured_artist()
    {   
        $artist=Artist::where('isVerified','=','1')->get();
        $songs=array();
        $i=0;

        foreach($artist as $value)
        {
            $data= featuredArtists::where('artist_id','=',$value['id'])->get();
            if(!blank($data))
            {
                foreach($data as $Songvalue)
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
                
                  $songs[]=$song;
                  }
                }

                 $followers=explode(',',$value->followers);
              
                 $value->followers=$followers;

                    $response[]= array('Artist' => $value , 'Song' => $songs);

                    unset($songs);      
            } 
            
            $i++;
        }
           
        return response()->json([
                'success' => true,
                'Data'   =>$response
            ], Response::HTTP_OK);
    }


}
