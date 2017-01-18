@extends('templates/master-bulma')

@section('title', 'Watchlist')

@section('content')
@verbatim
<section class="hero">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                Documentation
            </h1>
            <h2 class="subtitle">
                Stock Trade API
            </h2>
        </div>
    </div>
</section>
<div id="docs" class="container">

</div>
@endverbatim
@endsection

@push('styles')
<link href="{{ asset('css/stocktrade-main.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
var hostname = "{{ url('/') }}";
</script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('api/v1/js/docs.js') }}"></script>
@endpush
