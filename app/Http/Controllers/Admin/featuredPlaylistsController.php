<?php

namespace App\Http\Controllers\Admin;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\User;
use App\Models\Song;
use Illuminate\Support\Facades\Auth;
use App\Models\featuredPlaylists;
use Thumbnail;
use Carbon;
use DB;
use Artisan;
use Illuminate\Support\Str;

class featuredPlaylistsController extends Controller
{ 

    /*
    |--------------------------------------------------------------------------
    | Betting Form Page In admin Panel
    |--------------------------------------------------------------------------
    */
    

    public function index()
    {



       
        $role= auth()->user()->user_role;
        if($role == "superAdmin")
            {
                $featured = featuredPlaylists::paginate(10);
              return view('admin.featured.index',compact('featured'));
            }
            else
            {
                $featured = featuredPlaylists::paginate(10)->where('artist_id',Auth::user()->id);
              return view('admin.featured.index',compact('featured'));
            }

       
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
            $upload = $request->file('image')->move(storage_path('app/public/featured/'), $images);
        }
        else{
            $data['image'] ='';
        }

$data['creator_id'] = Auth::user()->id;
        $Album = featuredPlaylists::create($data);
            
            
              return redirect()->route('featuredlist')->with('message','featured Added Successfully');

    }
    public function get_featured_form()
    {
        $role= auth()->user()->user_role;
        if($role == "admin")
            {   
        return view('admin.Artistfeatured.add');
         }
         else
         {
            return view('admin.featured.add');
         }
    }
    public function edit($id)
    {
          $featured=featuredPlaylists::where('id',$id)->first();
        if($featured != '')
        {
        $songs=explode(',',$featured->song);
        $song_data=array();
        foreach($songs as $song)
        {  
            
            $songselected=Song::select('*')->where('id','=',$song)->first();
            if(!empty($songselected))
            { 
               $song_data[]=$songselected;
            }
        } 
        }
        //$featid=$id;
        $role= auth()->user()->user_role;

        if($role == "admin")
            { 
             return view('admin.Artistfeatured.edit',compact('featured','song_data'));
            }
            else
            {
              return view('admin.featured.edit',compact('featured','song_data'));
            } 
       
    }
    public function update(Request $request)
    {   
        $data = request()->except(['_token']);
        $old_data=featuredPlaylists::where('id',$request->id)->first();

        if ($request->hasFile('image')) {
            if($old_data->image != '')
            { 
                if (file_exists('app/public/featured/'.$old_data->image)) {
                     unlink(storage_path('app/public/featured/'.$old_data->image));
                }
            }
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/featured/'), $images);
        }

        $featured=featuredPlaylists::where('id',$request->id)->first();

        $data['song']=$featured->song.','.$request->song;

          $Updated=featuredPlaylists::where('id',$request->id)->update($data);

          $featured=$featured->song;
          
        return redirect()->route('featuredlist')->with('message','Featured Updated Successfully');
    }
    public function delete($id)
    {
        $data=featuredPlaylists::find($id);
        if($data->image != '')
        { 
            if (file_exists('app/public/featured/'.$data->image)) {
                  unlink(storage_path('app/public/featured/'.$data->image));
               }
        }
        $data->delete();
   
        return redirect()->route('featuredlist')->with('message','featured Deleted Successfully');
    }
    
    public function delete_episode_song($id,$fid)
    {
        $featured=featuredPlaylists::find($fid);

        
        $songs=explode(',',$featured->song);

         $i=0;
        foreach($songs as $song)
        {   

            if($song === $id)
            {
                unset($songs[$i]);
            }
            $i++;
        }

        $new_songs['song']= implode(',',$songs);
         $updated=featuredPlaylists::where('id',$fid)->update($new_songs);

       return back()->with('message','Song removed from the playlist');
    }
}
