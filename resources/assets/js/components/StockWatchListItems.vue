<template lang="html">
    <div class="column is-one-quarter">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    {{ symbol }}
                </p>
                <a class="card-header-icon">
                    <span class="icon">
                        <i class="fa fa-angle-down"></i>
                    </span>
                </a>
            </header>
            <div class="card-content">
                <div class="content">
                    <p class="is-small has-text-centered is-block">{{ details.statistics['name'] }}</p>
                    <nav class="level is-mobile">
                        <div class="level-item has-text-centered">
                            <div>
                                <p class="heading">Open</p>
                                <p class="title">{{ details.statistics['open'] }}</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                                <p class="heading">Close</p>
                                <p class="title">{{ details.statistics['previous-close'] }}</p>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
            <footer class="card-footer">
                <div class="card-footer-item">
                    <modal btnText="Buy" btnClass="is-block">
                        <template slot="header">Buy {{ details.statistics['symbol'] }} at {{ details.statistics['open'] }}</template>
                        <stock-buy
                        :symbol="details.statistics['symbol']"
                        :name="details.statistics['name']"
                        :price="details.statistics['open']"
                        :minimal="true"
                        :minheight="false"
                        ></stock-buy>
                    </modal>
                </div>
                <a class="card-footer-item">Sell</a>
                <modal btnText="More Info" btnClass="card-footer-item">
                    <template slot="header">{{ details.statistics['symbol'] }} : {{ details.statistics['name'] }}</template>
                    <div class="content">
                        <div class="has-text-centered">
                            <h1 class="title is-2">{{ details.statistics['symbol'] }}</h1>
                            <h5 class="subtitle">{{ details.statistics['name'] }}</h5>
                        </div>
                        <div class="columns">
                            <div v-if="!isWatched" class="column is-half is-offset-one-quarter">
                                <a v-if="!isAdding" class="button block is-fullwidth is-primary is-medium" :class=" { 'is-danger' : isAdded == 'error' , 'is-disabled' : isAdded } " @click="addToWatchlist(details.statistics['symbol'])" >
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
                                <a v-if="!isRemoving" class="button block is-fullwidth is-danger is-medium" @click="removeFromWatchlist(details.statistics['symbol'])" >
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
                                <h3 class="title">{{ details.statistics['open'] }}</h3>
                                <h6 class="subtitle is-6">Open</h6>
                            </div>
                            <div class="column">
                                <h3 class="title">{{ details.statistics['previous-close'] }}</h3>
                                <h6 class="subtitle is-6">Previous Close</h6>
                            </div>
                            <div class="column">
                                <h3 class="title">{{ details.statistics['revenue'] }}</h3>
                                <h6 class="subtitle is-6">Revenue</h6>
                            </div>
                            <div class="column">
                                <h3 class="title">{{ details.statistics['percent-change'] }}</h3>
                                <h6 class="subtitle is-6">Change</h6>
                            </div>
                        </div>
                        <p>{{ details.profile.longBusinessSummary }}</p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="2">Statistics</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Market Capitalization</td><td>{{ details.statistics['market-capitalization'] }}</td></tr>
                                <tr><td>Outstanding Shares</td><td>{{ details.statistics['outstanding-shares'] }}</td></tr>
                                <tr><td>Float Shares</td><td>{{ details.statistics['float-shares'] }}</td></tr>
                                <tr><td>Last Trade Size</td><td>{{ details.statistics['last-trade-size'] }}</td></tr>
                                <tr><td>Stock Exchange</td><td>{{ details.statistics['stock-exchange'] }}</td></tr>
                                <tr><td>Day High</td><td>{{ details.statistics['day-high'] }}</td></tr>
                                <tr><td>Day Low</td><td>{{ details.statistics['day-low'] }}</td></tr>
                                <tr><td>Year High</td><td>{{ details.statistics['year-high'] }}</td></tr>
                                <tr><td>Year High Change</td><td>{{ details.statistics['year-high-change'] }} ({{ details.statistics['year-high-change-percent'] }})</td></tr>
                                <tr><td>Year Low</td><td>{{ details.statistics['year-low'] }}</td></tr>
                                <tr><td>Year Low Change</td><td>{{ details.statistics['year-low-change'] }} ({{ details.statistics['year-low-change-percent'] }})</td></tr>
                                <tr><td>Earnings per Share</td><td>{{ details.statistics['earnings-per-share'] }}</td></tr>
                                <tr><td>Ask Price</td><td>{{ details.statistics['ask-price'] }}</td></tr>
                                <tr><td>Ask Size</td><td>{{ details.statistics['ask-size'] }}</td></tr>
                                <tr><td>Bid Price</td><td>{{ details.statistics['bid-price'] }}</td></tr>
                                <tr><td>Bid Size</td><td>{{ details.statistics['bid-size'] }}</td></tr>
                                <tr><td>Average Daily Volume</td><td>{{ details.statistics['average-daily-volume'] }}</td></tr>
                            </tbody>
                        </table>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="2">Other Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Website</td><td><a :href="details.profile['website']" target="_blank">{{ details.profile['website'] }}</a></td></tr>
                                <tr><td>Address</td><td>{{ details.profile['address1'] }}</td></tr>
                                <tr><td>City</td><td>{{ details.profile['city'] }}</td></tr>
                                <tr><td>State</td><td>{{ details.profile['state'] }}</td></tr>
                                <tr><td>Country</td><td>{{ details.profile['country'] }}</td></tr>
                                <tr><td>ZIP</td><td>{{ details.profile['zip'] }}</td></tr>
                                <tr><td>Industry</td><td>{{ details.profile['industry'] }}</td></tr>
                                <tr><td>Fulltime Employees</td><td>{{ details.profile['fullTimeEmployees'] }}</td></tr>
                                <tr><td>Phone</td><td>{{ details.profile['phone'] }}</td></tr>
                                <tr><td>Sector</td><td>{{ details.profile['sector'] }}</td></tr>
                            </tbody>
                        </table>

                    </div>
                </modal>
            </footer>
        </div>
    </div>
</template>

<script>

import Events from './Events.js';
import Modal from './utils/Modal.vue';
import Axios from 'axios';
import StockBuy from './StockBuy.vue';

export default {
    data: function(){
        return {
            isRemoving : false,
            isRemoved : false,
            isAdding : false,
            isAdded : false,
            isWatched : true,
            api : {
                addToWatchlist : hostname + "/api/v1/user/watchlist/add/",
                removeFromWatchlist : hostname + "/api/v1/user/watchlist/remove/"
            },
        }
    },
    props : {
        symbol : String(),
        details : Object()
    },
    components: {
        'modal' : Modal,
        'stock-buy' : StockBuy
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
                    Events.$emit('symbolWatchlistAdded');
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
                    Events.$emit('symbolWatchlistRemoved');
                }
                else{
                    self.isRemoved = 'error';
                    setTimeout(function(){
                        self.isRemoved = false;
                    },3000);
                }
            });
        }
    }

}
</script>

<style lang="css">
</style>
