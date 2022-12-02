<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Betting;
use App\Models\Category;
use App\Models\Likevideo;
use App\Models\BetRequest;
use App\Models\BlockUser;
use App\Models\Comment;
use App\Models\BetNotification;
use App\Models\JoinBet;
use App\Models\Filter;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Str;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\api\usersettingsController;
use App\Http\Controllers\api\LibraryController;
use Illuminate\Support\Facades\Hash;
use DB;
//use Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Thumbnail;
use Carbon\Carbon;
use App\Models\BetReport;
use App\Models\FollowUser;
use App\Models\AdminPanel;
use App\Models\Contact_us;
use App\Mail\ContactMail;
use App\Models\Conditon;
use Symfony\Component\HttpFoundation\Response;
use App\Mail\forgotpasswordMail;

class AuthController extends Controller
{
    
/*
|--------------------------------------------------------------------------
| Constructor Function for middleware
|--------------------------------------------------------------------------
*/


    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login','Register','socialLogin','privacy_policy','terms_conditions','contact_us','Notification','timeTimer','forgot_password','sentTestMail','ResetMoney','logout','bettingReport','verifyCode']]);
    }

/*
|--------------------------------------------------------------------------
| Bet Login Api
|--------------------------------------------------------------------------
*/

    public function login(){

        $credentials = request(['email', 'password']);

        $devices = request(['device_type', 'device_token', 'fcm_token']);
             if(User::where('email', $credentials['email'])->where('status',1)->exists()){
              return response()->json(['success' => false , 'error' => ' User not Registered'],200);
             }
        if (! $token = auth()->guard('api')->attempt($credentials)) {

            return response()->json(['success' => false , 'error' => 'Unauthorized'],200);
        }
      $user= User::where('email', $credentials['email'])
      ->update(['device_type' => $devices['device_type'],'device_token' => $devices['device_token'],'fcm_token' => $devices['fcm_token']]);
        $this->respondWithToken($token);
       
        return response()->json([
            'success' => true,
            'token' => $token,
            'user_details'=>Auth::user()
        ]);


    }

/*
|--------------------------------------------------------------------------
| User Profile Api
|--------------------------------------------------------------------------
*/

    public function me()
    {
      // echo "string";die;
        $data['message'] = "Successfully"; 
        $data['status'] = 200; 
        $data['data'] = auth()->guard('api')->user(); 
        return response()->json($data);
    }

/*
|--------------------------------------------------------------------------
| Logout Api
|--------------------------------------------------------------------------
*/

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

/*
|--------------------------------------------------------------------------
| Refresh token Api
|--------------------------------------------------------------------------
*/

    public function refresh()
    {
        return $this->respondWithToken(auth()->guard('api')->refresh());
    }

/*
|--------------------------------------------------------------------------
| Respond with Token Api
|--------------------------------------------------------------------------
*/

    protected function respondWithToken($token)
    {
         $data['message'] = "Login Successfully";
         $data['status'] = 200;
         $data['data'] = [
        'access_token' => $token,
        'token_type' => 'bearer',
        // 'expires_in' => auth('api')->factory()->getTTL() * 60
    ];

        return response()->json($data);
    }

/*
|--------------------------------------------------------------------------
| Edit Profile Api
|--------------------------------------------------------------------------
*/

    public function editProfile()
    {
    	$id = Auth::user()->id;
    	return response()->json(User::find($id));
    }

/*
|--------------------------------------------------------------------------
| Profile Update Api
|--------------------------------------------------------------------------
*/

   public function profileUpdate(Request $request){


    $rules = array(
            'name' =>'required|min:4|string|max:255',
            'email'=>'required|email|string|max:255'
        );
          $request['email']=strtolower($request->email);
       $validator = Validator::make( $request->all(), $rules);
            if ( $validator->fails() ) 
                {
                    $message = [
                        'message' => $validator->errors()->first()
                    ];
                     return response()->json($message);
                }

            else{
                    $user =Auth::user();
                    $user->name = $request['name'];
                    $user->email = $request['email'];
                    $user->save();
                    $data['status'] = 200;
                    $data['message'] = "Profile Update Successfully";
                    $data['data'] = $user;
                    return response()->json($data); 
            }
       
    }

/*
|--------------------------------------------------------------------------
| Change Password Api
|--------------------------------------------------------------------------
*/


    public function changePass(Request $request)
    {
        $rules = array(
                  'current_password' => ['required', new MatchOldPassword],

                   'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',

                   'password_confirmation' => 'min:6'
        );


        $validator = Validator::make( $request->all(), $rules);
            if ( $validator->fails() ) 
                {
                    $message = [
                        'message' => $validator->errors()->first()
                    ];
                     return response()->json($message);
                }

   else{

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->password)]);
        $data['message'] = "Password Changed Successfully";
        $data['status'] = 200;
        return response()->json($data);

   }

    }


/*
|--------------------------------------------------------------------------
| Forget Password Api
|--------------------------------------------------------------------------
*/

//    public function forgot_password(Request $request){

//     $rules = array('email'=>'unique:users,email');

//      $email=strtolower($request->email);

//      $data=array( 
//       'email' => $email
//       );

//     $validator = Validator::make( $data , $rules);

//     // Mail::send('emails.verification', array('key' => 'value'), function($message){
//     //         $message->to('developer1607@gmail.com', 'Test')->subject('Welcome!');
//     //     });
//     // return response()->json(["msg" => 'Reset password link sent on your email id.','status'=>   $status]);
//       if ( $validator->fails() ) {
        
//           $credentials = $email;
//             $status = Password::sendResetLink(
//               $data
//           );
//          //Password::sendResetLink($credentials);
//          return response()->json(["msg" => 'Reset password link sent on your email id.','status'=>   $status]);
//       }
//       else{
//         return response()->json(["msg" => 'The email was not registered']);
//       }
        
// }

         public function forgot_password(Request $request)
         {
             $date = Carbon::now();
             $date=strtotime($date);
             $futureDate = $date+(60*5);
            $expiry=date("Y-m-d H:i:s", $futureDate);
            
        
             $credentials = request()->validate(['email' => 'required|email']);

             $user = User::where('email', $request->email)->first();
             
             if (isset($user)) {
             $verification_code = Str::random(8);
             
             $user->verification_code=$verification_code;
             $user->code_expiry=$expiry;
             $user->save();
            //  $account_sid = getenv("TWILIO_SID");
            //  $auth_token = getenv("TWILIO_AUTH_TOKEN");
            //  $twilio_number = getenv("TWILIO_NUMBER");
            //  $client = new Client($account_sid, $auth_token);
            //   $client->messages->create("+917986263826", 
            // ['from' => $twilio_number, 'body' => "otp for friendly money verification :".$verification_code] );

             \Mail::to($user->email)->send(new forgotpasswordMail($user));
            
             return response()->json([
                'status'=>true,
                "message" => 'Please check mail for verification code'
            ]);
             
        }
            //  if (isset($user)) {

            //      Password::sendResetLink($credentials);

            //      return response()->json([
            //          'status'=>true,
            //          "message" => 'Reset password link sent on your email address.'
            //      ]);
            //  }
             else{
                 return response()->json([
                     'status'=>false,
                     "message" => 'Email Id is not Exist '
                 ]);
             }
    //        $request->validate(['email' => 'required|email']);

    //          $status = Password::sendResetLink(
    //     $request->only('email')
    // );
 
    // // return $status === Password::RESET_LINK_SENT
    // //             ? back()->with(['status' => __($status)])
    // //             : back()->withErrors(['email' => __($status)]);

    //             return response()->json([
    //                 'status'=>true,
    //                 "message" => $status
    //             ]);
         }

         public function verifyCode(Request $request)
         {
             
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'code' => 'required'
            ]);
    
            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
            }
             
             $user=User::where('email',$request->email)->first();
            
             $credentials = request()->validate(['email' => 'required|email']);
             $date = Carbon::now();
             $date=strtotime($date);
             $now=date("Y-m-d H:i:s", $date);
            
            if($user->code_expiry<$now)
            {
                return response()->json([
                    'status'=>false,
                    "message" => 'Verification Code Expired'
                ]);

            }

            else
            {
                   if($request->code==$user->verification_code)
                   {
                    // Password::sendResetLink($credentials);


                    
                         return response()->json([
                              'status'=>true,
                              "message" => 'Success.',
                              "Token" => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbXVzaWMubnZpbmZvYmFzZS5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2NDQ5MDY2NTQsIm5iZiI6MTY0NDkwNjY1NCwianRpIjoiTUdCdmdhc1N2d3pTZzJzMyIsInN1YiI6MSwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.eFG6nHZ5sD-kJxRWM7yr9WTgi8aOMn4NBShIQX7k5K4'
                          ]);
                   }
                    else{
                        return response()->json([
                                    'status'=>false,
                                    "message" => 'Please enter correct verification code'

                        ]);
                    }

                
            }
        }

    public function ResetPassword(Request $request)
     {
     
       
        $validator = Validator::make($request->all(), [
            'password' => ['required'],
            'email' => ['required'],
            
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $message = [
                'message' => $validator->errors()->first()
            ];
            return response()->json($message,500);
        }
       
             $user=User::where('email',$request->email)->first();
             
        if (isset($user)) {
             $user-> update(['password'=>bcrypt($request->password)]);
            
             return response()->json([
                'status'=>True,
                "message" => 'Updated Successfully '
            ]);
        }
        else{
            return response()->json([
                'status'=>True,
                "message" => 'Invalid Email '
            ]);

        }


     }

/*
|--------------------------------------------------------------------------
| Register a new user Api
|--------------------------------------------------------------------------
*/


    public function Register(Request $request)
    {

        $rules = array(
         'name' =>'required|min:4|string|max:255',
         'email'=>'required|email',
         'password' => 'required',
    
//         'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        );

         $validator = Validator::make( $request->all(), $rules);
            if ( $validator->fails() ) 
                {
                    $message = [
                        'message' => $validator->errors()->first()
                    ];
                     return response()->json($message);
                }
                else{
                        if (User::where('email', '=', $request->email)->where('status',0)->count() > 0) {
                        $user['message']= 'User is already Exist';
                        $user['status']= 500;
                        return response()->json($user);
                        }
                else{   
              
                  if ($request->hasFile('image')) {
                      $images = $request->image->getClientOriginalName();
                      $images = time().'_'.$images; // Add current time before image name
                      $imageName = $images;
                      $upload = $request->file('image')->storeAs('public/users', $images);
                  }
                  else{
                      $imageName ='';
                  }
                 if(User::where('email', $request->email)->where('status',1)->exists()){
             User::where('email', $request->email)->where('status',1)->update(['status'=>0]);
             $user =User::where('email', $request->email)->first();
             }
             else{
                $user = new User;
                $user->name = $request->name;
                $user->email = strtolower($request->email);
                $user->password = Hash::make($request->password);
                $user->profile_photo_path = $imageName;
                 $user->mobile = $request->mobile;

                $user->save();
                 }
                 $email_data = array(
                    'name' => $request->name,
                    'email' => $request->email,
                );

                    Mail::send('welcome_email', $email_data, function ($message) use ($email_data) {
                        $message->to($email_data['email'], $email_data['name'])
                            ->subject('Welcome')
                            ->from('info@afromelodies.com', 'Abdi');
                    });

                    Auth::loginUsingId($user->id);
                return $this->login($request);
                $data['status'] = 200;
                $data['message'] = "User Registered Successfully";
                $data['data'] = $user;
                return response()->json($data);
                }

                
                }
	
        
    }


   public function socialLogin(Request $request){
   
   	if (User::where('email', '=', $request->email)->count() > 0) {

   		$ex = User::where('email',$request->email)->first();

   		if ($ex->provider_name == Null) {

     			$email = $request->email;
  		   	$password = Hash::make($request->password);

  		   return $this->socialloginwith();
   		}
      elseif (User::where('provider_id', '=', $request->provider_id)->count() > 0) {

            // $email = $request->email;
            $password = Hash::make($request->password);
            return $this->socialloginwith();
      }
   	 else{

     			 User::where('email', $request->email)
        ->update(['provider_id' => $request->provider_id,'provider_name' => $request->provider_name,'fcm_token',$request->fcm_token]);

        		$email = $request->email;
  		   	$password = Hash::make($request->password);

		   return $this->socialloginwith();
   		}
		  
		}
   
else{

   if (User::where('provider_id', '=', $request->provider_id)->count() > 0) {
      $email = $request->email;
            $password = Hash::make($request->password);
            return $this->socialloginwith();
    }
    else{

      $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'provider_id' => $request->provider_id,
      'provider_name' => $request->provider_name,
      'password' => Hash::make($request->password),
      'device_type' => $request->device_type,
      'device_token' => $request->device_token,
      'profile_photo_path' => $request->image,
      'fcm_token' => $request->fcm_token,

      ]);
      $email = $request->email;
      $password = $request->password;
      //return $this->login();
      return $this->socialloginwith();
    }
			
		}
   
   }
 
 /*
 |--------------------------------------------------------------------------
 | Social Login With API
 |--------------------------------------------------------------------------
 */
 
 
   public function socialloginwith()
    {
       $credentials = request(['provider_id', 'password']);
       $devices = request(['device_type', 'device_token', 'fcm_token']);

        User::where('email', request('email'))
            ->update($devices);
       if (! $token = auth()->guard('api')->attempt($credentials)) {

                return response()->json(['error' => 'Unauthorized'], 401);
         }

            return $this->respondWithToken($token);
    }


/*
|--------------------------------------------------------------------------
| Monthly Added money Cron job API
|--------------------------------------------------------------------------
*/

    public function ResetMoney()
    {
      User::where('user_money',0)->increment('user_money', 100);
    }


/*
|--------------------------------------------------------------------------
| Get User Details By ID API
|--------------------------------------------------------------------------
*/

   public function getUserbyId($id){

    $currentUser = auth()->user()->id;
    $myBetting = DB::select("select * from join_bets where userid = $id");
    $userDetails = DB::select("select * from users where id = $id");
    $booleanData = DB::select("select * from follow_users where user1 =$id and user2 =$currentUser");
    $wincount = DB::select("select count(id) as wincount from join_bets where status = 'win' and userid = $id");
    // echo'<pre>';print_r($wincount[0]->wincount);die;
    $losscount = DB::select("select count(id) as losscount from join_bets where status = 'loss' and userid = $id");
    $opencount = DB::select("select count(id) as opencount from join_bets where status = 'pending' and userid = $id");
    if (empty($booleanData)) {
      $userDetails[0]->follow_status = 0;
    }else{
      $userDetails[0]->follow_status = 1;

    }

    $userDetails[0]->wincount = $wincount[0]->wincount;
    $userDetails[0]->losscount = $losscount[0]->losscount;
    $userDetails[0]->opencount = $opencount[0]->opencount;
    // echo "<pre>";print_r($userDetails);die;


                      if(count($myBetting) > 0){
                         $data['message']  = 'My bets Listed Successfully';
                         $data['status']  = 200;
                         $data['data']  = $myBetting;                       
                         $data['user']  = $userDetails;                       
                        //  $data['videoList']  = $videoList;                       
                         $data['total_records'] = count($myBetting);
                         return response()->json($data);
                       }
                        else{
                        $data['status'] = 200;
                        $data['message'] = "Sorry! Betting not found.";
                        return response()->json($data); 

                      }
   }


/*
|--------------------------------------------------------------------------
| Add Money with Stripe Payment API
|--------------------------------------------------------------------------
*/

  public function addMoney(Request $request)
   {
     $rules = array(
          'amount' => 'required',
          'coins' => 'required',
          'number'=>'required',
          'exp_month'=>'required',
          'exp_year'=>'required',
          'coins'=>'required',
          );
       $validator = Validator::make( $request->all(), $rules);
              if ( $validator->fails() ) 
                  {
                      $message = [
                          'message' => $validator->errors()->first()
                      ];
                       return response()->json($message);
                  }
                  else{
                    
                     $stripe = new \Stripe\StripeClient(
                      'sk_test_ssX8RkrsQJWlHzUI7gxSeVnd'
                      );
                       $token = $stripe->tokens->create([
                          'card' => [
                            'number' => $request->number,
                            'exp_month' => $request->exp_month,
                            'exp_year' => $request->exp_year,
                            'cvc' => $request->cvc,
                          ],
                        ]);
                        $amount = intval($request->amount);
                        $data = $stripe->charges->create([
                        'amount' => $amount * 100,
                        'currency' => 'usd',
                        'source' => $token,
                        'description' => 'Buy Coins Charges for Bets',
                      ]);
                     User::where('id',auth()->user()->id)->increment('user_money', $request->coins);
                     return response()->json(['msg' => 'Money added Successfully.','status' => 200]);
                  }
   } 


/*
|--------------------------------------------------------------------------
| Notification User When Bet status is Pending API
|--------------------------------------------------------------------------
*/

    public function notifyUser($round,$betid)
    {

          $users = DB::select("select user_id from bet_requests where bet_id = $betid");
          foreach ($users as $key => $user) {
          $fcm_token = DB::table('users')->where('id', $user->user_id)->pluck('fcm_token');
          $fcm_token = $fcm_token[0];
          sendPushNotification('Bet is now active','Please Join Bets Now It is active',$fcm_token,$notiid=null);

          // Store Notification

          BetNotification::create([
                  'bet_id' => $betid,
                  'title' => 'Bet is now active',
                  'description' => 'Please Join Bets Now It is active',
                  'read' => 0,
               ]);
          }
     
    }

/*
|--------------------------------------------------------------------------
| Store Bet Comments Api
|--------------------------------------------------------------------------
*/

    public function storeComment(Request $request)
    {

      $rules = [

            'bet_id' => 'required|numeric',
            'message_type' =>'required|in:text,image,audio,video',
            'message' =>'required',
            'round' =>'required',
        ];


    $validator = Validator::make( $request->all(), $rules);
      if ( $validator->fails()){

          $message = [
                'message' => $validator->errors()->first()
            ];
             return response()->json($message);
          }

        else{

          // 'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
          if ($request->message_type != 'text') {

            if ($request->hasFile('message')) {
                      $images = $request->message->getClientOriginalName();
                      $images = time().'_'.$images; // Add current time before image name
                      $request->message = $images;
                      $upload = $request->file('message')->storeAs('public/chat', $images);
                  }
          }
 
          $comments = Comment::create([

          'message_type' => $request->message_type,
          'message'      => $request->message,
          'user_id'      => auth()->user()->id,
          'bet_id'       => $request->bet_id,
          'round'        => $request->round,

          ]);

          $fcm_token = DB::select("SELECT U1.fcm_token as user1_token,U2.fcm_token as user2_token FROM `bettings` JOIN users U1 ON bettings.userid_1 = U1.id JOIN users U2 on U2.id = bettings.userid_2 WHERE bettings.id = $request->bet_id");
           $username = User::where('id',auth()->user()->id)->pluck('name');
           $username = $username[0];
          //  echo'<pre>';print_r($username);die;
            foreach($fcm_token as $token){

              sendPushNotification(''.$username.' commented on your bet','Please check user comment on your bet',$token->user1_token,$notiid=null);
              sendPushNotification(''.$username.' commented on your bet','Please check user comment on your bet',$token->user2_token,$notiid=null);

            }
            BetNotification::create([
              'bet_id' => $request->bet_id,
              'title' => 'Commented on your bet',
              'description' => 'please check your bet User commented on your bet',
              'read' => 0,
              'user_id' => auth()->user()->id,
           ]);
            $data['status'] = 200;
            $data['data']   = $comments;
            $data['msg']    = "message sent Successfully !";
            return response()->json($data);
       
      }   
      
    }

/*
|--------------------------------------------------------------------------
| Get Comment Api
|--------------------------------------------------------------------------
*/

    public function getComments($bet_id)
    {
        $round = DB::table('bettings')->where('id',$bet_id)->pluck('round');
        $round = $round[0];
        $betList = DB::select("SELECT comments.id,comments.message,comments.created_at,users.id as user_id,users.name,users.profile_photo_path FROM bettings JOIN comments ON comments.bet_id='$bet_id' JOIN users ON users.id=comments.user_id  WHERE  bettings.id = '$bet_id' and comments.round='$round'");
        if(count($betList) > 0){
          $data['status'] = 200;
          $data['data'] = $betList;
          $data['msg'] = 'Comments listing Successfully';
          return response()->json($data);
        }
       else{
        return response()->json(["msg" =>"No comments found for this bet",'status' => 200]);
       }
      
  
    }

/*
|--------------------------------------------------------------------------
| Add Money by Apple Pay
|--------------------------------------------------------------------------
*/

    public function addMoneyByApplePayment(Request $request){

      $rules = [

            'coins' => 'required|numeric',
            
        ];


    $validator = Validator::make( $request->all(), $rules);
      if ( $validator->fails()){

          $message = [
                'message' => $validator->errors()->first()
            ];
             return response()->json($message);
          } 

          else{

            User::where('id',auth()->user()->id)->increment('user_money', $request->coins);
            return response()->json(['msg' => 'Money added Successfully.','status' => 200]);

          }
       
    }


/*
|--------------------------------------------------------------------------
| Publish Betting Api and Store video for multiple User
|--------------------------------------------------------------------------
*/

 public function publishBetting(Request $request){

  $price = $request->money;
  $member = $request->member_qty;
  $category = $request->category_id;
  $time = $request->time;

  if(Betting::where('money', '=', $price)->where('member_qty', '=', $member)->where('category_id', '=', $category)->where('time', '=', $time)->where('publish', '=', null)->exists()){

    $video2 = time().'_2.'.$request->file('video')->extension();
    $request->video->move(public_path('betting_videoes'), $video2);
    $thumbnail_image2 = $video2.'.jpg';
    $thumbnail_path = public_path('thumbnail');
    $video_path2 = public_path('betting_videoes/'.$video2.'');
    $thumbnail_status2 = Thumbnail::getThumbnail($video_path2,$thumbnail_path,$thumbnail_image2,3);
    $bettingVal =  Betting::where('money', '=', $price)->where('member_qty', '=', $member)->where('category_id', '=', $category)->where('time', '=', $time)->where('publish', '=', null)->first();
    $bettingVal->update([
    'video_link2' => $video2,
    // 'emoji_2' => $request->emoji,
    'tag_2' => $request->tag,
    'caption_2' => $request->caption,
    'userid_2'   => auth()->user()->id,
    'publish' => 1,

   ]);
   $notificationData = DB::select("select bettings.userid_1,bettings.userid_2,users.fcm_token,users2.fcm_token as fcm_token2 from bettings join users on bettings.userid_1 = users.id join users as users2 on bettings.userid_2 = users2.id where bettings.id = $bettingVal->id");
   foreach($notificationData as $userData){
     sendPushNotification('Your video is now posted.','Your video is now posted to All or Nothin open the app and share your match for more likes!',$userData->fcm_token,$notiid=null);
     sendPushNotification('Your video is now posted.','Your video is now posted to All or Nothin open the app and share your match for more likes!',$userData->fcm_token2,$notiid=null);
 
   }

   return response()->json(['msg' => 'Video Post Successfully','status' => 200]);

  }

else{

    $video1 = time().'_1.'.$request->file('video')->extension(); 
    $request->video->move(public_path('betting_videoes'), $video1);  
    $video_path = public_path('betting_videoes/'.$video1.'');
    $thumbnail_path = public_path('thumbnail');
    $thumbnail_image = $video1.'.jpg';
    $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image,3);
    Betting::create([
      'video_link1' => $video1,
      'tag_1' => $request->tag,
      'caption_1' => $request->caption,
      'time' => $request->time,
      'money' => $request->money,
      'member_qty' => $request->member_qty,
      'category_id' => $request->category_id,
      'userid_1'   => auth()->user()->id,

      ]);

      
   return response()->json(['msg' => 'Video Post Successfully','status' => 200]);

}
 
}



/*
|--------------------------------------------------------------------------
| Comment Delete Api User can delete only his commnets
|--------------------------------------------------------------------------
*/


public function deleteComment(Request $request){

  $rules = array(
    'id'=>'required',
);

  $validator = Validator::make( $request->all(), $rules);
    if ( $validator->fails() ) 
        {
            $message = [
                'message' => $validator->errors()->first()
            ];
             return response()->json($message);
        }
        else{

          if(Comment::where('id',$request->id)->exists()){
            $getchatdetail = Comment::whereIn('id', $request->id)->get();
            if(count($getchatdetail) > 0) {
              Comment::whereIn('id', $request->id)->delete();
              return response()->json(['msg' => 'Comment Deleted','status' => 200]);
            }
            else {
              return response()->json(['msg' => 'Comment not found','status' => 200]);
          }            
        
          }
          else{
        
           return response()->json(['msg' => 'Comment not Found','status' => 404]);
        
          }
        }

 

}

/*
|--------------------------------------------------------------------------
| Report Betting Api 
|--------------------------------------------------------------------------
*/

public function reportBet(Request $request){
 
  $rules = array(
    'report_type' =>'required',
    'bet_id'=>'required',
    'video_link'=>'required',
);

  $validator = Validator::make( $request->all(), $rules);
    if ( $validator->fails() ) 
        {
            $message = [
                'message' => $validator->errors()->first()
            ];
             return response()->json($message);
        }
      else{
        $userid = Auth::user()->id;
        
        BetReport::create([
          'report_type' => $request->report_type, 
          'bet_id' => $request->bet_id, 
          'video_link' => $request->video_link, 
          'user_id' => $userid, 
        ]);
        return response()->json(['msg' => 'Report Publish Successfully','status' => 200]);

      }  

}

/*
|--------------------------------------------------------------------------
| Display users videos in profile 
|--------------------------------------------------------------------------
*/


public function videoList(){

    $userid = Auth::user()->id; 
    // $videoList = DB::select("select * from bettings where userid_1 = $userid or userid_2 =$userid");
    $videoList = DB::select("select bettings.id as bet_id,bettings.video_link1,bettings.caption_1,users.name as username from bettings join users on bettings.userid_1 = users.id where userid_1 = $userid or userid_2 =$userid group by bettings.id ORDER BY bettings.id DESC");

    $videoList = array_map(function ($videoList) {
      return (array)$videoList;
    }, $videoList);

// echo'<pre>';print_r($videoList);die;
//     $videos = [];
//     foreach($videoList as $video){

//         if($video['userid_1'] == $userid){
//           $videos[] = $video['video_link1'];
//         }

//         if($video['userid_2'] == $userid){
//           $videos[] = $video['video_link2'];

//         }
//     }
    
    $data['data'] = $videoList;
    $data['status'] = 200;
    return response()->json($data);

}


/*
|--------------------------------------------------------------------------
|  Follow User Api
|--------------------------------------------------------------------------
*/


public function followUser(Request $request){

  $rules = array(
    'user1' =>'required',
  );

  $validator = Validator::make( $request->all(), $rules);
    if ( $validator->fails() ) 
        {
            $message = [
                'message' => $validator->errors()->first()
            ];
             return response()->json($message);
        }
        else{
          $userid = Auth::user()->id; 
      if(FollowUser::where('user1',$request->user1)->where('user2',$userid)->count() > 0){
        return response()->json(['msg' => 'You have alredy followed that user.','status' => 200]);
      }
      else{

        $data = FollowUser::create([
          'user1' => $request->user1,
          'user2' => $userid,
        ]);

        return response()->json(['msg' => 'User followed Successfully','status' => 200]);
      }
       
        }
}



/*
|--------------------------------------------------------------------------
|  UnFollow User Api
|--------------------------------------------------------------------------
*/


public function unFollowUser(Request $request){

  $rules = array(
    'user2' =>'required',
  );

  $validator = Validator::make( $request->all(), $rules);
    if ( $validator->fails() ) 
        {
            $message = [
                'message' => $validator->errors()->first()
            ];
             return response()->json($message);
        }
        else{
          $userid = Auth::user()->id; 
        $data = FollowUser::where('user2', '=', $request->user2)->where('user1', '=', $userid)->delete();
            return response()->json(['msg' => 'User unfollowed Successfully','status' => 200]);
        }
}


/*
|--------------------------------------------------------------------------
|  Get Responser Api
|--------------------------------------------------------------------------
*/


public function makeTrendingBet(Request $request){

  $rules = array(
    'hours' =>'required',
    'bet_id' =>'required',
    'coins' =>'required',
  );

  $validator = Validator::make( $request->all(), $rules);
    if ( $validator->fails() ) 
        {
            $message = [
                'message' => $validator->errors()->first()
            ];
             return response()->json($message);
        }

    else{

    $userid = Auth::user()->id; 
    $now = date('Y-m-d H:i:s');
    $upcomingTime =  date('Y-m-d H:i:s',strtotime('+'.$request->hours.'',strtotime($now)));
    $trendingTime = strtotime($upcomingTime);
    User::where('id',$userid)->decrement('user_money', $request->coins);
    $data = Betting::where('id',$request->bet_id)->update([
      'trending_time' => $trendingTime,
      'trending' => 1
    ]);
  //   $m = strtotime($upcomingTime);
  //  echo $time = date("Y-m-d H:i:s",$m);
  $notificationData = DB::select("select bettings.userid_1,bettings.userid_2,users.fcm_token,users2.fcm_token as fcm_token2 from bettings join users on bettings.userid_1 = users.id join users as users2 on bettings.userid_2 = users2.id where bettings.id = $request->bet_id");
  foreach($notificationData as $userData){
    sendPushNotification('Your bet is displayed in trending page','Please check your bet on trending page',$userData->fcm_token,$notiid=null);
    sendPushNotification('Your bet is displayed in trending page','Please check your bet on trending page',$userData->fcm_token2,$notiid=null);

  }
  return response()->json(['msg' => 'Video added on trending page','status' => 200]);

    }
}

/*
|--------------------------------------------------------------------------
|  Get fcm token By users ids
|--------------------------------------------------------------------------
*/


public function retriveFcmToken(){
  $userIds = [1,13,14,15];
  $token = User::whereIn('id',$userIds)->pluck('fcm_token');
  return $token;
}



/*
|--------------------------------------------------------------------------
|  Block User Api 
|--------------------------------------------------------------------------
*/



public function blockUser(Request $request){
 
  $rules = array(
    'userid' =>'required',
  );

  $validator = Validator::make( $request->all(), $rules);
    if ( $validator->fails() ) 
        {
            $message = [
                'message' => $validator->errors()->first()
            ];
             return response()->json($message);
        }
        else{
          BlockUser::create([
            'user1' => $request->userid,
            'user2' => Auth::user()->id,
          ]);

        //  $users =  $this->getBlockUsersId(Auth::user()->id);
          return response()->json(['msg' => 'user block successfully','status' => 200]);

        }

}


/*
|--------------------------------------------------------------------------
|  Get block User List
|--------------------------------------------------------------------------
*/


public function getBlockUsersId($userid){

  $users = DB::select("select user1,user2 from block_users where user1 = $userid or user2 = $userid");
  $blockUserIds = array();
  foreach($users as $user){
    if($user->user1!=$userid){
      $blockUserIds[] = $user->user1;
    }
    if($user->user2!=$userid){
      $blockUserIds[] = $user->user2;
    }
    
  }
  return $blockUserIds;
}


/*
|--------------------------------------------------------------------------
|Notification List
|--------------------------------------------------------------------------
*/

public function notificationList()
{
  $user = Auth::user()->id;
  $notificationList = DB::select("select bet_notifications.*,users.name,users.profile_photo_path from bet_notifications JOIN users on users.id = bet_notifications.user_id where user_id = $user");
  $data['status'] = 200;
  $data['msg'] = 'Notification List';
  $data['data'] = $notificationList;
  return response()->json($data);
}

/*
|--------------------------------------------------------------------------
|  Read Notification Api
|--------------------------------------------------------------------------
*/

public function readNotification($id)
{
  BetNotification::where('id',$id)->update(['read'=> 1]);
  $data['status'] = 200;
  $data['msg'] = 'Notification read successfully';
  return response()->json($data);
}


/*
|--------------------------------------------------------------------------
|  Change Profile Image 
|--------------------------------------------------------------------------
*/

public function changeProfileImage(Request $request){

           $user = Auth::user()->id;
           if ($request->hasFile('image')) {
                      $images = $request->image->getClientOriginalName();
                      $images = time().'_'.$images; // Add current time before image name
                      $imageName = $images;
                      $upload = $request->file('image')->storeAs('public/users', $images);
                      User::where('id',$user)->update(['profile_photo_path' => $imageName]);
             }
             return response()->json(['msg'=>'Profile updated successfully']);
             
}


/*
|--------------------------------------------------------------------------
|  Change Cover Image 
|--------------------------------------------------------------------------
*/

public function changeCoverImage(Request $request){

  $user = Auth::user()->id;
  if ($request->hasFile('image')) {
             $images = $request->image->getClientOriginalName();
             $images = time().'_'.$images; // Add current time before image name
             $imageName = $images;
             $upload = $request->file('image')->storeAs('public/users', $images);
             User::where('id',$user)->update(['cover_image' => $imageName]);
    }
    return response()->json(['msg'=>'Cover Image updated successfully']);
    
}

/*
|--------------------------------------------------------------------------
|  Discover Apis 
|--------------------------------------------------------------------------
*/


public function discoverVideos(){
  $discoverVideos = DB::select("select bettings.id as bet_id,bettings.video_link1,bettings.caption_1,bettings.caption_2,users.name as username from bettings join follow_user0s U1 on U1.id = bettings.userid_1 join users on users.id = bettings.userid_1");
  $discoverVideos2 = DB::select("select bettings.id as bet_id,bettings.video_link2,bettings.caption_1,bettings.caption_2,users.name as username from bettings join follow_users U1 on U1.id = bettings.userid_2 join users on users.id = bettings.userid_2");
  $discoverVideos = array_map(function ($discoverVideos) {
    return (array)$discoverVideos;
  }, $discoverVideos);
  // echo'<pre>';print_r($discoverVideos);die;

  $discoverVideos2 = array_map(function ($discoverVideos2) {
    return (array)$discoverVideos2;
  }, $discoverVideos2);

$discoverData = array_merge($discoverVideos,$discoverVideos2);
 $data['status'] = 200;
 $data['msg'] = 'Video list display successfully';
 $data['data'] = $discoverData;
 return response()->json($data);

}


public function buyFilter(Request $request){

  $rules = array(
    'userid' =>'required',
  );

  $validator = Validator::make( $request->all(), $rules);
    if ( $validator->fails() ) 
        {
            $message = [
                'message' => $validator->errors()->first()
            ];
             return response()->json($message);
        }
        else{

  $user = Auth::user()->id;

   User::where('id',auth()->user()->id)->decrement('user_money', $request->coins);
   Filter::create([
    'name' => $request->name,
    'user_id' => $user,
   ]);
   return response()->json(['msg' => 'Filter Buy Successfully.','status' => 200]);
        }
}

public function filterList(){

  $user = Auth::user()->id;

  $filterList = DB::select("select * from filters where user_id = $user");
  $data['status'] = 200;
  $data['data'] = $filterList;
  $data['msg'] = "Filter listing successfully";
 return response()->json($data);


}



public function videosList(){
  $discoverVideos = DB::select("select bettings.id as bet_id,bettings.video_link1,bettings.caption_1,users.name as username from bettings join users on bettings.userid_1 = users.id group by bettings.id ORDER BY bettings.id DESC");
  $discoverVideos2 = DB::select("select bettings.id as bet_id,bettings.video_link2,bettings.caption_2,users.name as username from bettings join users  on bettings.userid_2 = users.id group by bettings.id ORDER BY bettings.id DESC");
   $discoverVideos = array_map(function ($discoverVideos) {
    return (array)$discoverVideos;
  }, $discoverVideos);

  $discoverVideos2 = array_map(function ($discoverVideos2) {
    return (array)$discoverVideos2;
  }, $discoverVideos2);

$discoverData = array_merge($discoverVideos,$discoverVideos2);
// echo'<pre>';print_r($discoverData);
 $data['status'] = 200;
 $data['msg'] = 'Video list display successfully';
 $data['data'] = $discoverData;
 return response()->json($data);

}

public function sentTestMail(Request $request){

	Mail::send('welcome', [], function($message) {
    $message->to('user123@yopmail.com')->subject('Test Mail'); 
    });
}


public function videosListbyid($id){


    $videoList = DB::select("select bettings.id as bet_id,bettings.video_link1,bettings.caption_1,users.name as username from bettings join users on bettings.userid_1 = users.id where userid_1 = $id group by bettings.id ORDER BY bettings.id DESC");

    $videoList = array_map(function ($videoList) {
      return (array)$videoList;
    }, $videoList);
    $data['status'] = 200;
    $data['msg'] = 'Video list display successfully';
    $data['data'] = $videoList;
    return response()->json($data);

}
public function privacy_policy(){
        $data = AdminPanel::first();
        return response()->json([
                    'success' => true,
                    'data' => $data,
                ], Response::HTTP_OK);
}
 
public function terms_conditions(){
$data = Conditon::first();
return response()->json([
                    'success' => true,
                    'data' => $data,
                ], Response::HTTP_OK);
}

public function contact_us(Request $request){

    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'name' => 'required|string',
        'company_name' => 'required|string',
        'message' => 'required'
    ]);

    if ($validator->fails()) {
        return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
    }  

     
    $contact = Contact_us::create([
    'name' => $request->name,
    'company_name' =>$request->company_name,
    'email' =>$request->email,
    'message' =>$request->message,
    ]);
    if ($contact) {
        Mail::to('info@afromelodiez.com')->send(new ContactMail($contact));
      return response()->json([
                    'success' => true,
                    'data' => $contact,
                ], Response::HTTP_OK);
            }
    }
    
    public function users_status($id)
    {
        $users_status =User::find($id); 
        $users_status->status=1;
        $users_status->save();
        return response()->json([
                    'success' => true,
                    'data' => $users_status,
                ], Response::HTTP_OK);
    }
    
}

