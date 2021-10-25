<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class dashboard extends Controller
{
   public function index(){
   	return view('pages/dashboard');
   }
}
