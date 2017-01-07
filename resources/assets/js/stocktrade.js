import StockTradeWatchList from './components/StockTradeWatchList.vue';
import Events from './components/Events.js';

const stocktrade = new Vue({
    el: '#stocktrade',
    template : '<stocktrade-watchlist></stocktrade-watchlist>',
    components: {
        'stocktrade-watchlist' : StockTradeWatchList
    },
});
