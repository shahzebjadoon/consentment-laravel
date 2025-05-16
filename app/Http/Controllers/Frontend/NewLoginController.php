<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.new.login');
    }


  



}