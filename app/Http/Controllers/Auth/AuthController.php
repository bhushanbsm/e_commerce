<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;
use Validator;

class AuthController extends Controller
{
    public function register()
    {
        return view('pages.Auth.register');
    }

    public function storeUser(\App\Http\Requests\AuthRegistrationRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if (!$user) {
            return response(
                [
                    "code" => "412",
                    "status" => "failed",
                    "msg" => 'Unable to register user.'
                ],
                412
            );
        }

        return response(
            [
                "code" => "200",
                "status" => "success",
                "data" => $user
            ],
            200
        );
    }

    public function login()
    {
        return view('pages.Auth.login');
    }

    public function authenticate(\App\Http\Requests\AuthLoginRequest $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response(
                [
                    "code" => "200",
                    "status" => "success",
                    "data" => 'success'
                ],
                200
            );
        }
        return response(
            [
                "code" => "412",
                "status" => "failed",
                "msg" => 'User credentials does not match.'
            ],
            412
        );
    }

    public function logout() {
        Auth::logout();

        return redirect('/');
    }
}
