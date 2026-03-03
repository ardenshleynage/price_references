<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
/* use Illuminate\Http\Request; */

class Appcontroller extends Controller
{
    //
    public function Login(): View
    {
        return view('login.login');
    }
    public function forgetPassword(): View
    {
        return view('login.password_forget');
    }

    public function contactIt(): View
    {
        return view('login.contact_it');
    }

    public function index(): View
    {
        return view('index');
    }
}
