@extends('templates/master')

@section('title', 'Dashboard')


    @section('content')
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Demo Trading Account</h1>
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- <iframe src="http://localhost:8000/embed/watchlist" width="100%" height="100%" frameborder="0" allowtransparency="true" scrolling="no" /> -->
                            <div id="stocktrade"><stocktrade-watchlist></stocktrade-watchlist></div>
                            <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
                            <script type="text/javascript" src="{{ asset('api/v1/js/stocktrade.js') }}"></script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
