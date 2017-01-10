<template>
    <div id="stockchart">
        <div v-if="isLoading">
            {{ messages.loading }}
            <progress class="progress is-small" v-bind:class="{ 'is-danger' : isLoadingFailed }" v-bind:value="loadingPercent" max="100">{{ loadingPercent }}%</progress>
        </div>
        <div v-if="!isLoading && isSearched">
            <div class="content">
                <div class="has-text-centered">
                    <h1 class="title is-2">{{ stockData.details['symbol'] }}</h1>
                    <h5 class="subtitle">{{ stockData.details['name'] }}</h5>
                </div>
                <div class="columns">
                    <div v-if="!isWatched" class="column is-half is-offset-one-quarter">
                        <a v-if="!isAdding" class="button block is-fullwidth is-primary is-medium" :class=" { 'is-danger' : isAdded == 'error' , 'is-disabled' : isAdded } " @click="addToWatchlist(stockData.details['symbol'])" >
                            <span  class="icon is-small">
                                <i class="fa" :class=" { 'fa-check' : isAdded === true, 'fa-exclamation-triangle' : isAdded == 'error', 'fa-heart' : !isAdded } "></i>
                            </span>
                            <span v-if="isAdded === true ">Added to Watchlist</span>
                            <span v-else-if="isAdded == 'error'">Adding Failed</span>
                            <span v-else>Add to Watchlist</span>
                        </a>
                        <a v-else class="button block is-fullwidth is-primary is-medium is-loading"></a>
                    </div>
                    <div v-else class="column is-half is-offset-one-quarter">
                        <a v-if="!isRemoving" class="button block is-fullwidth is-danger is-medium" @click="removeFromWatchlist(stockData.details['symbol'])" >
                            <span  class="icon is-small">
                                <i class="fa" :class=" { 'fa-ban' : isAdded === true, 'fa-exclamation-triangle' : isAdded == 'error', 'fa-heart' : !isAdded } "></i>
                            </span>
                            <span v-if="isRemoved === true ">Stock Unfollowed</span>
                            <span v-else-if="isRemoved == 'error'">Unfollow Failed</span>
                            <span v-else>Unfollow Stock</span>
                        </a>
                        <a v-else class="button block is-fullwidth is-danger is-medium is-loading"></a>
                    </div>
                </div>
                <div class="columns has-text-centered">
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
                        <h3 class="title">{{ stockData.details['percent-change'] }}</h3>
                        <h6 class="subtitle is-6">Change</h6>
                    </div>
                </div>
                <p>{{ stockData.profile.longBusinessSummary }}</p>
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="2">Statistics</th>
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
                            <th colspan="2">Other Details</th>
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
    </div>
</template>

<script>
import Events from './Events.js';
import Axios from 'axios';

export default {
    name : 'stockchart',
    data: function(){
        return {
            isSearched : false,
            isLoading: false,
            isLoadingFailed : false,
            isWatched : false,
            progressInterval : null,
            messages : {
                loading : 'Initializing quote data...',
            },
            loadingPercent : 0,
            stockData : [],
            stockWatched : false,
            api : {
                addToWatchlist : hostname + "/api/v1/user/watchlist/add/",
                removeFromWatchlist : hostname + "/api/v1/user/watchlist/remove/"
            },
            isAdding : false,
            isAdded : false,
            isRemoving : false,
            isRemoved : false,
        }
    },
    methods: {
        addToWatchlist(symbol){
            var self = this;
            self.isAdding = true;
            self.isAdded = false;

            Axios.get(self.api.addToWatchlist + symbol).then(function(response){
                self.isAdding = false;
                if(response.status == 200 && response.data.status == 'OK'){
                    self.isAdded = true;
                    self.isWatched = true;
                    self.isRemoving = false;
                    self.isRemoved = false;
                }
                else{
                    self.isAdded = 'error';
                    setTimeout(function(){
                        self.isAdded = false;
                    },3000);
                }
            });
        },
        removeFromWatchlist(symbol){
            var self = this;
            self.isRemoving = true;
            self.isRemoved = false;

            Axios.get(self.api.removeFromWatchlist + symbol).then(function(response){
                self.isAdding = false;
                if(response.status == 200 && response.data.status == 'OK'){
                    self.isAdded = false;
                    self.isWatched = false;
                    self.isRemoved = true;
                    self.isRemoving = true;
                }
                else{
                    self.isRemoved = 'error';
                    setTimeout(function(){
                        self.isRemoved = false;
                    },3000);
                }
            });
        }
    },
    created: function(){

        var self = this;

        Events.$on('symbolDataLoading',function(){
            self.messages.loading = "Initializing quote data...";
            self.isLoadingFailed = false;
            self.isLoading = true;
            self.isSearched = true;

            self.isAdded = false;
            self.isWatched = false;
            self.isRemoved = false;
            self.isRemoving = false;

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
            self.isWatched = data.watched;
        });

        Events.$on('symbolDataLoadFailed',function(symbol){
            clearInterval(self.progressInterval);
            self.loadingPercent = 100;
            self.isLoading = true;
            self.isLoadingFailed = true;
            self.messages.loading = "Failed to fetch data for symbol " + symbol;
        });

    }
}
</script>
