@extends('templates/master-bulma')

@section('title', 'Watchlist')

    @section('content')
        @verbatim
        <div id="stocktrade"></div>
        @endverbatim
    @endsection

    @push('styles')
        <link href="{{ asset('css/stocktrade-main.css') }}" rel="stylesheet">
    @endpush

    @push('scripts')
        <script>
        var hostname = "{{ url('/') }}";
        </script>
        <!-- Resources -->
        
        
        
        
        <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
        
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        <script type="text/javascript" src="{{ asset('api/v1/js/stocktrade.js') }}"></script>
    @endpush
