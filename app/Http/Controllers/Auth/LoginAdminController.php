<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Route;

class LoginAdminController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        } else {
        
         return view('backend.auth.login_admin');
        }
    }

    public function doLogin(Request $request)
    {
      $messages = [
         'email.required'=>'Email Harus Diisi',
         'password.required'=>'Password Harus Diisi'
        ];

      $this->validate($request, [
        'email'   => 'required|email',
        'password' => 'required'
      ],$messages);
     
      if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
        
        return redirect()->intended(route('admin.home'));
      } 
    
      $request->session()->flash('error','Kredensial anda tidak sesuai dengan data kami');

      return redirect()->back()->withInput($request->only('email', 'remember')); 
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}
