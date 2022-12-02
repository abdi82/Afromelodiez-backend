<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminPanel;
use App\Models\Contact_us;
use App\Models\ReplyContact;
use App\Models\Conditon;
use App\Models\Notification;
use App\Mail\ReplyContacts;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
class AdminPanelController extends Controller
{
    public function index(){
        $data = AdminPanel::first();

    	return view('admin.privacypolicy.add',compact('data'));

    }
    public function store(Request $request)
    {
    	
        $admin = AdminPanel::find(1);
        $admin->description = $request->description;
        $admin->short_description = $request->short_description;
        $admin->title = $request->title;
        $admin->save();

       return redirect()->route('privacy-policy');
    }

    public function term_index(){
        $data = Conditon::first();

        return view('admin.terms-condition.add',compact('data'));
    }

    public function storeterm(Request $request)
    {

        $admin= Conditon::find(2);

        $admin->description = $request->description;
        $admin->short_description = $request->short_description;
        $admin->title = $request->title;
        $admin->save();
       return redirect()->route('terms_conditions');
    }

    public function notification(Request $request){

        $data = Notification::get();
        return view('admin.Notification.notification-listing',compact('data'));

    }
   public function create_notification(){
       return view('admin.Notification.add-notification');
   }
    public function add_notification(Request $request){

        $data = new Notification;
        $data->message =$request->message;
        $data->save();
        return redirect()->route('get_notification')->with('message','Notification Created Successfully');

    }
     public function delete_noti($id)
     {
        $data=Notification::find($id)->delete();
        return redirect()->route('get_notification');
     }
     public function edit($id)
     {
        $data = Notification::find($id);
        return view('admin.Notification.edit-notification',compact('data'));
     }
     public function update(Request $request, $id)
    {

        $data = Notification::find($id);
        $data->title = $request->title;
        $data->save = $request->save;
        $data->message =$request->message;
        $data->update();
        return redirect()->route('get_notification');
    }
    public function notification_reply($id)
    {
           
        $user = User::get();
        $notification = Notification::find($id);

         // $Notify= sendPushNotification('Hi','test','cu99yQO7Q3WLiqwiAmcfel:APA91bHXKaTiaueQWOfLg0Ax9SJkEsoHbbKif2jnl9u7TKDUK8oy2u-7RLjKv6R0ZsgJ4SsdoHluic6bh5JKsQrtGt86mwSWRS_ekwgVx_y44PITgi64DWEvZl9G0aHOopPq4V-cUFHS', $notiid=null);
        foreach($user as $users)
                {
                    
                  $Notify= sendPushNotification($notification->title,$notification->message,$users->fcm_token, $notiid=null);
                  
                }    return redirect()->route('get_notification')->with("message",'Notification Send Successfully');

       // return view('admin.Notification.send-notification-listing',compact('notification'))
     }

    public function admin_contact(Request $request)
    {

        $data = Contact_us::get();

        return view('ContactPage',compact('data'));
    }
    public function contact_listing()
    {
        $data = Contact_us::paginate(5);

        return view('contact_listing',compact('data')); 
    }
    public function reply($id)
    {
        $data = Contact_us::find($id);
        // print_r($data);die('hello');
       return view('ContactPage',compact('data'));
    }

    public function contact_store(Request $request)
    {
        
        $data = new ReplyContact;
        $data->email = $request->email;
        $data->message = $request->message;
        $data->save();
        
           \Mail::to($request->email)->send(new ReplyContacts($data));

        return redirect()->route('contact_listing');
    }
   
}
