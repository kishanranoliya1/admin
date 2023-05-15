<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use App\Models\InstaAdmin_Auth;

use Validator;
use Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if ((Session::get('isLoggedIn')) &&(Session::get('user'))) {

            return redirect('/admin/uploaded-files');

        } else {

            return view('/auth/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $input = $request->all();
        $rules = [
            'email' => 'required|string',
            'password' => 'required',
        ];

        $validation = Validator::make($input, $rules);
        if ($validation->passes()) 
        {
            $user = InstaAdmin_Auth::where('email', $input['email'])->first();
            
            if ($user)
            {
                if (!Hash::check($input['password'], $user->password))
                {
                    return back()->withErrors(['Invalid Credentials']);

                } else {
                    Session::put('isLoggedIn', true);                  
                    Session::put('user', $user);
                    Session::save();
                    return redirect('/admin/uploaded-files');                
                }
            } else{

                return back()->withErrors(['Invalid Credentials']);
            }
        } else {

            $errors = $validation->errors();
            return back()->withErrors($errors);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */

    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */

    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */

    
    public function signOut(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }
    
   
}
