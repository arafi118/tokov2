<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
       $hari_ini = \Carbon\Carbon::now()->format('l').','.\Carbon\Carbon::now()->format('d F Y');
      
       return view('backend.admin.home',compact('hari_ini'));
    }
}
