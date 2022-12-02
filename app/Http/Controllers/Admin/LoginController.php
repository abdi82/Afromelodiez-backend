<?php

namespace App\Http\Controllers\Admin;
use Auth;
use Socialite;
Use App\Models\User;
use App\Models\SocialIdentity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class LoginController extends Controller

{ 
   public function home()
   {
       if(Auth::user()) {
           if (Auth::user()->user_role == 'superAdmin') {
               return redirect('/admin/dashboard');
           } else if (Auth::user()->user_role == 'admin') {
               return redirect('/song');

            } else if (Auth::user()->user_role == 'artist') {
               return redirect('/song');

           } else if (Auth::user()->user_role == 'manager') {
               return redirect('/admin/ManagerDashboard');
           }
       }
    else
    {
        return redirect('/login')->with('error','You have not admin access');
    }
   }
   public function redirectToProvider($provider)
   {
   	
       return Socialite::driver($provider)->redirect();
   }

   public function handleProviderCallback($provider)
   {
       try {
           $user = Socialite::driver($provider)->user();
       } catch (Exception $e) {
           return redirect('/login');
       }
       $authUser = $this->findOrCreateUser($user, $provider); 
       Auth::login($authUser, true);
       return redirect($this->redirectTo);
   }


   public function findOrCreateUser($providerUser, $provider)
   {
      
       $account = SocialIdentity::whereProviderName($provider)
                  ->whereProviderId($providerUser->getId())
                  ->first();

       if ($account) {
           return $account->user;
       } else {
           $user = User::whereEmail($providerUser->getEmail())->first();

           if (! $user) {
               $user = User::create([
                   'email' => $providerUser->getEmail(),
                   'name'  => $providerUser->getName(),
               ]);
           }

           $user->identities()->create([
               'provider_id'   => $providerUser->getId(),
               'provider_name' => $provider,
           ]);

           return $user;
       }
   }

public function logout(Request $request) {
    Auth::logout();
    return redirect('/login');
  }

}