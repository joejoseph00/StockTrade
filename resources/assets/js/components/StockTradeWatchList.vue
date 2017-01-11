<template>
    <div id="stocktrade">
        <section class="hero is-primary">
            <div class="hero-head">
                <div class="nav-right nav-menu">
                    <a class="nav-item is-active" @click="onLogout">
                        Logout Account
                    </a>
                </div>
            </div>
            <div class="hero-body">
                <div class="container">
                    <div class="columns is-vcentered">
                        <div class="column">
                            <p class="title">
                                Demo Trading Account
                            </p>
                            <p class="subtitle">
                                Compete, Risk Free with <strong>$100,000</strong> in Virtual Cash
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-foot">
                <div class="container">
                    <nav class="tabs is-centered is-boxed">
                        <ul>
                            <li>
                                <a>
                                    <span class="icon is-small"><i class="fa fa-dashboard"></i></span>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="is-active">
                                <a>
                                    <span class="icon is-small"><i class="fa fa-star"></i></span>
                                    <span>Watchlist</span>
                                </a>
                            </li>
                            <li>
                                <a>
                                    <span class="icon is-small"><i class="fa fa-area-chart"></i></span>
                                    <span>My Portfolio</span>
                                </a>
                            </li>
                            <li>
                                <a>
                                    <span class="icon is-small"><i class="fa fa-clock-o"></i></span>
                                    <span>Transaction History</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

        </section>
        <div id="tab-watchlist" v-if="!isLoading">
            <div v-if="isLoading">
                {{ messages.loading }}
                <div class="preloader preloader-indefinite"></div>
            </div>
            <div class="section content" v-if="!isLoading">
                <div class="container">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">
                                Favorite Stocks
                            </p>
                        </header>
                        <div class="card-content">
                            <div class="content">
                                <div v-if="stocks.length" id="stockitem-list" class="columns">
                                    <stock-item v-for="stock in stocks" :symbol="stock.symbol" :details="stock.data"></stock-item>
                                </div>
                                <div v-else class="has-text-centered">
                                    <blockquote>
                                        <h1><span class="icon is-large">
                                            <i class="fa fa-meh-o"></i>
                                        </span><br>No Followed Stocks yet.</h1>Sad to hear that you're not following any stocks yet. <br>Start getting updates from your favorite stocks by clicking the button below.</blockquote>
                                    </div>
                                </div>
                            </div>
                            <footer class="card-footer">
                                <div class="column is-one-third is-offset-one-third">
                                    <modal btnText="Add Symbol" btnClass="card-footer-item is-fullwidth">
                                        <template slot="header">Add Symbol to Watchlist</template>
                                        <label class="label">Search Symbol or Stock Name</label>
                                        <p class="control">
                                            <autocomplete
                                            url="/api/v1/stock/search"
                                            anchor="symbol"
                                            label="name"
                                            placeholder="e.g. GOOGL"
                                            class-name="input"
                                            :onSelect="getSymbolData"
                                            ></autocomplete>
                                        </p>
                                        <preview></preview>
                                    </modal>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
    import Autocomplete from 'vue2-autocomplete-js';
    import Events from './Events.js';
    import Preview from './StockTradeWatchListPreview.vue';
    import StockItems from './StockWatchListItems.vue';
    import Axios from 'axios';
    import Modal from './utils/Modal.vue';

    var autocomplete = Vue.extend({
        mixins: [Autocomplete],
        mounted: function(){
            var self = this;
            self.$el.children[0].className = 'input autocomplete-input';
            Events.$on('retrySymbolDataFetch',function(){
                self.type = self.$el.children[0].value;
                self.onSelect({
                    symbol : self.$el.children[0].value
                });
            });
        }
    });

    export default {
        name: 'stocktrade-watchlist',
        data: function(){
            return {
                isLoading: true,
                message: 'Hello Vue!',
                messages : {
                    loading : 'Getting favorite quotes...'
                },
                stocks : [],
                api : {
                    getWatchlist : {
                        url : hostname + "/api/v1/user/watchlist",
                        response : null
                    },
                    getStockData : {
                        url : hostname + "/api/v1/stock/data/",
                        response : null
                    },
                    logoutAccount : {
                        url : hostname + "/api/v1/user/logout",
                        response : null
                    }
                }
            }
        },
        components: {
            autocomplete,
            'modal' : Modal,
            'preview' : Preview,
            'stock-item' : StockItems
        },
        methods: {
            onLogout(){
                var self = this;
                Axios.post(self.api.logoutAccount.url).then(function(response){
                    if(response.status===200){
                        if(response.data.status=="OK"){
                            Events.$emit('userLoggedOut');
                        }
                    }
                });
            },
            getWatchList() {
                // Fetch Symbols from Database
                var self = this;
                Axios.get(self.api.getWatchlist.url).then(function(response){
                    self.api.getWatchlist.response = response;
                    self.stocks = response.data.watchlist;
                    self.isLoading = false;
                }).catch(function (error) {
                    self.messages.loading = 'Error: Failed getting watchlist';
                    self.api.getWatchlist.response = [];
                });;
            },
            getSymbolData(result){
                if(result.symbol!=""){
                    // Fetch Symbols from Database
                    var self = this;
                    Events.$emit('symbolDataLoading',result.symbol);
                    Axios.get(self.api.getStockData.url + result.symbol).then(function(response){
                        if(typeof response.data.error !== "undefined"){
                            Events.$emit('symbolDataLoadFailed',result.symbol);
                            return false;
                        }else{
                            Events.$emit('symbolDataLoaded',response.data);
                            self.api.getStockData.response = response;
                            return true;
                        }
                    }).catch(function (error) {
                        Events.$emit('symbolDataLoadFailed',result.symbol);
                    });
                }
            }
        },
        created: function(){

            var self = this;
            this.getWatchList();

            Events.$on('symbolWatchlistAdded',function(){
                self.getWatchList();
            });
            Events.$on('symbolWatchlistRemoved',function(){
                self.getWatchList();
            });


        }
    }
    </script>
