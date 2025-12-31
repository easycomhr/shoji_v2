<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {

            $authUser = Auth::user();
            $authRole = $authUser->role ?? null;
            if(!$authUser->is_login){
                Auth::logout();
                return redirect()->back()->with(['error' => __("The username or password is incorrect!")]);
            }

            $this->userService->saveLastedLogin();

            // Authentication passed...
            return redirect()->route('admin.dashboard.index');
        }


        return redirect()->back()->with(['error' => __("The username or password is incorrect!")]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
