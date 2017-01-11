<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\User;
use App\Stock;
use App\Watchlist;
use Auth;

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
    public function getWatchlist()
    {

        $watchlist = Watchlist::select('stock_symbol as symbol')->where([
            'user_id' => Auth::id(),
            ])->get();

            foreach ($watchlist as $key => $stock) {

                $stockdata = \App::call('\App\Http\Controllers\StockController@show', [ 'symbol' => $stock['symbol'], 'options' => 'n,s,a,b,o,p,v,c1,p2,g,h,s6,k,j,j5,k4,j6,k5,j1,x,f6,j2,a5,b6,k3,a2,e' ]);



                $stock['data'] = Stock::where([
                    'symbol' => $stock['symbol']
                    ])->first();

                    $stock['data']['profile'] = json_decode($stock['data']['profile']);
                    $stock['data']['statistics'] = json_decode($stock['data']['statistics']);
                }

                return response()->json([
                    'error' => [],
                    'status' => 'OK',
                    'watchlist' => $watchlist
                ]);
            }


            /**
            * Store a newly created resource in storage.
            *
            * @param  \Illuminate\Http\Request  $request
            * @return \Illuminate\Http\Response
            */
            public function addToWatchlist($symbol)
            {

                Watchlist::firstOrCreate([
                    'user_id' => Auth::id(),
                    'stock_symbol' => $symbol
                ]);

                return response()->json([
                    'error' => [],
                    'status' => 'OK'
                ]);
            }

            /**
            * Store a newly created resource in storage.
            *
            * @param  \Illuminate\Http\Request  $request
            * @return \Illuminate\Http\Response
            */
            public function removeFromWatchlist($symbol)
            {

                Watchlist::where([
                    'user_id' => Auth::id(),
                    'stock_symbol' => $symbol
                ])
                ->delete();

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

            public function authenticate(Request $request){
                if (Auth::attempt(['username' => $request->get('username'), 'password' => $request->get('password')])) {
                    // Authentication passed...

                    $user = User::select('username','fullname')->where(['username' => $request->get('username')])->first();

                    return response()->json([
                        'error' => [],
                        'status' => 'OK',
                        'user' => $user
                    ]);
                }

                return response()->json([
                    'error' => ['Username or Password incorrect'],
                    'status' => 'FAILED',
                ]);
            }

            public function logout(){
                Auth::logout();

                return response()->json([
                    'error' => [],
                    'status' => 'OK',
                ]);
            }

            public function isLoggedIn(){
                if (Auth::check()) {
                    return response()->json([
                        'error' => [],
                        'status' => 'OK',
                        'user' => Auth::user()
                    ]);
                }

                return response()->json([
                    'error' => ['No user logged in.'],
                    'status' => 'FAILED',
                ]);
            }

        }
