<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AccountController extends Controller
{
    //nampilin halaman register
    public function register(){
        return view('account.register');
    }

    //proses registrasi user
    public function processRegister(Request $request){
        $validator = validator::make($request->all(),[
            'name' =>'required|min:3|max:255',
            'email' =>'required|email|unique:users',
            'password' =>'required|confirmed|min:6',
            'password_confirmation' =>'required',
        ]);


        if($validator->fails()) {
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }

        //kalo validasi berhasil, bakal proses registrasi disini
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('account.login')->with('success', 'You have been registered successfully');
    }

    //nampilin halaman login
    public function login(){
        return view('account.login');
    }

    public function authenticate(Request $request){

        $validator = Validator::make($request->all(),[
            'email' =>'required|email',
            'password' =>'required',
        ]);

        if($validator->fails()) {
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }

        //cek kecocokan email dan password
        
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return redirect()->route('account.profile');
        } else {
            return redirect()->route('account.login')->with('error', 'Invalid email or password');
        }

    }
    public function profile(){
        return view('account.profile');
    }
}
