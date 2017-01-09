import StockTradeWatchList from './components/StockTradeWatchList.vue';
import Events from './components/Events.js';

const stocktrade = new Vue({
    el: '#stocktrade',
    template : '<stocktrade-watchlist></stocktrade-watchlist>',
    components: {
        'stocktrade-watchlist' : StockTradeWatchList
    },
});

function sendHeight()
{
    if(parent.postMessage)
    {
        var height = document.body.scrollHeight;
        parent.postMessage(height, '*');
    }
}

window.onload = function(){
    var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
    sendHeight();
}
