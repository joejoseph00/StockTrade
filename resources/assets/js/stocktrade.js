import StockTradeWatchList from './components/StockTradeWatchList.vue';
import StockTradeLogin from './components/StockTradeLogin.vue';
import Events from './components/Events.js';

const stocktrade = new Vue({
    el: '#stocktrade',
    template : `
    <div>
        <stocktrade-watchlist v-if="isLoggedIn"></stocktrade-watchlist>
        <stocktrade-login v-else></stocktrade-login>
    </div>
    `,
    data: {
        isLoggedIn: false,
    },
    components: {
        'stocktrade-watchlist' : StockTradeWatchList,
        'stocktrade-login' : StockTradeLogin
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
