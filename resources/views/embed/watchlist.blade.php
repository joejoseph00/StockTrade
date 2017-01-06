@extends('templates/master-bulma')

@section('title', 'Watchlist')

@section('content')
@verbatim
<div class="container is-fluid">
    <div class="content">
            <h3>Demo Trading Account</h3>
            <div id="stocktrade"><stocktrade-watchlist></stocktrade-watchlist></div>
    </div>
</div>
@endverbatim
@endsection

@push('styles')
<link href="{{ asset('css/stocktrade-main.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('api/v1/js/stocktrade.js') }}"></script>
@endpush
