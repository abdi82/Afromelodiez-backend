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
use App\Models\podcastsEpisodes;
use App\Models\podcast;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;


class EpisodesPodcastController extends Controller
{

    public function index()
    {  


        if(Auth::user()->user_role== "superAdmin" || Auth::user()->user_role== "mananger"){
            $episode = podcastsEpisodes::paginate(10);
        }
        else{
            $episode = podcastsEpisodes::where('creator_id',Auth::user()->id)->paginate(10);
        }
        foreach($episode as $value)
        { 
            $podcast_name=podcast::select('*')
                    ->where('id', '=', $value->podcast )
                    ->first();      
              if(!empty($podcast_name))
              {
                $value->podcast=$podcast_name->name;              
              }
        }
        return view('admin.podcastEpisodes.index',compact('episode'));
    } 
    
    public function indexSearch(Request $request)
    {   
        

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
    public function multiple_episodes_store(Request $request)
    {

        $validated = $request->validate([
            'multipleEpisodes' => 'required'
        ]);
        
      if ($request->hasFile('multipleEpisodes')) {

             $files=$request->file('multipleEpisodes');
            foreach($files as $file){

                $episode = time().'_'.$file->getClientOriginalName();
                $episode = str_replace(' ', '', $episode);
                $file->move( storage_path('app/public/podcast/episodes') , $episode);
                $data['episode'] = $episode;
                $file_name=$file->getClientOriginalName();
                $temp= explode('.',$file_name);
                $Name =$temp[0] ;
                $data['episode'] = $Name;
                $data['creator_id'] = Auth::user()->id;
                $songs = podcastsEpisodes::create($data);
               //$file->move('song',$name);
            }
             
        }
     return redirect()->route('episodeslist')->with('message','Episodes Added Successfully');
    }
    public function store(Request $request)
    {
        $data=$request->all();
        $validated = $request->validate([
            'title' => 'required',
            'episode' => 'required'
        ]);
        
        if ($request->hasFile('episode')) {
            $episode = $request->episode->getClientOriginalName();
            $episode = time().'_'.$episode; // Add current time before image name
            $episode = str_replace(' ', '', $episode);
            $data['episode'] = $episode;
            $upload = $request->file('episode')->move(storage_path('app/public/podcast/episodes/'), $episode);
        }
        else{
            $data['episode'] ='';
        }
        $data['creator_id'] = Auth::user()->id;
        if ($request->hasFile('image')) {
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/podcast/episodes/images/'), $images);
        }
        else{
            $data['image'] ='';
        }

        if($request->podcast != '')
        {
           $podcast= podcast::where('id',$request->podcast)->first();
           $data['artist_id']=$podcast->artist_id;
        }
        $songs = podcastsEpisodes::create($data);

        return redirect()->route('episodeslist')->with('message','Episode Added Successfully');

    }
    public function get_episode_form()
    {
         return view('admin.podcastEpisodes.add');
    }
    public function get_multiple_episodes_form()
    {
         return view('admin.MultipleEpisodes.add');
    }
    public function selectSearch(Request $request)
    {
        $song = [];

        if($request->has('q')){
            $search = $request->q;
            $song =podcast::select("id", "name")
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
    public function song_form()
    {

        return view('admin.song.add');
    }
    public function edit($id)
    {   
         $podcast='';
        $episode=podcastsEpisodes::find($id); 

        $podcast_name=podcast::select('*')
                ->where('id', '=', $episode->podcast )
                ->first();      
          if(!empty($podcast_name))
          {
            $podcast=$podcast_name->name;              
          }
        return view('admin.podcastEpisodes.edit',compact('episode','podcast'));
    }
    public function update(Request $request ,$id)
    {
        $data = request()->except(['_token']);
        $old_data=podcastsEpisodes::where('id',$id)->first();

        if ($request->hasFile('episode')) {
            if($old_data->episode != '')
            { 
                if (file_exists('app/public/podcast/episodes/'.$old_data->episode)) {
                 unlink(storage_path('app/public/podcast/episodes/'.$old_data->episode));
              }
            }
            $episode = $request->episode->getClientOriginalName();
            $episode = time().'_'.$episode; // Add current time before image name
            $episode = str_replace(' ', '', $episode);
            $data['episode'] = $episode;
            $upload = $request->file('episode')->move(storage_path('app/public/podcast/episodes'), $episode);
        }

        if ($request->hasFile('image')) {
            if($old_data->image != '')
            { if (file_exists('app/public/podcast/episodes/images/'.$old_data->image)) {
                 unlink(storage_path('app/public/podcast/episodes/images/'.$old_data->image));
              }
            }
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('song_image')->move(storage_path('app/public/podcast/episodes/images/'), $images);
        }

        if($request->podcast != '')
        {
           $podcast= podcast::where('id',$request->podcast)->first();
           $data['artist_id']=$podcast->artist_id;
        }

        $song=podcastsEpisodes::where('id',$id)->update($data);

        

        return redirect()->route('episodeslist')->with('message','Episode Updated Successfully');
    }
	
    public function delete($id)
    {
        $data=podcastsEpisodes::find($id);
        if($data->song_image != '')
        {    
            if (file_exists('app/public/podcast/episodes/images/'.$data->song_image)) {
            unlink(storage_path('app/public/podcast/episodes/images/'.$data->song_image));
           }
        }
        if($data->episode != '')
        {  
            if (file_exists('app/public/podcast/episodes/'.$data->episode)) {
                unlink(storage_path('app/public/podcast/episodes/'.$data->episode));
             }
        }
        $data->delete();

        return redirect()->route('episodeslist')->with('message','Episode Deleted Successfully');
    }
    
    // artist 

    public function index_artist()
    {  

        $userId = Auth::id();

        $userartist_id=User::where('id',$userId)->first();
        $episode = podcastsEpisodes::where('artist_id','=',$userartist_id->artist_id)
                     ->paginate(10);
           
        foreach($episode as $value)
        { 
            $podcast_name=podcast::select('*')
                    ->where('id', '=', $value->podcast )
                    ->first();      
              if(!empty($podcast_name))
              {
                $value->podcast=$podcast_name->name;              
              }
        }
        return view('admin.artistPodcastEpisodes.index',compact('episode'));
    } 
    
   
    public function multiple_episodes_store_artist(Request $request)
    {
        
        $validated = $request->validate([
            'multipleEpisodes' => 'required'
        ]);
        
      if ($request->hasFile('multipleEpisodes')) {

             $files=$request->file('multipleEpisodes');
            foreach($files as $file){

                $episode = time().'_'.$file->getClientOriginalName();
                $episode = str_replace(' ', '', $episode);
                $file->move( storage_path('app/public/podcast/episodes') , $episode);
                $data['episode'] = $episode;
                $file_name=$file->getClientOriginalName();
                $temp= explode('.',$file_name);
                $Name =$temp[0] ;
                $data['episode'] = $Name;
                $data['artist_id'] = $request->artist_id;
                $songs = podcastsEpisodes::create($data);
               //$file->move('song',$name);
            }
             
        }
     return redirect()->route('episodeslist_artist')->with('message','Episodes Added Successfully');
    }
    public function store_artist(Request $request)
    {
        $data=$request->all();
        $validated = $request->validate([
            'title' => 'required',
            'episode' => 'required'
        ]);
        
        if ($request->hasFile('episode')) {
            $episode = $request->episode->getClientOriginalName();
            $episode = time().'_'.$episode; // Add current time before image name
            $episode = str_replace(' ', '', $episode);
            $data['episode'] = $episode;
            $upload = $request->file('episode')->move(storage_path('app/public/podcast/episodes/'), $episode);
        }
        else{
            $data['episode'] ='';
        }

        if ($request->hasFile('image')) {
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/podcast/episodes/images/'), $images);
        }
        else{
            $data['image'] ='';
        }
        $songs = podcastsEpisodes::create($data);

        return redirect()->route('episodeslist_artist')->with('message','Episode Added Successfully');

    }
    public function get_episode_form_artist()
    {
         return view('admin.artistPodcastEpisodes.add');
    }
    public function get_multiple_episodes_form_artist()
    {
         return view('admin.ArtistMultipleEpisodes.add');
    }
    public function selectSearch_artist(Request $request)
    {
        $song = [];

        if($request->has('q')){
            $search = $request->q;
            $song =podcast::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($song);
    }

    public function langSearch_artist(Request $request)
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
    public function catSearch_artist(Request $request)
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

    public function albumSearch_artist(Request $request)
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
    public function song_form_artist()
    {

        return view('admin.song.add');
    }
    public function edit_artist($id)
    {   
         $podcast='';
        $episode=podcastsEpisodes::find($id); 

        $podcast_name=podcast::select('*')
                ->where('id', '=', $episode->podcast )
                ->first();      
          if(!empty($podcast_name))
          {
            $podcast=$podcast_name->name;              
          }
        return view('admin.artistPodcastEpisodes.edit',compact('episode','podcast'));
    }
    public function update_artist(Request $request ,$id)
    {
        $data = request()->except(['_token']);
        $old_data=podcastsEpisodes::where('id',$id)->first();

        if ($request->hasFile('episode')) {
            if($old_data->episode != '')
            { 
                 if (file_exists('app/public/podcast/episodes/'.$old_data->episode)) {
                    unlink(storage_path('app/public/podcast/episodes/'.$old_data->episode));
                 }
            }
            $episode = $request->episode->getClientOriginalName();
            $episode = time().'_'.$episode; // Add current time before image name
            $episode = str_replace(' ', '', $episode);
            $data['episode'] = $episode;
            $upload = $request->file('episode')->move(storage_path('app/public/podcast/episodes'), $episode);
        }

        if ($request->hasFile('image')) {
            if($old_data->image != '')
            { if (file_exists('app/public/podcast/episodes/images/'.$old_data->image)) {
                unlink(storage_path('app/public/podcast/episodes/images/'.$old_data->image));
             }
            }
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('song_image')->move(storage_path('app/public/podcast/episodes/images'), $images);
        }

        $song=podcastsEpisodes::where('id',$id)->update($data);

        

        return redirect()->route('episodeslist')->with('message','Episode Updated Successfully');
    }
    
    public function delete_artist($id)
    {
        $data=podcastsEpisodes::find($id);
        if($data->song_image != '')
        {   if (file_exists('app/public/podcast/episodes/images/'.$data->song_image)) {
            unlink(storage_path('app/public/podcast/episodes/images/'.$data->song_image));
           }
        }
        if($data->episode != '')
        {  if (file_exists('app/public/podcast/episodes/'.$data->episode)) {
            unlink(storage_path('app/public/podcast/episodes/'.$data->episode));
            }
        }
        $data->delete();

        return redirect()->route('episodeslist')->with('message','Episode Deleted Successfully');
    }
     


}
