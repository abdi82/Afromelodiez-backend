<?php

namespace App\Http\Controllers\Admin;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\advertisements;
use App\Models\BetReport;
use App\Models\Category;
use App\Models\Emoji;
use App\Models\User;
use App\Models\continent;
use App\Models\AdRecord;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;
use Illuminate\Support\Str;

class AdController extends Controller
{ 

    /*
    |--------------------------------------------------------------------------
    | Betting Form Page In admin Panel
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $ad = advertisements::paginate(10);

        // foreach($ad as $id)
        // {

        //     $AdRecord=AdRecord::where('ad_id',$id)->get();
        //     $sum=0;

        //     foreach($AdRecord as $value)
        //     {
        //         $sum=$sum+$value['played'];
                 
                
        //     }
        //     $id->title=$sum;
        // }

        return view('admin.Ad.index',compact('ad'));
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'attachment' => 'required',
            'banner_type' => 'required'
        ]);

        $data['url']=$request->url;
        $data['location_type']=$request->location_type;
        $data['type']=$request->type;
        $data['banner_type']=$request->banner_type;

        if ($request->hasFile('attachment')) {
            $images = $request->attachment->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['attachment'] = $images;
            $upload = $request->file('attachment')->move(storage_path('app/public/ad/'), $images);
        }
        else{
            $data['attachment'] ='';
        }


        $ad = advertisements::create($data);   
       
       $ad_id=$ad->id;
        if($request->location_type != '')
        { 
            $continent=$request->location_type;

                $country_data['name']=$continent;
                $country_data['ad_id']=$ad_id;
                 
                $ad = continent::create($country_data);
            
        } 

        return redirect()->route('adlist')->with('message','Advertisement Added Successfully');

    }
    public function get_ad_form()
    {

        return view('admin.Ad.add');
    }
    public function edit($id)
    {
          $ad=advertisements::find($id);

        $AdRecord=AdRecord::where('ad_id',$id)->get();
        $sum=0;

        foreach($AdRecord as $value)
        {
            $sum=$sum+$value['played'];

        }

        return view('admin.Ad.edit',compact('ad','sum'));
    }
    public function update(Request $request ,$id)
    {   
        $data = request()->except(['_token']);

        $old_data=advertisements::where('id',$id)->first();
        $data['type']=$request->type;

        if  ($request->hasFile('attachment')) {
            if($old_data->attachment != '')
            { 
                if (file_exists('app/public/ad/'.$old_data->attachment)) {
                     unlink(storage_path('app/public/ad/'.$old_data->attachment));
               }
            }
            $images = $request->attachment->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['attachment'] = $images;
            $upload = $request->file('attachment')->move(storage_path('app/public/ad/'), $images);
        }

          $artist=advertisements::where('id',$id)->update($data);

        $ad_id=$id;
        if($request->location_type != '')
        { 
            $continent=$request->location_type;

                $country_data['name']=$continent;
                $country_data['ad_id']=$ad_id;
                 
                $ad = continent::create($country_data);
            
        } 
          
        return redirect()->route('adlist')->with('message','Advertisement Updated Successfully');
    }
    public function delete($id)
    {
        $ad=advertisements::find($id);
        if($ad->attachment != '')
        {  
            if (file_exists('app/public/ad/'.$ad->attachment)) {
            unlink(storage_path('app/public/ad/'.$ad->attachment)); 
             }
        }
        $ad->delete();
         
        return redirect()->route('adlist')->with('message','Advertisement Deleted Successfully');
    }
 
     public function users_visit_list(){
     
         
        //$users_visit =AdRecord::count();
        
        $users_visit = AdRecord::with('users')->paginate(10);
      
       
        return view('admin.users.user-visit-list',compact('users_visit'));

    }




}
