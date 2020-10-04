<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
         // first check if you are loggedin and admin user ...
        //return Auth::check();

        if (!Auth::check() && ($request->is(['admin']) || $request->is('admin/*'))) {
           // return 'here';
            return redirect('/login');
        }

        if (!Auth::check() && !($request->is(['admin']) || $request->is('admin/*'))) {

            return view('welcome');
        }
        // you are already logged in... so check for if you are an admin user..
        $user = Auth::user();
        if ($user->userType == 'User') {
            return redirect('/login');
        }

        //if user is already logged in
        if ($request->path() == 'login') {
            return redirect('/admin');
        }
        return view('welcome');
    }
}
