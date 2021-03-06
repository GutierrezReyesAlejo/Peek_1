<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Services\SocialGoogleAccountService;


use App\User;
use \Laravel\Socialite\Facades\Socialite;
// use Socialite; // This works fine, but vscode-intelephense addon does not recognize symbol


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }


    /**
     * Obtain the user information from Google.
     *
     * @return mixed
     */
    public function handleProviderCallback(SocialGoogleAccountService $service)
    {
        //try {
            $provide_user = Socialite::driver('google')->user();

            $existe = $service->yaExiste($provide_user);

            $user = $service->createOrGetUser($provide_user);
            auth()->login($user);
    
            if (!$existe){
                return view('auth.choiceTypeAccount');
            }            
            return redirect()->to('/home');
            
        //} catch (\Exception $e) {
            //echo json_encode($e);
            // return redirect('/login');
        //}
    }
}
