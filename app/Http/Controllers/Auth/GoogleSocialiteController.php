<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use Auth;
use Exception;
use App\Models\User;

class GoogleSocialiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
       
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleCallback()
    {
        try {
     
            $user = Socialite::driver('google')->user();

            $this->handleUser($user);
            return redirect('/dashboard');
     
        } catch (Exception $e) {

            $user = Socialite::driver('google')->stateless()->user();
            $this->handleUser($user);
            return redirect('/dashboard');
        }
    }

    public function handleUser($user='')
    {
        $finduser = User::where('social_id', $user->id)->first();

        if(!$finduser){
            $finduser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'social_id'=> $user->id,
                'social_type'=> 'google',
                'password' => encrypt('my-google')
            ]);
        }

        
        Auth::login($finduser);
    }
}
