@extends('templates/master')

@section('title', 'Dashboard')

    @section('content')
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <script id="dm-stock-trade-js" src="{{ url('/js/embed.js') }}"></script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
