<?php

namespace App\Http\Controllers\Admin;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Artist;
use App\Models\Song;
use App\Models\Album;
use App\Models\Category;
use App\Models\Emoji;
use App\Models\User;
use App\Models\songsRecord;
use App\Models\featuredArtists;
use App\Models\episodesRecord;
use App\Models\featuredPlaylists;
use App\Models\userPlaylist;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon\Carbon;
use DB;
use Artisan;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;


class SongController extends Controller
{

    public function index()
    {  
     if (Auth::user()->user_role == 'superAdmin'){
         $song = Song::orderBy('id','DESC')->paginate(10);
     }
     else{
         $song = Song::orderBy('id','DESC')->where('artist_id',Auth::user()->id)->paginate(10);
     }

     
        foreach($song as $value)
        { 
            $artist_id_data="";
            $language_id_data="";
            $category_id_data="";
            $album_id_data="";
            $artist=Artist::select('*')->where('id', $value['artist_id'])
                                 ->first();
            if(!empty($artist))
            {
                $value['artist_id']= $artist->name;
            }
            else
            {
                $value['artist_id']='';
            }
            $language=Language::select('*')->where('id', $value['language_id'])->first();
            if(!empty($language))
            {
               $value['language_id']=$language->name;   
            }
            else
            {
                $value['language_id']='';
            }          
            $category=Category::select('*')->where('id', $value['category_id'])->first();
            if(!empty($category))
            {
                $value['category_id']=$category->name;
            }
            else
            {
                $value['category_id']='';
            } 
            $album=Album::select('*')->where('id', $value['album'])->first();
            if(!empty($album))
            {
               $value['album']=$album->name;
            }
            else
            {
                $value['album']='';
            }
            
        }
        return view('admin.song.index',compact('song'));
    } 

    public function index_artist()
    {  
        $userId = Auth::id();

        $userartist_id=User::where('id',$userId)->first();
        $song = Song::where('artist_id','=',$userartist_id->artist_id)
                     ->paginate(10);
        

        foreach($song as $value)
        { 
            $artist_id_data="";
            $language_id_data="";
            $category_id_data="";
            $album_id_data="";
            $artist=Artist::select('*')->where('id', $value['artist_id'])
                                 ->first();
            if(!empty($artist))
            {
                $value['artist_id']= $artist->name;
            }
            else
            {
                $value['artist_id']='';
            }
            $language=Language::select('*')->where('id', $value['language_id'])->first();
            if(!empty($language))
            {
               $value['language_id']=$language->name;   
            }
            else
            {
                $value['language_id']='';
            }          
            $category=Category::select('*')->where('id', $value['category_id'])->first();
            if(!empty($category))
            {
                $value['category_id']=$category->name;
            }
            else
            {
                $value['category_id']='';
            } 
            $album=Album::select('*')->where('id', $value['album'])->first();
            if(!empty($album))
            {
               $value['album']=$album->name;
            }
            else
            {
                $value['album']='';
            } 
        }
        return view('admin.artistSong.index',compact('song'));
    } 
    
    public function indexSearch(Request $request)
    {   
        $validated = $request->validate([
            'search' => 'required'
        ]);

            $search=$request->search;
            $song =Song::select('*')
                ->where('id', '=', $request->search)
                ->first();

            $artist_id_data="";
            $language_id_data="";
            $category_id_data="";
            $album_id_data="";
            $artist=Artist::select('*')->where('id', $song['artist_id'])
                                 ->first();
            if(!empty($artist))
            {
                $song['artist_id']= $artist->name;
            }
            else
            {
                $song['artist_id']='';
            }
            if($song['language_id'] != '')
            { 
            $language=Language::select('*')->where('id', $song['language_id'])->first();
            }
            if(!empty($language))
            {
               $song['language_id']=$language->name;   
            }
            else
            {
                $song['language_id']='';
            }          
            $category=Category::select('*')->where('id', $song['category_id'])->first();
            if(!empty($category))
            {
                $song['category_id']=$category->name;
            }
            else
            {
                $song['category_id']='';
            } 
            $album=Album::select('*')->where('id', $song['album'])->first();
            if(!empty($album))
            {
               $song['album']=$album->name;
            }
            else
            {
                $song['album']='';
            } 
        
        return view('admin.song.indexSearch',compact('song'));
    } 
    public function indexSearchArtist(Request $request)
    {   
        $validated = $request->validate([
            'search' => 'required'
        ]);

        $user=ucfirst(auth()->user());
           $artist=json_decode($user);

           $artist_id= $artist->artist_id; 

            $search=$request->search;
            $song =Song::select('*')
                ->where('id', '=', $request->search)
                ->where('artist_id', '=', $artist_id)
                ->first();

            $artist_id_data="";
            $language_id_data="";
            $category_id_data="";
            $album_id_data="";
            if($song != '')
            {
                $artist=Artist::select('*')->where('id', $song['artist_id'])
                                     ->first();
                if(!empty($artist))
                {
                    $song['artist_id']= $artist->name;
                }
                else
                {
                    $song['artist_id']='';
                }
                if($song['language_id'] != '')
                { 
                $language=Language::select('*')->where('id', $song['language_id'])->first();
                }
                if(!empty($language))
                {
                   $song['language_id']=$language->name;   
                }
                else
                {
                    $song['language_id']='';
                }          
                $category=Category::select('*')->where('id', $song['category_id'])->first();
                if(!empty($category))
                {
                    $song['category_id']=$category->name;
                }
                else
                {
                    $song['category_id']='';
                } 
                $album=Album::select('*')->where('id', $song['album'])->first();
                if(!empty($album))
                {
                   $song['album']=$album->name;
                }
                else
                {
                    $song['album']='';
                } 
            }
            else
            {   
                $song['id']='';
                $song['name']='';
                $song['song']='';
                $song['artist_id']='';
                $song['category_id']='';
                $song['language_id']='';
                $song['album']='';
            }
        return view('admin.artistSong.indexSearch',compact('song'));
    }

    public function search_song(Request $request)
    {   
        $song =[];

        if($request->has('q')){
            $search = $request->q;
            $song =Song::select('*')
                ->where('name', 'LIKE', "%$search%")
                ->get();
        } 

       return response()->json($song);
    }
    public function search_song_artist(Request $request)
    {   
        $song =[];

                $user=ucfirst(auth()->user());
           $artist=json_decode($user);

           $artist_id= $artist->artist_id; 

        if($request->has('q')){
            $search = $request->q;
            $song =Song::select('*')
                ->where('name', 'LIKE', "%$search%")
                ->where('artist_id', '=',$artist_id)
                ->get();
        } 

       return response()->json($song);
    }
    public function multiple_song_store(Request $request)
    {
        $validated = $request->validate([
            'multiplesong' => 'required'
        ]);
        $durations= explode(',',$request->duration);
                if(isset($request->userID))
                { 
                    $artist= Artist::where('user_id',$request->userID)->first();
                    $data['artist_id'] = $artist->id;
                }

      if ($request->hasFile('multiplesong')) {
              
             $files=$request->file('multiplesong');
            $i=0;
            foreach($files as $file){
                
                $song = time().'_'.$file->getClientOriginalName();
                $song = str_replace(' ', '', $song);
                $file->move( storage_path('app/public/song/') , $song);
                $data['song'] = $song;
                $file_name=$file->getClientOriginalName();
                $temp= explode('.',$file_name);
                $Name =$temp[0] ;
                $Name = str_replace(' ', '', $Name);
                $data['name'] = $Name;
                $data['duration'] = $durations[$i];

                $songs = Song::create($data);
               //$file->move('song',$name);
                $i++;
            }
             
        }
     return redirect()->route('song')->with('message','Songs Added Successfully');
    }
    public function multiple_song_store_artist(Request $request ,$id)
    {
        $validated = $request->validate([
            'multiplesong' => 'required'
        ]);
        $durations= explode(',',$request->duration);
        $user= User::where('id',$id)
                     ->first();
        $artistID=$user->artist_id;

    if ($request->hasFile('multiplesong')) {

             $files=$request->file('multiplesong');
             $i=0;
            foreach($files as $file){

                $song = time().'_'.$file->getClientOriginalName();
                $file->move( storage_path('app/public/song/') , $song);
                $data['song'] = $song;
                $file_name=$file->getClientOriginalName();
                $temp= explode('.',$file_name);
                $Name =$temp[0] ;
                $data['name'] = $Name;
                $data['duration'] = $durations[$i];
                if(Auth::user()->user_role == "superAdmin" || Auth::user()->user_role =="manager"){
                    $data['artist_id']=$artistID; 
                }
                else{
                    $data['artist_id']=Auth::user()->id;
                }

                $songs = Song::create($data);
                $i++;
            }
             
        }

         
     return redirect()->route('song_artist_index')->with('message','Songs Added Successfully');
    }
    public function store(Request $request)
    {
        $data=$request->all();
        $validated = $request->validate([
            'name' => 'unique:songs|required',
            'song' => 'required_without:video',
            'video'=> 'required_without:song'
        ]);
        
        if ($request->hasFile('song')) {
            // $song = $request->song->getClientOriginalName();
            // $song = time().'_'.$song;
            // $song = str_replace(' ', '', $song);
            $song=$request->file('song');
            $song = time().'_'.$song->getClientOriginalName();
            $song = str_replace(' ', '', $song);
            $data['song'] = $song;
            $uploadsong=$request->file('song');
            $upload = $uploadsong->move(storage_path('app/public/song/') , $song);

        }
        else{
            $data['song'] ='';
        }

        if ($request->hasFile('video')) {
            $video = $request->video->getClientOriginalName();
            $video = time().'_'.$video; // Add current time before image name
            $video = str_replace(' ', '', $video);
            $data['video'] = $video;
            $upload = $request->file('video')->move(storage_path('app/public/video/') , $video);
        }
        else{
            $data['video'] ='';
        }

        if ($request->hasFile('song_image')) {
            $images = $request->song_image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['song_image'] = $images;
            $upload = $request->file('song_image')->move(storage_path('app/public/song/images'), $images);
        }
        else{
            $data['song_image'] ='';
        }
        
        $sel_artists=$request->featuring;
        if($sel_artists != '')
        {
        $all_feature= implode(',',$sel_artists);
           $data['featuring']=$all_feature;

        }
       $data['creator_id'] = Auth::user()->id;
        $songs = Song::create($data);

        if($sel_artists != '')
        {
        
            foreach($sel_artists as $artist)
            {   
                $featuredData['song_id']=$songs->id;
                $featuredData['artist_id']=$artist;
                featuredArtists::create($featuredData);
            }

        }
        

        $artist_id=$request->artist_id;  
        if($artist_id != '')
        {
            $artist=Artist::where('id',$artist_id)->first();
            if($artist->followers != '')
            {
                $followers=explode(',',$artist->followers);

                foreach($followers as $follow)
                {
                  $user =User::where('id',$follow)->first();
                  if(!empty($user))
                  {
                  $Notify= sendPushNotification('Hi'.$user->name.'!', $artist->name.' added new songs on AfroMelodies.',$user->fcm_token, $notiid=null);
                  }
                } 
            }
        }
        return redirect()->route('song')->with('message','Song uploaded Successfully','Data',$request->featuring);

    }
    public function store_artist(Request $request)
    {
        $data=$request->all();
        $validated = $request->validate([
            'name' => 'required',
            'song' => 'required_without:video',
            'video'=> 'required_without:song'
        ]);
        
        if ($request->hasFile('song')) {
            $song = $request->song->getClientOriginalName();
            $song = time().'_'.$song;
            $song = str_replace(' ', '', $song);
            $data['song'] = $song;
            $upload = $request->file('song')->move(storage_path('app/public/song/'), $song);

        }
        else{
            $data['song'] ='';
        }

        if ($request->hasFile('video')) {
            $video = $request->video->getClientOriginalName();
            $video = time().'_'.$video; // Add current time before image name
            $video = str_replace(' ', '', $video);
            $data['video'] = $video;
            $upload = $request->file('video')->move(storage_path('app/public/video/'), $video);
        }
        else{
            $data['video'] ='';
        }

        if ($request->hasFile('song_image')) {
            $images = $request->song_image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['song_image'] = $images;
            $upload = $request->file('song_image')->move(storage_path('app/song/images/'), $images);
        }
        else{
            $data['song_image'] ='';
        }
        $sel_artists=$request->featuring;
        if($sel_artists != '')
        {
        
            foreach($sel_artists as $artist)
            {   
                $featuredData['song_id']=$songs->id;
                $featuredData['artist_id']=$artist;
                featuredArtists::create($featuredData);
            }

        }

        $songs = Song::create($data);
        if($sel_artists != '')
        {
            foreach($sel_artists as $artist)
            {   
                $featuredData['song_id']=$songs->id;
                $featuredData['artist_id']=$artist;
                featuredArtists::create($featuredData);
            }
        }

        $artist_id=$request->artist_id;  
        if($artist_id != '')
        {
            $artist=Artist::where('id',$artist_id)->first();
            if($artist->followers != '')
            {
                $followers=explode(',',$artist->followers);

                foreach($followers as $follow)
                {
                  $user =User::where('id',$follow)->first();
                  if(!empty($user))
                  {
                  $Notify= sendPushNotification('Hi'.$user->name.'!', $artist->name.' added new songs on AfroMelodies.',$user->fcm_token, $notiid=null);
                   }
                } 
            }
        }
        return redirect()->route('song_artist_index')->with('message','Song Added Successfully');

    }
    public function get_new_admin_form()
    {
         return view('admin.users.add');
    }
    public function get_song_form()
    {
         return view('admin.song.add');
    }
    public function artist_song_form()
    {
         return view('admin.artistSong.add');
    }
    public function get_multiple_song_form()
    {
         return view('admin.MultipleSong.add');
    }
    public function artist_multiple_song_form()
    {
         return view('admin.MultipleSong.Artistadd');
    }
    public function selectSearch(Request $request)
    {
        $song = [];

        if($request->has('q')){
            $search = $request->q;
            $song =Artist::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($song);
    }

    public function langSearch(Request $request)
    {
        $song = [];

        if($request->has('q')){
            $search = $request->q;
            $song =Language::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($song);
    }
    public function catSearch(Request $request)
    {
        $song = [];

        if($request->has('q')){
            $search = $request->q;
            $song =Category::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($song);
    }

    public function albumSearch(Request $request)
    {
        $song = [];

        if($request->has('q')){
            $search = $request->q;
            $song =Album::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($song);
    }
    public function featureSearch(Request $request)
    {  
        $user_id =auth()->id();
         $user=User::findorfail($user_id);
        
        $artists=array();
        if($request->has('q')){
            $search = $request->q;
            $artists =Artist::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($artists);
    }
    public function song_form()
    {

        return view('admin.song.add');
    }
    public function edit_song_artist($id)
    {   

        $song_data=Song::find($id);

                        $artist_id_data="";
                        $language_id_data="";
                        $category_id_data="";
                        $album_id_data="";
                        $feature_artist_id_data="";
                        $names=array();

                        $all_featured=explode(',',$song_data['featuring']);
                        foreach($all_featured as $value)
                        { 
                            if($value != '')
                             { 
                            $feat_artist=Artist::select('*')->where('id', $value)
                                             ->first();
                           $names[]=$feat_artist->name;
                             }

                        }
                        if($names != '')
                        {
                        $feature_artist_id_data=implode(', ',$names);
                        }
                        $artist=Artist::select('*')->where('id', $song_data['artist_id'])
                                             ->get();
                        if($artist != "[]")
                        {
                            $artist_id_data= $artist[0]->name;
                        }
                        $language=Language::select('*')->where('id', $song_data['language_id'])->get();
                        if($language != "[]")
                        {
                        $language_id_data=$language[0]->name;   
                        }              
                        $category=Category::select('*')->where('id', $song_data['category_id'])->get();
                        if($category != "[]")
                        {
                        $category_id_data=$category[0]->name;
                        }
                        $album=Album::select('*')->where('id', $song_data['album'])->get();
                        if($album != "[]")
                        {
                        $album_id_data=$album[0]->name;
                        }
                           

        return view('admin.artistSong.edit',compact('song_data','artist_id_data','language_id_data','category_id_data','album_id_data','feature_artist_id_data'));
    }
    public function edit($id)
    {   

        $song_data=Song::find($id);

                        $artist_id_data="";
                        $language_id_data="";
                        $category_id_data="";
                        $album_id_data="";
                        $feature_artist_id_data="";
                        $names=array();

                        $all_featured=explode(',',$song_data['featuring']);
                        foreach($all_featured as $value)
                        { 
                            if($value != '')
                             { 
                            $feat_artist=Artist::select('*')->where('id', $value)
                                             ->first();
                           $names[]=$feat_artist->name;
                             }

                        }
                        if($names != '')
                        {
                        $feature_artist_id_data=implode(', ',$names);
                        }
                        $artist=Artist::select('*')->where('id', $song_data['artist_id'])
                                             ->get();
                        if($artist != "[]")
                        {
                            $artist_id_data= $artist[0]->name;
                        }
                        $language=Language::select('*')->where('id', $song_data['language_id'])->get();
                        if($language != "[]")
                        {
                        $language_id_data=$language[0]->name;   
                        }              
                        $category=Category::select('*')->where('id', $song_data['category_id'])->get();
                        if($category != "[]")
                        {
                        $category_id_data=$category[0]->name;
                        }
                        $album=Album::select('*')->where('id', $song_data['album'])->get();
                        if($album != "[]")
                        {
                        $album_id_data=$album[0]->name;
                        }
                           

        return view('admin.song.edit',compact('song_data','artist_id_data','language_id_data','category_id_data','album_id_data','feature_artist_id_data'));
    }

    public function monthly_listeners(){
     
         $data =songsRecord::select('users.*')->join('users','users.id','=','songs_records.user_id')->whereBetween('songs_records.created_at',[Carbon::now()->subDays(30),Carbon::now()])->groupBy('songs_records.user_id')->paginate(5);

        //print_r($data);die('here');
        return view('admin.users.monthly-listing',compact('data'));

    }

    public function update(Request $request ,$id)
    {    
        $data = request()->except(['_token']);

         $validated = $request->validate([
            'name' => 'required',
            'song_image' => 'mimes:jpeg,jpg,png,gif'
        ]); 

        $old_data=Song::where('id',$id)->first();

        if ($request->hasFile('song')) {
            if($old_data->song != '')
            { 
                if (file_exists('app/public/song/'.$old_data->song)) {
                   unlink(storage_path('app/public/song/'.$old_data->song));
             }
            }
            $song = $request->song->getClientOriginalName();
            $song = time().'_'.$song;
            $song = str_replace(' ', '', $song);
            $data['song'] = $song;
            $upload = $request->file('song')->move(storage_path('app/public/song/'), $song);
        }
        
        if ($request->hasFile('video')) {
            if($old_data->video != '')
            { if (file_exists('app/public/video/'.$old_data->video)) {
                    unlink(storage_path('app/public/video/'.$old_data->video));
                }
            }
            $video = $request->video->getClientOriginalName();
            $video = time().'_'.$video; // Add current time before image name
            $video = str_replace(' ', '', $video);
            $data['video'] = $video;
            $upload = $request->file('video')->move(storage_path('app/public/video/'), $video);
        }

        if ($request->hasFile('song_image')) {
            if($old_data->song_image != '')
            {  if (file_exists('app/public/song/images/'.$old_data->song_image)) {
               unlink(storage_path('app/public/song/images/'.$old_data->song_image));
              }
            }
            $images = $request->song_image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['song_image'] = $images;
            $upload = $request->file('song_image')->move(storage_path('app/public/song/images/'), $images);
        }

        $sel_artists=$request->featuring;
        if($sel_artists != '')
        {
        $all_feature= implode(',',$sel_artists);
        $data['featuring']=$all_feature;
        }
        $song=Song::where('id',$id)->update($data);

        if($sel_artists != '')
        {
           $delete=featuredArtists::where('song_id','=',$id)->delete();
           foreach($sel_artists as $artist)
           {
            $featuredData['song_id']=$id;
            $featuredData['artist_id']=$artist;
            $added= featuredArtists::create($featuredData);
           }
        }

                $artist_id=$request->artist_id;  
        if($artist_id != '')
        {
            $artist=Artist::where('id',$artist_id)->first();
            if($artist->followers != '')
            {
                $followers=explode(',',$artist->followers);

                foreach($followers as $follow)
                {
                  $user =User::where('id',$follow)->first();
                  if(!empty($user))
                  {
                  $Notify= sendPushNotification('Hi'.$user->name.'!', $artist->name.' added new songs on AfroMelodies.',$user->fcm_token, $notiid=null);
                  }
                } 
            }
        }

        
        return redirect()->route('song')->with('message','Song Updated Successfully');
    }

    public function update_song_artist(Request $request ,$id)
    {
        $data = request()->except(['_token']);

         $validated = $request->validate([
            'song_image' => 'mimes:jpeg,jpg,png,gif'
        ]); 

        $old_data=Song::where('id',$id)->first();

        if ($request->hasFile('song')) {
            if($old_data->song != '')
            { 
                if (file_exists('app/public/song/'.$old_data->song)) {
                    unlink(storage_path('app/public/song/'.$old_data->song));
                }
            }
            $song = $request->song->getClientOriginalName();
            $song = time().'_'.$song;
            $song = str_replace(' ', '', $song);
            $data['song'] = $song;
            $upload = $request->file('song')->move(storage_path('app/public/song/'), $song);
        }
        
        if ($request->hasFile('video')) {
            if($old_data->video != '')
            {  
                if (file_exists('app/public/video/'.$old_data->video)) {
                 unlink(storage_path('app/public/video/'.$old_data->video));
             }
            }
            $video = $request->video->getClientOriginalName();
            $video = time().'_'.$video; // Add current time before image name
            $video = str_replace(' ', '', $video);
            $data['video'] = $video;
            $upload = $request->file('video')->move(storage_path('app/public/video/'), $video);
        }

        if ($request->hasFile('song_image')) {
            if($old_data->song_image != '')
            { if (file_exists('app/public/song/images/'.$old_data->song_image)) {
            unlink(storage_path('app/public/song/images/'.$old_data->song_image));
              }
            }
            $images = $request->song_image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['song_image'] = $images;
            $upload = $request->file('song_image')->move(storage_path('app/public/song/images/'), $images);
        }

        $sel_artists=$request->featuring;
        if($sel_artists != '')
        {
        $all_feature= implode(',',$sel_artists);
        $data['featuring']=$all_feature;
        }
        $song=Song::where('id',$id)->update($data);

        if($sel_artists != '')
        {
           $delete=featuredArtists::where('song_id','=',$id)->delete();
           foreach($sel_artists as $artist)
           {
            $featuredData['song_id']=$id;
            $featuredData['artist_id']=$artist;
            $added= featuredArtists::create($featuredData);
           }
        }

        $artist_id=$request->artist_id;  
        if($artist_id != '')
        {
            $artist=Artist::where('id',$artist_id)->first();
            if($artist->followers != '')
            {
                $followers=explode(',',$artist->followers);

                foreach($followers as $follow)
                {
                  $user =User::where('id',$follow)->first();
                  if(!empty($user))
                  {
                  $Notify= sendPushNotification('Hi'.$user->name.'!', $artist->name.' added new songs on AfroMelodies.',$user->fcm_token, $notiid=null);
                  }
                } 
            }
        }

        return redirect()->route('song_artist_index')->with('message','Song Updated Successfully');
    }
	
    public function delete($id)
    {
        $data=Song::find($id);

        if($data->song != '')
        {   
            if (file_exists('app/public/song/'.$data->song)) {
            unlink(storage_path('app/public/song/'.$data->song));
             }
        }
        if($data->video != '')
        {  if (file_exists('app/public/video/'.$data->video)) {
            unlink(storage_path('app/public/video/'.$data->video));
           }
        }
        if($data->song_image != '')
        {  if (file_exists('app/public/song/images/'.$data->song_image)) {
            unlink(storage_path('app/public/song/images/'.$data->song_image));
            }
        }
        $songs=[];
        $content=[];
        $playlists=featuredPlaylists::get();

        foreach($playlists as $playlist)
        {
           $songs=explode(',',$playlist->song);
            
             foreach($songs as $key => $value)
             { 
               if($value == $id)
               {
                   unset($songs[$key]);
               }
            }
             
            $content['song']=implode(',',$songs);

            $Updateplaylists=featuredPlaylists::where('id',$playlist['id'])->update($content);
        }

       $featured_artist_delete= featuredArtists::where('song_id',$id)->delete();

       $user_playlist=userPlaylist::get();

       foreach($user_playlist as $playlistValue)
       {
          $Usersongs=explode(',',$playlistValue->song_ID);
           
            foreach($Usersongs as $key => $value)
            { 
              if($value == $id)
              {
                  unset($Usersongs[$key]);
              }
           }
            
           $contentSong['song_ID']=implode(',',$Usersongs);

           $Updateuserplaylists=userPlaylist::where('id',$playlistValue['id'])->update($contentSong);
       }

        $record_delete=songsRecord::where('song_id',$id)->delete();

        $data->delete();


        return redirect()->route('song')->with('message','Song Deleted Successfully');
    }

    public function delete_song_artist($id)
    {
        $data=Song::find($id);

        if($data->song != '')
        {   
            if (file_exists('app/public/song/'.$data->song)) {
            unlink(storage_path('app/public/song/'.$data->song));
             }
        }
        if($data->video != '')
        {  if (file_exists('app/public/video/'.$data->video)) {
            unlink(storage_path('app/public/video/'.$data->video));
           }
        }
        if($data->song_image != '')
        {  if (file_exists('app/public/song/images/'.$data->song_image)) {
            unlink(storage_path('app/public/song/images/'.$data->song_image));
            }
        }

        $songs=[];
        $content=[];
        $playlists=featuredPlaylists::get();

        foreach($playlists as $playlist)
        {
           $songs=explode(',',$playlist->song);
            
             foreach($songs as $key => $value)
             { 
               if($value == $id)
               {
                   unset($songs[$key]);
               }
            }
             
            $content['song']=implode(',',$songs);

            $Updateplaylists=featuredPlaylists::where('id',$playlist['id'])->update($content);
        }

       $featured_artist_delete= featuredArtists::where('song_id',$id)->delete();

       $user_playlist=userPlaylist::get();

       foreach($user_playlist as $playlistValue)
       {
          $Usersongs=explode(',',$playlistValue->song_ID);
           
            foreach($Usersongs as $key => $value)
            { 
              if($value == $id)
              {
                  unset($Usersongs[$key]);
              }
           }
            
           $contentSong['song_ID']=implode(',',$Usersongs);

           $Updateuserplaylists=userPlaylist::where('id',$playlistValue['id'])->update($contentSong);
       }
        $record_delete=songsRecord::where('song_id',$id)->delete();

        $data->delete();


        return redirect()->route('song_artist_index')->with('message','Song Deleted Successfully');
    }
    
    public function delete_song_record_data()
    {
        $delete= songsRecord::where( 'created_at', '<=', Carbon::now()->subDays(30))->delete();
    }

    public function mostlistenedSong()
    {
        $song=Song::where('played' ,'>','100')->paginate(10);
        

        return view('admin.song.index',compact('song'));

    }
    public function CurrentListenersUsers()
    {
        Artisan::call('cache:clear');

        $users= songsRecord::leftJoin('users','users.id','=','songs_records.user_id')
          ->select('users.*')
          ->where('songs_records.updated_at','>',Carbon::now()->subMinutes(5)->toDateTimeString())
          ->distinct()
         ->paginate(10);


        return view('admin.listeners.index',compact('users'));
    }
   public function song_status(Request $request){
        $song=Song::find($request->song_id);
        $song->status=$request->status;
        $song->save();
        return response()->json(['message'=>'Status Change']);
   }

}
