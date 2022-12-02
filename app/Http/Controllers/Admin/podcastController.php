<?php

namespace App\Http\Controllers\Admin;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\advertisements;
use App\Models\BetReport;
use App\Models\Category;
use App\Models\episodesRecord;
use App\Models\Emoji;
use App\Models\User;
use App\Models\Artist;
use App\Models\podcast;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon\Carbon;
use DB;
use Artisan;
use Illuminate\Support\Str;

class podcastController extends Controller
{ 

    /*
    |--------------------------------------------------------------------------
    | Betting Form Page In admin Panel
    |--------------------------------------------------------------------------
    */

    public function index()
    {
       
        $podcast = podcast::paginate(10);

        return view('admin.podcast.index',compact('podcast'));
    }

    public function get_podcast_create_form()
    {
        return view('admin.podcast.add');
    }
    public function store(Request $request)
    {
        $data=$request->all();
        $validated = $request->validate([
            'name' => 'required'
        ]);

        if ($request->hasFile('image')) {
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/podcast/'), $images);
        }
        else{
            $data['image'] ='';
        }
        $data['creator_id'] = Auth::user()->id;

        $podcast = podcast::create($data);

        return redirect()->route('podcastlist')->with('message','Podcast Added Successfully');

    }
    public function edit($id)
    {
        $podcast=podcast::find($id);
        $artist_id_data="";
        $artist=Artist::select('*')->where('id', $podcast['artist_id'])
                                             ->get();
                        if($artist != "[]")
                        {
                            $artist_id_data= $artist[0]->name;
                        }

        return view('admin.podcast.edit',compact('podcast','artist_id_data'));
    }
    public function episodeslist()
    {
         $episode = podcast::paginate(10);

        return view('admin.podcastEpisodes.index',compact('episode'));
    }
    public function update(Request $request ,$id)
    {   
        $data = request()->except(['_token']);

        $old_data=podcast::where('id',$id)->first();

        if ($request->hasFile('image')) {
            if($old_data->image != '')
            {  
                if (file_exists('app/public/podcast/'.$old_data->image)) {
                 unlink(storage_path('app/public/podcast/'.$old_data->image));
              }
            }
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/podcast/'), $images);
        }

        $artist=podcast::where('id',$id)->update($data);
          
        return redirect()->route('podcastlist')->with('message','Podcast Updated Successfully');
    }
    public function delete($id)
    {
        $data=podcast::find($id);
        if($data->image != '')
        {   if (file_exists('app/public/podcast/'.$data->image)) {
            unlink(storage_path('app/public/podcast/'.$data->image));
             }
        }
        $data->delete();

        return redirect()->route('podcastlist')->with('message','Podcast Deleted Successfully');
    }
    public function store_episodes(Request $request)
    {
        $data=$request->all();

        $validated = $request->validate([
            'name' => 'image'
        ]);


        if ($request->hasFile('image')) {
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/podcast/'), $images);
        }
        else{
            $data['image'] ='';
        }


        $podcast = podcast::create($data);

        return redirect()->route('podcastlist')->with('message','Podcast Added Successfully');

    }

    //artist 

    public function index_artist()
    {
        $userId = Auth::id();

        $userartist_id=User::where('id',$userId)->first();
        $podcast = podcast::where('artist_id','=',$userartist_id->artist_id)
                     ->paginate(10);

        return view('admin.artistPodcast.index',compact('podcast'));
    }

    public function get_podcast_create_form_artist()
    {
        return view('admin.artistPodcast.add');
    }
    public function store_artist(Request $request)
    {   
        $data=$request->all();
        $validated = $request->validate([
            'name' => 'required'
        ]);

        if ($request->hasFile('image')) {
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/podcast/'), $images);
        }
        else{
            $data['image'] ='';
        }

        $podcast = podcast::create($data);

        return redirect()->route('podcastlist_artist')->with('message','Podcast Added Successfully');

    }
    public function edit_artist($id)
    {
        $podcast=podcast::find($id);
        $artist_id_data="";
        $artist=Artist::select('*')->where('id', $podcast['artist_id'])
                                             ->get();
                        if($artist != "[]")
                        {
                            $artist_id_data= $artist[0]->name;
                        }

        return view('admin.artistPodcast.edit',compact('podcast','artist_id_data'));
    }
    public function episodeslist_artist()
    {
         $episode = podcast::paginate(10);

        return view('admin.artistPodcastEpisodes.index',compact('episode'));
    }
    public function update_artist(Request $request ,$id)
    {   
        $data = request()->except(['_token']);

        $old_data=podcast::where('id',$id)->first();

        if ($request->hasFile('image')) {
            if($old_data->image != '')
            {  if (file_exists('app/public/podcast/'.$old_data->image)) {
            unlink(storage_path('app/public/podcast/'.$old_data->image));
             }
            }
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/podcast/'), $images);
        }

        $artist=podcast::where('id',$id)->update($data);
          
        return redirect()->route('podcastlist_artist')->with('message','Podcast Updated Successfully');
    }
    public function delete_artist($id)
    {
        $data=podcast::find($id);
        if($data->image != '')
        {   if (file_exists('app/public/podcast/'.$data->image)) {
            unlink(storage_path('app/public/podcast/'.$data->image));
              }
        }
        $data->delete();

        return redirect()->route('podcastlist_artist')->with('message','Podcast Deleted Successfully');
    }
    public function store_episodes_artist(Request $request)
    {
        $data=$request->all();

        $validated = $request->validate([
            'name' => 'image'
        ]);


        if ($request->hasFile('image')) {
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/podcast/'), $images);
        }
        else{
            $data['image'] ='';
        }


        $podcast = podcast::create($data);

        return redirect()->route('podcastlist_artist')->with('message','Podcast Added Successfully');

    }

    public function PodcastCurrentListeners()
    {
        $episodesRecord=episodesRecord::distinct()->select('id')->where('updated_at', '>', Carbon::now()->subMinutes(5)->toDateTimeString())->groupBy('user_id')->get();
       

        $users= episodesRecord::leftJoin('users','users.id','=','episodes_records.user_id')
          ->select('users.*')
          ->where('episodes_records.updated_at','>',Carbon::now()->subMinutes(5)->toDateTimeString())
          ->distinct()
         ->paginate(10);

        return view('admin.listeners.index',compact('users','episodesRecord'));
    }
   

}
