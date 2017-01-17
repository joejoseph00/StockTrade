<template lang="html">
    <div id="stock-recommendation" class="is-minheight-300">
        <p v-if="isLoading">
            {{ messages.loading }}
            <progress class="progress is-small" v-bind:value="loadingPercent" max="100">{{ loadingPercent }}%</progress>
        </p>
        <template v-else>
            <span class="select">
                <select v-model.lazy="leaderboardFilter" @change="onFilterChanged">
                    <option value="all">All</option>
                    <option value="buy">Buy</option>
                    <option value="sell">Sell</option>
                    <option value="hold">Hold</option>
                </select>
            </span>
            <table class="table">
                <thead>
                    <tr>
                        <th>Stock</th>
                        <th>Exchange</th>
                        <th>Type</th>
                        <th>Best</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="e in entry">
                        <tr>
                            <td>{{ e.symbol }}</td>
                            <td>{{ e.issuer }}</td>
                            <td>{{ e.type }}</td>
                            <td>
                                <template v-if="e.recommendation === 'BUY' ">
                                    <modal btnText="BUY" btnClass="is-link" :minimal="true" :minheight="false">
                                        <template slot="header">Buy {{ e.symbol }} at {{ e.currentPrice }}</template>
                                        <stock-buy
                                        :symbol="e.symbol"
                                        :name="e.name"
                                        :price="e.currentPrice"
                                        ></stock-buy>
                                    </modal>
                                </template>
                                <template v-else-if="e.recommendation === 'SELL' ">
                                    <modal btnText="SELL" btnClass="is-link" :minimal="true" :minheight="false">
                                        <template slot="header">Sell {{ e.symbol }} at {{ e.currentPrice }}</template>
                                        <stock-sell
                                        :symbol="e.symbol"
                                        :name="e.name"
                                        :price="e.currentPrice"
                                        ></stock-sell>
                                    </modal>
                                </template>
                                <template v-else>{{ e.recommendation }}</template>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </template>
    </div>
</template>

<script>
import Axios from 'axios';
import StockBuy from './StockBuy.vue';
import StockSell from './StockSell.vue';
import Modal from './utils/Modal.vue';

export default {
    data: function(){
        return {
            leaderboardFilter: 'all',
            isLoading: true,
            loadingPercent : 0,
            progressInterval : null,
            api : {
                getLeaderboard : hostname + '/api/v1/stocks/recommendation'
            },
            entry : {},
            messages : {
                loading : 'Getting portfolio details...'
            },
        }
    },
    components: {
        'modal' : Modal,
        'stock-buy' : StockBuy,
        'stock-sell' : StockSell,
    },
    methods: {
        onFilterChanged(event){
            console.log(event);
            this.leaderboardFilter = event.target.value;
            this.getLeaderboard();
        },
        getLeaderboard(){
            var self = this;
            self.isLoadingFailed = false;
            self.isLoading = true;
            self.loadingPercent = 0;

            self.progressInterval = setInterval(function(){
                self.loadingPercent += 1;
                if(self.loadingPercent>100) self.loadingPercent = 0;
            },50);

            Axios.get(self.api.getLeaderboard,{ params : { filter : self.leaderboardFilter } }).then(function(response){
                self.entry = response.data.entry;
                self.isLoading = false;
                self.loadingPercent = 0;
            }).catch(function (error) {
                self.messages.loading = 'Error: Failed getting watchlist';
                self.isLoadingFailed = true;
            });
        },
    },
    created: function(){

        var self = this;
        this.getLeaderboard();
    }
}
</script>

<style lang="css">
</style>
