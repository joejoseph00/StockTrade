var apihost = 'http://localhost:8000';
var stocktradeWatchList = new Vue({
    el: '#stocktrade-watchlist',
    data: {
        isLoading: true,
        message: 'Hello Vue!',
        messages : {
            loading : 'Getting favorite quotes...'
        },
        api : {
            getWatchlist : {
                url : apihost + "/user/watchlist",
                response : null
            }
        }
    },
    components: {
        'stockitem' : {
            props: ['symbol'],
            template : '<li>{{ symbol }}</li>',
            data : function(){
                return {
                    symbol : ''
                };
            }
        }
    },
    methods: {
        fetchData: function () {
            var xhr = new XMLHttpRequest()
            var self = this
            xhr.open('GET', self.api.getWatchlist.url)
            xhr.onreadystatechange = function (oEvent) {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        self.api.getWatchlist.response = JSON.parse(xhr.responseText)
                        self.isLoading = false;

                    } else {
                        self.messages.loading = 'Error: Failed getting watchlist';
                    }
                }
            };
            xhr.send()
        }
    },
    created: function(){
        console.log('component ready');

        this.fetchData();

    }
})
