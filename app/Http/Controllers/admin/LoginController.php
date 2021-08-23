<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Models\Admin;
class LoginController extends Controller
{
    //
    public function getLogin(){
        return view('admin.Auth.login');
    }

    public function login(LoginRequest $request){

        $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {
           // notify()->success('تم الدخول بنجاح  ');
            return redirect() -> route('admin.dashboard');
        }
       // notify()->error('خطا في البيانات  برجاء المجاولة مجدا ');
        return redirect()->back()->with(['error' => 'هناك خطا بالبيانات']);
    }

// tinker save first admin
    // public function save(){
    //     $admin = new App\Models\Admin();
    //     $admin ->name = "Hesham Ashraf";
    //     $admin ->email = "heshamashraf971@gmail.com";
    //     $admin ->password = bcrypt("12345678");
    //     $admin ->save();
    // }
}


