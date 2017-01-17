<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Transaction;
use Validator;

class TransactionController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {

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

        $validator = Validator::make($request->all(), [
            'symbol' => 'required|string',
            'qty' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()->all(),
                'status' => 'FAILED'
            ],422);
        }

        $transaction_id = Transaction::create([
            'user_id' => Auth::id(),
            'type' => 'buy',
            'symbol' => $request->get('symbol'),
            'qty' => $request->get('qty'),
            'price' =>  $request->get('price'),
        ]);

        return response()->json([
            'data' => [
                'id' => $transaction_id
            ],
            'status' => 'OK'
        ]);
    }
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function sell(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'symbol' => 'required|string',
            'qty' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()->all(),
                'status' => 'FAILED'
            ],422);
        }

        $transaction_id = Transaction::create([
            'user_id' => Auth::id(),
            'type' => 'sell',
            'symbol' => $request->get('symbol'),
            'qty' => $request->get('qty'),
            'price' =>  $request->get('price'),
        ]);

        return response()->json([
            'data' => [
                'id' => $transaction_id
            ],
            'status' => 'OK'
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
}
