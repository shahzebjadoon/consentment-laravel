<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('frontend.new.register');
    }
}