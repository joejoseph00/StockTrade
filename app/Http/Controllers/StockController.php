<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Watchlist;
use Curl;
use Auth;
use Carbon\Carbon;

class StockController extends Controller
{

    public $options = [
        'a' => 'Ask Price',
        'b' => 'Bid Price',
        'o' => 'Open',
        'p' => 'Previous Close',
        'c1' => 'Change',
        'p2' => 'Percent Change',
        'g' => 'Day Low',
        'h' => 'Day High',
        's6' => 'Revenue',
        'k' => 'Year High',
        'j' => 'Year Low',
        'j5' => 'Year Low Change',
        'k4' => 'Year High Change',
        'j6' => 'Year Low Change Percent',
        'k5' => 'Year High Change Percent',
        'j1' => 'Market Capitalization',
        'f6' => 'Float Shares',
        'n' => 'Name',
        's' => 'Symbol',
        'x' => 'Stock Exchange',
        'j2' => 'Outstanding Shares',
        'v' => 'Volume',
        'a5' => 'Ask Size',
        'b6' => 'Bid Size',
        'k3' => 'Last Trade Size',
        'a2' => 'Average Daily Volume',
        'e' => 'Earnings Per Share'
    ];
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
        //
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($symbol,$options = "n,s,a,b,o,p,v,c1,p2,g,h,s6,k,j,j5,k4,j6,k5,j1,x,f6,j2,a5,b6,k3,a2,e")
    {
        $symbol = urlencode($symbol);

        // Check if Symbol already on database, if yes, check last updated time
        $isExist = Stock::where('symbol',$symbol)->first();

        $fetchData = true;
        $updateData = false;

        // Check if symbol in watchlist
        $isWatched = Watchlist::where([ 'user_id' => Auth::id(), 'stock_symbol' => $symbol ])->exists();


        if(!empty($isExist)){

            $MinutesAgo = Carbon::now()->diffInMinutes($isExist->updated_at);
            $threshold = 60; // minutes

            $fetchData = false;

            if($MinutesAgo>$threshold){
                $updateData = true;
                $fetchData = true;
            }

        }


        if($fetchData){

            // If symbol not yet in database, or if saved data was outdated already, proceed fetch

            $options = explode(",",$options);

            $options = [
                'range' => 'max',
                'interval' => '1d',
                'indicators' => 'quote',
                'includeTimestamps' => true,
                'corsDomain' => 'finance.yahoo.com'
            ];
            $url = 'https://query1.finance.yahoo.com/v7/finance/chart/GOOGL?'.http_build_query($options);

            // $response = Curl::to('download.finance.yahoo.com/d/quotes.csv?s='.$symbol.'&f='.implode('',$options))
            // ->get();
            $response = Curl::to($url)
            ->get();


            // $response = explode("\n", $response);
            $data = [];
            if(!empty($response)){
                $response = json_decode($response);

                if(isset($response->chart) && $response->chart->error==null){
                    $result = $response->chart->result;

                    if(is_array($result))
                    $result = array_shift($result);

                    $data = array_filter((array)$result->meta,function($key,$value){
                        if(in_array($key,[
                            'currentTradingPeriod',
                            'dataGranularity',
                            'validRanges'
                            ])) return false;
                            return true;
                        },ARRAY_FILTER_USE_BOTH);
                    }

                }

                // $data = array_map(function($row) use ($options){
                //     $data = str_getcsv($row);
                //     if(count($data) === count($options)){
                //
                //         $newdata = [];
                //         foreach ($data as $key => $value) {
                //             $newdata[str_slug($this->options[$options[$key]])] = $data[$key];
                //         }
                //
                //
                //
                //         if($newdata['name']=='N/A') return null;
                //         return $newdata;
                //     }
                //     return null;
                // },$response);

                // $data = array_filter($data, 'is_array');

                if(empty($data)){
                    return response()->json([
                        'error' => 'Failed to get data',
                        'message' => 'Could not fetch statistics for ' . $symbol
                    ]);
                }

                $baseURLQuote = "https://query2.finance.yahoo.com/v7/finance/quote";
                
                $infoStats = Curl::to($baseURLQuote)
                ->withData([
                    'formatted' => 'true',
                    'lang' => 'en-US',
                    'region' => 'US',
                    'symbols' => 'GOOGL',
                    'fields' => implode(',',[
                        // 'messageBoardId',
                        'longName',
                        'shortName',
                        'underlyingSymbol',
                        'underlyingExchangeSymbol',
                        'headSymbolAsString',
                        // 'regularMarketPrice',
                        // 'regularMarketChange',
                        // 'regularMarketChangePercent',
                        // 'regularMarketVolume',
                        // 'uuid'
                    ]),
                    'corsDomain' => 'finance.yahoo.com',
                ])
                ->asJson()
                ->get();

                if($infoStats && $infoStats->quoteResponse->error==null){
                    $infoStats = (array)$infoStats->quoteResponse->result[0];
                }else{
                    $infoStats = [];
                    return response()->json([
                        'error' => 'Failed to get data',
                        'message' => 'Could not fetch info for ' . $symbol
                    ]);
                }


                $baseURLQuote = "https://query2.finance.yahoo.com/v10/finance/quoteSummary/" .$symbol;

                $response = Curl::to($baseURLQuote)
                ->withData([
                    'formatted' => 'true',
                    'crumb' => 'HLn18oo0lxL',
                    'lang' => 'en-US',
                    'region' => 'US',
                    'modules' => implode(',',[
                        'summaryProfile',
                        'detail',
                        'assetProfile',
                        'financialData',
                        'defaultKeyStatistics'
                    ]),
                    'corsDomain' => 'finance.yahoo.com',
                ])
                ->asJson()
                ->get();

                if($response && count($response->quoteSummary->result)){
                    $profile = $response->quoteSummary->result[0]->assetProfile;
                    $detailedStats = [
                        'defaultKeyStatistics' => $response->quoteSummary->result[0]->defaultKeyStatistics,
                        'financialData' => $response->quoteSummary->result[0]->financialData,
                        'info' => $infoStats
                    ];
                }else{
                    return response()->json([
                        'error' => 'Failed to get data',
                        'message' => 'Could not fetch profile for ' . $symbol
                    ]);
                }

                // $details = $data[0];
                $details = $data;







                // If everythings ok, check if symbol already on DB
                if($updateData){
                    Stock::where('symbol',$symbol)->update([
                        'issuer' => $details['exchangeName'],
                        'type' => $details['instrumentType'],
                        'statistics' => json_encode($detailedStats),
                        'profile' => json_encode($profile),
                    ]);
                }else{
                    Stock::create([
                        'symbol' => $symbol,
                        'issuer' => $details['exchangeName'],
                        'name' => $infoStats['shortName'],
                        'type' => $details['instrumentType'],
                        'statistics' => json_encode($detailedStats),
                        'profile' => json_encode($profile),
                    ]);
                }

                return response()->json([
                    'profile' => $profile,
                    'details' => $detailedStats,
                    'watched' => $isWatched,
                    'status' => 'OK'
                ]);
            }else{

                // Return response from DB

                if(!empty($isExist)){
                    return response()->json([
                        'profile' => json_decode($isExist->profile),
                        'details' => json_decode($isExist->statistics),
                        'watched' => $isWatched,
                        'status' => 'OK'
                    ]);
                }

                return response()->json([
                    'status' => 'FAILED'
                ]);
            }

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
        * Display a listing of stock symbols.
        *
        * @return \Illuminate\Http\Response
        */
        public function search(Request $request)
        {
            $term = $request->input('q');
            if(empty($term)) return response()->json([]);

            $response = Curl::to('http://d.yimg.com/autoc.finance.yahoo.com/autoc?region=us&lang=en&query='.$term)
            ->asJson()
            ->get();

            if(!empty($response->ResultSet->Result)) return response()->json($response->ResultSet->Result);
            return response()->json([]);
        }

    }
