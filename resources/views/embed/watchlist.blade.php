@extends('templates/master')

@section('title', 'Watchlist')

    @section('content')
        @verbatim
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h3>Watchlist</h3>
                    <div id="stocktrade-watchlist" class="stocktrade-panel panel panel-default">
                        <div class="panel-heading">Favorites</div>
                        <div class="panel-body text-center" v-if="isLoading">
                            {{ messages.loading }}
                            <div class="preloader preloader-indefinite"></div>
                        </div>
                        <div class="panel-body" v-if="!isLoading">
                            <ul id="stockitem-list" class="list-unstyled">
                                <stockitem v-for="item in api.getWatchlist.response" :symbol="item"></stockitem>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endverbatim
    @endsection

    @push('styles')
        <link href="{{ asset('css/stocktrade.css') }}" rel="stylesheet">
    @endpush

    @push('scripts')
        <script type="text/javascript" src="https://vuejs.org/js/vue.js"></script>
        <script type="text/javascript" src="{{ asset('js/embed.js') }}"></script>
    @endpush
