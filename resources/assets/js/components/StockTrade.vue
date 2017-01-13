<template lang="html">
    <div id="StockTradeWrapper">

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
        </section>
        <tabs tabclass="is-centered is-boxed" :invertColor="true" >
            <tab name="Dashboard">
                <h1>Here is the content for Dashboard</h1>
            </tab>
            <tab name="Watchlist" :selected="true">
                <stocktrade-watchlist></stocktrade-watchlist>
            </tab>
            <tab name="My Portfolio">
                <h1>Here is the content for My Portfolio</h1>
            </tab>
            <tab name="Transaction History">
                <stocktrade-transactions></stocktrade-transactions>
            </tab>
        </tabs>

    </div>
</template>

<script>
import Events from './Events.js';
import StockTradeWatchList from './StockTradeWatchList.vue';
import StockTransactions from './StockTransactions.vue';
import Tabs from './utils/Tabs.vue';
import Tab from './utils/Tab.vue';


export default {
    components: {
        'stocktrade-watchlist' : StockTradeWatchList,
        'stocktrade-transactions' : StockTransactions,
        'tabs' : Tabs,
        'tab' : Tab,
    },
    created: function(){

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
    }
}
</script>

<style lang="css">
</style>
