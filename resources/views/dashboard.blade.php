@extends('templates/master')

@section('title', 'Dashboard')


    @section('content')
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Dashboard</h1>
                    <div class="row">
                        <div class="col-sm-6">
                            <iframe src="http://localhost:8000/embed/watchlist" width="100%" height="100%" frameborder="0" allowtransparency="true" scrolling="no" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
