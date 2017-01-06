<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Curl;

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
        $options = explode(",",$options);
        $response = Curl::to('download.finance.yahoo.com/d/quotes.csv?s='.$symbol.'&f='.implode('',$options))
        ->get();

        $response = explode("\n", $response);

        $data = array_map(function($row) use ($options){
            $data = str_getcsv($row);
            if(count($data) === count($options)){
                $newdata = [];
                foreach ($data as $key => $value) {
                    $newdata[str_slug($this->options[$options[$key]])] = $data[$key];
                }

                if($newdata['name']=='N/A') return null;
                return $newdata;
            }
            return null;
        },$response);

        $data = array_filter($data, 'is_array');

        if(empty($data)){
            return response()->json([
                'error' => 'Failed to get data',
                'message' => 'Could not fetch statistics for ' . $symbol
            ]);
        }

        $baseURLQuote = "https://query2.finance.yahoo.com/v10/finance/quoteSummary/" .$symbol;

        $response = Curl::to($baseURLQuote)
        ->withData( [
            'formatted' => 'true',
            'crumb' => 'HLn18oo0lxL',
            'lang' => 'en-US',
            'region' => 'US',
            'modules' => implode(',',[
                'summaryProfile',
                'detail',
            ]),
            'corsDomain' => 'finance.yahoo.com',
        ] )
        ->asJson()
        ->get();

        if($response && count($response->quoteSummary->result)){
            $profile = $response->quoteSummary->result[0]->summaryProfile;
        }else{
            return response()->json([
                'error' => 'Failed to get data',
                'message' => 'Could not fetch profile for ' . $symbol
            ]);
        }


        return response()->json([
            'profile' => $profile,
            'details' => $data[0]
        ]);
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
