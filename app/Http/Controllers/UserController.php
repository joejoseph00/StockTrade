<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        //
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
        // Check if all data are present
        $username = $request->get('username');
        $fullname = $request->get('fullname');
        $password = $request->get('password');

        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users|alpha_dash|min:6',
            'fullname' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()->all(),
                'status' => 'FAILED'
            ]);
        }

        $user = User::create([
            'username' => $username,
            'fullname' => $fullname,
            'password' => bcrypt($password),
        ]);
        return response()->json([
            'error' => [],
            'status' => 'OK',
            'data' => $user
        ]);


    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        //
    }



    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addToWatchlist($symbol)
    {
        return response()->json([
            'error' => [],
            'status' => 'OK'
        ]);
    }

    public function isUsernameAvailable(Request $request){

        $username = $request->get('username');

        if(empty($username)){
            return response()->json([
                'error' => ['Empty Username'],
                'status' => 'failed',
            ]);
        }

        $status = 'taken';

        if(User::where('username', $username)->count()>0){
            return response()->json([
                'error' => ['Username already taken'],
                'status' => 'taken',
            ]);
        }else{
            return response()->json([
                'error' => ['Username available'],
                'status' => 'available',
            ]);
        }

    }

}
