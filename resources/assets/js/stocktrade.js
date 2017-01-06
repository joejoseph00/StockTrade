import Autocomplete from 'vue2-autocomplete-js';
import Axios from 'axios';

var Events = new Vue();

var autocomplete = Vue.extend({
    mixins: [Autocomplete],
    mounted: function(){
        this.$el.children[0].className = 'input autocomplete-input';
    }
});

Vue.component('stocktrade-watchlist', {
    template: `
    <div id="stocktrade-watchlist">
    <div class="tabs is-centered is-boxed">
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
    </div>
    <div id="tab-watchlist" v-if="!isLoading">
    <div v-if="isLoading">
    {{ messages.loading }}
    <div class="preloader preloader-indefinite"></div>
    </div>
    <div v-if="!isLoading">
    <modal btnText="Add Symbol">
    <span slot="header">Add Symbol to Watchlist</span>
    <label class="label">Search Symbol or Stock Name</label>
    <p class="control">
    <autocomplete
    url="/api/v1/stock/search"
    anchor="symbol"
    label="name"
    placeholder="e.g. GOOGL"
    class-name="input"
    :onSelect="getSymbolData"
    >
    </autocomplete>
    </p>
    <stockchart></stockchart>
    </modal>
    <ul id="stockitem-list" class="list-unstyled">
    <stock-item v-for="stock in stocks">{{ stock.symbol }}</stock-item>
    </ul>
    </div>
    </div>
    </div>
    `,
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
                    url : "http://localhost:8000/user/watchlist",
                    response : null
                },
                getStockData : {
                    url : "http://localhost:8000/api/v1/stock/data/",
                    response : null
                }
            }
        }
    },
    components: {
        autocomplete : autocomplete,
        'stock-item' : {
            name : 'stock-item',
            template : '<li><slot></slot></li>'
        }
    },
    methods: {
        getWatchList() {
            // Fetch Symbols from Database
            var self = this;
            Axios.get(self.api.getWatchlist.url).then(function(response){
                self.api.getWatchlist.response = response;
                self.stocks = response.data;
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
        this.getWatchList();

        Events.$on('moduleLoaded',function(){
            console.log('Loaded');
        });
    }
});

Vue.component('modal',{
    name : 'modal',
    props : [
        'btnText'
    ],
    data : function(){
        return {
            isModalOpen : false
        }
    },
    template : `
    <div>
    <button @click=" isModalOpen = true " class="button is-small">{{ btnText }}</button>
    <div class="modal is-active" v-if="isModalOpen">
    <div class="modal-background" @click=" isModalOpen = false "></div>
    <div class="modal-card">
    <header class="modal-card-head">
    <p class="modal-card-title"><slot name="header"></slot></p>
    <button class="delete" @click=" isModalOpen = false "></button>
    </header>
    <section class="modal-card-body">
    <slot></slot>
    </section>
    <footer class="modal-card-foot">
    <a class="button is-primary">Save changes</a>
    <a class="button" @click=" isModalOpen = false ">Cancel</a>
    </footer>
    </div>
    </div>
    </div>
    `
});

Vue.component('stockchart',{
    template: `
    <div id="stockchart">
    <div v-if="isLoading">
    {{ messages.loading }}
        <progress class="progress is-small" v-bind:class="{ 'is-danger' : isLoadingFailed }" v-bind:value="loadingPercent" max="100">{{ loadingPercent }}%</progress>
    </div>
    <div v-if="!isLoading && isSearched">
        <div class="content">
            <h1 class="title is-2">{{ stockData.details['symbol'] }}</h1>
            <h5 class="subtitle">{{ stockData.details['name'] }}</h5>

            <div class="columns">
              <div class="column">
                  <h3 class="title">{{ stockData.details['open'] }}</h3>
                  <h6 class="subtitle is-6">Open</h6>
              </div>
              <div class="column">
                  <h3 class="title">{{ stockData.details['previous-close'] }}</h3>
                  <h6 class="subtitle is-6">Previous Close</h6>
              </div>
              <div class="column">
                  <h3 class="title">{{ stockData.details['revenue'] }}</h3>
                  <h6 class="subtitle is-6">Revenue</h6>
              </div>
              <div class="column">
                  <h3 class="title">{{ stockData.details['change'] }}</h3>
                  <h6 class="subtitle is-6">{{ stockData.details['percent-change'] }}</h6>
              </div>
            </div>
            <p>{{ stockData.profile.longBusinessSummary }}</p>
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">Statistics<th>
                    </tr>
                </thead>
                <tbody>
                        <tr><td>Market Capitalization</td><td>{{ stockData.details['market-capitalization'] }}</td></tr>
                        <tr><td>Outstanding Shares</td><td>{{ stockData.details['outstanding-shares'] }}</td></tr>
                        <tr><td>Float Shares</td><td>{{ stockData.details['float-shares'] }}</td></tr>
                        <tr><td>Last Trade Size</td><td>{{ stockData.details['last-trade-size'] }}</td></tr>
                        <tr><td>Stock Exchange</td><td>{{ stockData.details['stock-exchange'] }}</td></tr>
                        <tr><td>Day High</td><td>{{ stockData.details['day-high'] }}</td></tr>
                        <tr><td>Day Low</td><td>{{ stockData.details['day-low'] }}</td></tr>
                        <tr><td>Year High</td><td>{{ stockData.details['year-high'] }}</td></tr>
                        <tr><td>Year High Change</td><td>{{ stockData.details['year-high-change'] }} ({{ stockData.details['year-high-change-percent'] }})</td></tr>
                        <tr><td>Year Low</td><td>{{ stockData.details['year-low'] }}</td></tr>
                        <tr><td>Year Low Change</td><td>{{ stockData.details['year-low-change'] }} ({{ stockData.details['year-low-change-percent'] }})</td></tr>
                        <tr><td>Earnings per Share</td><td>{{ stockData.details['earnings-per-share'] }}</td></tr>
                        <tr><td>Ask Price</td><td>{{ stockData.details['ask-price'] }}</td></tr>
                        <tr><td>Ask Size</td><td>{{ stockData.details['ask-size'] }}</td></tr>
                        <tr><td>Bid Price</td><td>{{ stockData.details['bid-price'] }}</td></tr>
                        <tr><td>Bid Size</td><td>{{ stockData.details['bid-size'] }}</td></tr>
                        <tr><td>Average Daily Volume</td><td>{{ stockData.details['average-daily-volume'] }}</td></tr>
                </tbody>
            </table>

            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">Other Details<th>
                    </tr>
                </thead>
                <tbody>
                        <tr><td>Website</td><td><a :href="stockData.profile['website']" target="_blank">{{ stockData.profile['website'] }}</a></td></tr>
                        <tr><td>Address</td><td>{{ stockData.profile['address1'] }}</td></tr>
                        <tr><td>City</td><td>{{ stockData.profile['city'] }}</td></tr>
                        <tr><td>State</td><td>{{ stockData.profile['state'] }}</td></tr>
                        <tr><td>Country</td><td>{{ stockData.profile['country'] }}</td></tr>
                        <tr><td>ZIP</td><td>{{ stockData.profile['zip'] }}</td></tr>
                        <tr><td>Industry</td><td>{{ stockData.profile['industry'] }}</td></tr>
                        <tr><td>Fulltime Employees</td><td>{{ stockData.profile['fullTimeEmployees'] }}</td></tr>
                        <tr><td>Phone</td><td>{{ stockData.profile['phone'] }}</td></tr>
                        <tr><td>Sector</td><td>{{ stockData.profile['sector'] }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>`,
    data: function(){
        return {
            isSearched : false,
            isLoading: false,
            isLoadingFailed : false,
            progressInterval : null,
            messages : {
                loading : 'Initializing quote data...',
            },
            loadingPercent : 0,
            stockData : []
        }
    },
    methods: {
        displayChart(data){

        }
    },
    created: function(){

        var self = this;

        Events.$on('symbolDataLoading',function(){
            self.messages.loading = "Initializing quote data...";
            self.isLoadingFailed = false;
            self.isLoading = true;
            self.isSearched = true;
            self.progressInterval = setInterval(function(){
                self.loadingPercent += 1;
                if(self.loadingPercent>100) self.loadingPercent = 0;
            },50);
        });

        Events.$on('symbolDataLoaded',function(data){
            clearInterval(self.progressInterval);
            self.loadingPercent = 100;
            self.isLoadingFailed = false;
            self.isLoading = false;
            self.stockData = data;
            self.displayChart();
        });

        Events.$on('symbolDataLoadFailed',function(symbol){
            clearInterval(self.progressInterval);
            self.loadingPercent = 100;
            self.isLoading = true;
            self.isLoadingFailed = true;
            self.messages.loading = "Failed to fetch data for symbol " + symbol;
        });
        
    }
});

const stocktrade = new Vue({
    el: '#stocktrade',
    data(){
        return {

        }
    },
    components: { },
    created: function(){
        Events.$emit('moduleLoaded');
    }
});
