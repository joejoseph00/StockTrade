<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use App\Stock;
use App\Watchlist;
use App\UserInfo;
use App\Transaction;
use Auth;
use Image;

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
    public function update(Request $request)
    {

        $validator = Validator::make($request->get('profile'), [
            'username' => [
                'alpha_dash',
                'required',
                'min:6',
                Rule::unique('users')->ignore(Auth::id()),
            ],
            'email' => 'email',
            'fullname' => 'required',
            'password' => 'required_with:password_confirmation|confirmed',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()->all(),
                'status' => 'FAILED'
            ]);
        }

        $toUpdate = [
            'username' => $request->profile['username'],
            'fullname' => $request->profile['fullname'],
        ];

        if(!empty($request->password)){
            $toUpdate['password'] = bcrypt($request->password);
        }

        User::find(Auth::id())->update($toUpdate);

        $userInfo = [
            'email' => $request->profile['email'],
            'country' => $request->profile['country'],
        ];

        foreach ($userInfo as $key => $value) {
            UserInfo::updateOrCreate([
                'user_id' => Auth::id(),
                'key' => $key,
            ],[
                'value' => $value
            ]);
        }


        return response()->json([
            'error' => [],
            'status' => 'OK',
            'data' => $request->all()
        ]);
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

                $stockdata = Stock::where([
                    'symbol' => $stock['symbol']
                ]
                )->first();

                if(isset($stockdata['profile'])) $stockdata['profile'] = json_decode($stockdata['profile']);
                if(isset($stockdata['statistics'])) $stockdata['statistics'] = json_decode($stockdata['statistics']);

                $watchlist[$key]['data'] = $stockdata ?: [];

                if(empty($watchlist[$key]['data'])) unset($watchlist[$key]);
            }

            return response()->json([
                'error' => [],
                'status' => 'OK',
                'watchlist' => $watchlist ?: []
            ]
        );
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

    public function getTransactions(){
        return response()->json([
            'data' => User::find(Auth::id())->transactions()->latest()->get()->map(function ($item, $key) {
                $item->type = strtoupper($item->type);
                $item->priceFormatted = '$' . number_format($item->price,2);
                $item->total = $item->price * $item->qty;
                $item->totalFormatted = '$' . number_format($item->total,2);
                $item->idFormatted = str_pad($item->id, 6, "0", STR_PAD_LEFT);
                $item->purchasedTimeAgo = $item->updated_at->diffForHumans();
                return $item;
            }),
            'status' => 'OK'
        ]);
    }

    public function portfolio(){

        $startingMoney = 100000;
        $cashValue = $startingMoney;
        $totalShares = 0;
        $totalGains = 0;

        $transactions = User::find(Auth::id())->transactions()->get()->map(function ($item, $key) {
            $item->type = strtoupper($item->type);
            $item->priceFormatted = '$' . number_format($item->price,2);

            $item->total = $item->price * $item->qty;
            $item->totalFormatted = '$' . number_format($item->total,2);
            $item->qtyFormatted = number_format($item->qty,0);
            if($item->type=='SELL') $item->totalFormatted = '(' . $item->totalFormatted . ')';
            if($item->type=='SELL') $item->qtyFormatted = '(' . $item->qtyFormatted . ')';

            $item->idFormatted = str_pad($item->id, 6, "0", STR_PAD_LEFT);
            $item->purchasedTimeAgo = $item->updated_at->diffForHumans();
            return $item;
        });
        $stocks = [];
        foreach ($transactions as $key => $entry) {
            if(!isset($stocks[$entry->symbol]))
            $stocks[$entry->symbol] = [
                'symbol' => $entry->symbol,
                'qty' => 0,
                'purchasedPriceTotal' => 0,
                'history' => [],
                'gain' => 0
            ];


            if(!isset($stocks[$entry->symbol]['statistics'])){
                $stockData = Stock::select('statistics','name')->where('symbol',$entry->symbol)->first();
                // details.statistics.financialData.currentPrice
                $stocks[$entry->symbol]['statistics'] = json_decode($stockData->statistics);
            }

            if($entry->type==='BUY'){
                $stocks[$entry->symbol]['qty'] += $entry->qty;
                $totalShares += $entry->qty;

                $currentPriceTotalhere = ($stocks[$entry->symbol]['statistics']->financialData->currentPrice->raw * $entry->qty);
                $purchasedPriceTotalhere = ($entry->price * $entry->qty);

                $stocks[$entry->symbol]['purchasedPriceTotal'] += $purchasedPriceTotalhere;

                $gainTotalHere = ($currentPriceTotalhere  - $purchasedPriceTotalhere);

                $stocks[$entry->symbol]['gain'] += $gainTotalHere;
                $totalGains += $gainTotalHere;

                $cashValue -= $purchasedPriceTotalhere;
            }
            else{
                $stocks[$entry->symbol]['qty'] -= $entry->qty;
                $totalShares -= $entry->qty;

                // $stocks[$entry->symbol]['gain'] += ($entry->price * $entry->qty);

                $currentPriceTotalhere = ($stocks[$entry->symbol]['statistics']->financialData->currentPrice->raw * $entry->qty);
                $purchasedPriceTotalhere = ($entry->price * $entry->qty);
                $gainTotalHere = ($currentPriceTotalhere  - $purchasedPriceTotalhere);

                $totalGains += $gainTotalHere;
                $cashValue += $currentPriceTotalhere;
            }

            $stocks[$entry->symbol]['history'][] = $entry;

        }

        foreach ($stocks as $key => $stock) {

            $stock['name'] = $stockData->name;
            $stock['currentPrice'] = number_format($stock['statistics']->financialData->currentPrice->raw,2);
            $stock['gainPercent'] = $stock['gain']!=0 ? number_format(($stock['gain'] / $stock['purchasedPriceTotal']) * 100,2) : '0.00';
            $stock['purchasedPriceTotal'] = number_format($stock['purchasedPriceTotal'],2);
            $stock['currentPriceTotal'] = number_format($stock['qty'] * $stock['statistics']->financialData->currentPrice->raw,2);
            $stock['gain'] = number_format($stock['gain'],2);
            $stock['history'] = array_reverse($stock['history']);

            $stocks[$key] = $stock;

        }

        uasort($stocks, function($a,$b){
            if ($a['qty'] == $b['qty']) {
                return 0;
            }
            return ($a['qty'] > $b['qty']) ? -1 : 1;
        });

        $portfolioData = [
            'totalShares' => $totalShares,
            'totalCompanies' => count($stocks),
            'startingMoney' => $startingMoney,
            'totalGains' => $totalGains,
            'totalGainsFmt' => $totalGains >= 0 ? '$' . number_format(abs($totalGains),2) : '(-$'.number_format(abs($totalGains),2).')',
            'totalGainsPercent' => $totalGains < 0 ? '(' . (number_format(($totalGains / $startingMoney) * 100,2) . '%') . ')' : (number_format(($totalGains / $startingMoney) * 100,2) . '%'),
            'accountValue' => $startingMoney + $totalGains,
            'cashValue' => $cashValue,
            'cashValueFmt' => $cashValue >= 0 ? '$' . number_format(abs($cashValue),2) : '(-$'.number_format(abs($cashValue),2).')',
            'accountValueFmt' => (($startingMoney + $totalGains) >= 0 ? '$' . number_format(abs($startingMoney + $totalGains),2) : '(-$'.number_format(abs($startingMoney + $totalGains),2).')'),
        ];

        foreach ($portfolioData as $key => $value) {
            UserInfo::updateOrCreate([
                'user_id' => Auth::id(),
                'key' => $key,
            ],[
                'value' => $value
            ]);
        }

        return response()->json([
            'stocks' => $stocks,
            'portfolio' => $portfolioData
        ]);


    }

    function getMaxBuy(Request $request){

        if($request->has('symbol')){
            $stock = Stock::select('price')->where('symbol',$request->symbol)->firstOrFail();
            $price = $stock->price ;

            $cashvalue = $this->portfolio()->getData()->portfolio->cashValue;

            return response()->json([
                'error' => '',
                'status' => 'OK',
                'result' => [
                    'symbol' => $request->symbol,
                    'maxBuy' => floor($cashvalue / $price),
                    'maxBuyPrice' => floor($cashvalue / $price) * $price,
                    'maxBuyPriceFmt' => '$' . number_format(floor($cashvalue / $price) * $price,2),
                ]
            ]);
        }

        return response()->json([
            'error' => 'No symbol specified. Use `symbol` parameter',
            'status' => 'FAILED'
        ]);
    }


    function getMaxSell(Request $request){

        if($request->has('symbol')){
            $logs = Transaction::select('qty','type')->where('symbol',$request->symbol)->get();
            $qty = 0;
            foreach ($logs as $key => $log) {
                if($log->type === 'buy'){
                    $qty += $log->qty;
                }
                else{
                    $qty -= $log->qty;
                }
            }

            return response()->json([
                'error' => '',
                'status' => 'OK',
                'result' => [
                    'symbol' => $request->symbol,
                    'maxSell' => $qty,
                ]
            ]);
        }

        return response()->json([
            'error' => 'No symbol specified. Use `symbol` parameter',
            'status' => 'FAILED'
        ]);
    }

    function profile(){

        $info = UserInfo::select('key','value')->where('user_id',Auth::id())->get();



        $profile = [
            'fullname' => Auth::user()->fullname,
            'email' => '',
            'username' => Auth::user()->username,
        ];

        $info = array_combine(array_pluck($info,'key'),array_pluck($info,'value'));

        $profile = array_merge($profile,$info);

        return response()->json([
            'error' => '',
            'status' => 'OK',
            'profile' => $profile
        ]);
    }

    public function uploadProfile(Request $request){
        $filename = 'avatar-'.Auth::id() . '-' . date('YmdHis') .'.jpg';
        $path = 'avatar/';
        $img = Image::make($request->file('photo'))
        ->resize(null, 128, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })
        ->crop(128, 128);
        $img->save($path . $filename);

        UserInfo::updateOrCreate([
            'user_id' => Auth::id(),
            'key' => 'avatar',
        ],[
            'value' => asset($path . $filename)
        ]);

        return response()->json([
            'error' => '',
            'status' => 'OK',
            'profile' => asset($path . $filename)
        ]);
    }

}
