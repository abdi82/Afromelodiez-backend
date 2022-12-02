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
use App\Models\Banner;
use App\Models\continent;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;
use Illuminate\Support\Str;

class BannerController extends Controller
{ 
    
    public function banner_form()
    {
            $banner= Banner::all();

      return  view('admin.banner.edit',compact('banner'));
    }
    public function store(Request $request)
    {
       

        $validated = $request->validate([
            'attachment' => 'required'
        ]);
        



      if ($request->hasFile('attachment')) {



             $files=$request->file('attachment');
            foreach($files as $file){ 

                $image = time().'_'.$file->getClientOriginalName();
                $image = str_replace(' ', '', $image);
                $file->move( storage_path('app/public/banner/') , $image);
                $data['banner'] = $image;
                $file_name=$file->getClientOriginalName();
                $images = Banner::create($data);
            }
             
        }
        

        //$banner = Banner::where('id',1)->update($data);   

        return redirect()->route('banner_form')->with('message','Banners Added Successfully');

    }
   public function delete_banner_image($id)
   {
            $image = Banner::where('id',$id)->first();
            
             if (file_exists('app/public/banner/'.$image->banner)) {
            $deleted= unlink(storage_path('app/public/banner/'.$image->banner));
             }
            
            Banner::where('id',$id)->delete();
            if(isset($deleted))
            {
                return redirect()->route('banner_form')->with('message','Deleted Successfully');
            }


            
   }


}