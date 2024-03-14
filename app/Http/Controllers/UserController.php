<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function login(Request $request){
        $incomiongFields = $request->validate([
            'loginname'=> 'required',
            'loginpassword'=> 'required'

        ]);

        if(auth()->attempt(['name' => $incomiongFields['loginname'], 'password' => $incomiongFields['loginpassword']])){
            $request->session()->regenerate();

        }

        return redirect('/');
    }

    public function logout(){
        auth()->logout();
        return redirect('/');
    }
    public function register(Request $request){
        $incomiongFields = $request->validate([
            'name'=> ['required','min:3', 'max:10', Rule::unique('users','name')], 
            'email'=> ['required','email' , Rule::unique('users','email')],
            'password'=> ['required','min:5', 'max:200']
        ]);

        $incomiongFields['password'] = bcrypt($incomiongFields['password']);
        $user = User::create($incomiongFields);
        auth()-> login($user);
        return redirect('/');
    }
}