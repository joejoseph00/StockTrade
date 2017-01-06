
<template>
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
                    <h2 slot="header">
                        Add Symbol to Watchlist
                    </h2>
                    <label class="label">Name</label>
                    <p class="control">
                        <autocomplete
                        url="http://localhost:8000/api/v1/stock/search"
                        anchor="symbol"
                        label="name"
                        placeholder="e.g. GOOGL"
                        class-name="input"
                        :onShow = "onAutocompleteCreate"
                        >
                    </autocomplete>
                </p>

            </modal>
            <ul id="stockitem-list" class="list-unstyled">
                <stock-item v-for="stock in stocks">{{ stock.symbol }}</stock-item>
            </ul>
        </div>
    </div>
</div>
</template>

<script>
import Autocomplete from 'vue2-autocomplete-js';

export default {
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
                }
            }
        }
    },
    components: {
        autocomplete : Autocomplete,
        'stock-item' : {
            name : 'stock-item',
            template : '<li><slot></slot></li>'
        }
    },
    methods: {
        fetchData() {
            // Fetch Symbols from Database
            var xhr = new XMLHttpRequest()
            var self = this
            xhr.open('GET', self.api.getWatchlist.url)
            xhr.onreadystatechange = function (oEvent) {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        self.stocks = JSON.parse(xhr.responseText);
                        self.isLoading = false;
                    } else {
                        self.messages.loading = 'Error: Failed getting watchlist';
                        self.api.getWatchlist.response = [];
                    }
                }
            };
            xhr.send()
        },
        onAutocompleteCreate(){
            console.log(this.$refs.autocomplete);
        }
    },
    created: function(){
        this.fetchData();

        Events.$on('moduleLoaded',function(){
            console.log('Loaded');
        });
    },
}
</script>
