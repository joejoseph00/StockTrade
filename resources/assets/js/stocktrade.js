import StockTradeWatchList from './components/StockTradeWatchList.vue';
import StockTradeLogin from './components/StockTradeLogin.vue';
import Events from './components/Events.js';
import Axios from 'axios';

const stocktrade = new Vue({
    el: '#stocktrade',
    template : `
    <div>
    <div v-if="userStatusChecked">
    <stocktrade-watchlist v-if="isLoggedIn"></stocktrade-watchlist>
    <stocktrade-login v-else></stocktrade-login>
    </div>
    <div v-else>
    <section class="hero is-primary is-large header-image">

    <div class="hero-body">
    <div class="container has-text-centered">
    <h1 class="title is-2">
    Demo Stock Trading Account
    </h1>
    <h2 class="subtitle is-5">
    Compete, Risk Free with <strong>$100,000</strong> in Virtual Cash
    </h2>
    <p>
    <progress class="progress is-small" v-bind:value="loadingPercent" max="100">{{ loadingPercent }}%</progress>
    </p>
    </div>
    </div>
    </section>
    </div>
    </div>
    `,
    data: {
        isLoggedIn: false,
        userStatusChecked : false,
        user : null,
        loadingPercent : 0,
        progressInterval : null,
        api : {
            isUserLoggedIn : hostname + "/api/v1/user/isLoggedIn"
        },
    },
    components: {
        'stocktrade-watchlist' : StockTradeWatchList,
        'stocktrade-login' : StockTradeLogin
    },
    methods: {
        checkUserStatus(){
            var self = this;
            self.progressInterval = setInterval(function(){
                self.loadingPercent += 1;
                if(self.loadingPercent>100) self.loadingPercent = 0;
            },50);
            Axios.post(self.api.isUserLoggedIn).then(function(response){
                if(response.status == 200 && response.data.status == 'OK'){
                    self.isLoggedIn = true;
                    self.user = response.data.user;
                }
                else{
                    self.isLoggedIn = false;
                }
                clearInterval(self.progressInterval);
                self.userStatusChecked = true;
            });
        }
    },
    created: function(){

        var self = this;

        Events.$on('userLoggedIn',function(data){
            self.isLoggedIn = true;
            self.user = data;
        });

        Events.$on('userLoggedOut',function(){
            self.isLoggedIn = false;
            self.user = null;
        });

        this.checkUserStatus()
    }
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
