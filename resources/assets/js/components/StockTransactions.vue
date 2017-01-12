<template lang="html">
    <div id="stocktrade-transactions">
        <div class="section">
            <div class="container">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Latest Transactions
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="content">
                            <div v-if="isLoading">
                                <p>
                                    {{ messages.loading }}
                                    <progress class="progress is-small" v-bind:class="{ 'is-danger' : isLoadingFailed }" v-bind:value="loadingPercent" max="100">{{ loadingPercent }}%</progress>
                                    <a v-if="isLoadingFailed" class="button is-link" @click="onRetryFetch">Try Again</a>
                                </p>
                            </div>
                            <div v-else>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><abbr title="Position">ID</abbr></th>
                                            <th>Type</th>
                                            <th>Symbol</th>
                                            <th>Purchase Price</th>
                                            <th>Quantity</th>
                                            <th>Total Amount</th>
                                            <th>Purchase Date</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th><abbr title="Position">ID</abbr></th>
                                            <th>Type</th>
                                            <th>Symbol</th>
                                            <th>Purchase Price</th>
                                            <th>Quantity</th>
                                            <th>Total Amount</th>
                                            <th>Purchase Date</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr v-for="log in transactions">
                                            <td>{{ log.id }}</td>
                                            <td>{{ log.type }}</td>
                                            <td>{{ log.symbol }}</td>
                                            <td>{{ log.price }}</td>
                                            <td>{{ log.qty }}</td>
                                            <td>{{ log.price * log.qty }}</td>
                                            <td>{{ log.updated_at }}</td>
                                        </tr>
                                    </tbody>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
    import Axios from 'axios';
    export default {
        data: function(){
            return {
                isLoading : true,
                isLoadingFailed : false,
                loadingPercent : 0,
                progressInterval : null,
                transactions : [],
                messages : {
                    loading : "Fetching Latest Transaction"
                },
                api : {
                    getLatestTransactions : hostname + "/api/v1/user/transactions"
                },
                page : 1,
            }
        },
        methods: {
            fetchTransactionPage(){
                var self = this;
                self.isLoadingFailed = false;
                self.isLoading = true;
                self.loadingPercent = 0;
                if(self.progressInterval) clearInterval(self.progressInterval);
                self.progressInterval = setInterval(function(){
                    self.loadingPercent += 1;
                    if(self.loadingPercent>100) self.loadingPercent = 0;
                },50);

                Axios.get(self.api.getLatestTransactions,{ page : self.page }).then(function(response){
                    if(response.status == 200 && response.data.status == 'OK'){
                        self.transactions = response.data.data;
                        self.isLoading = false;
                    }
                    else{
                        self.isLoadingFailed = true;
                    }
                    clearInterval(self.progressInterval);
                }).catch(function(error){
                    self.isLoadingFailed = true;
                });
            },
            onRetryFetch(){
                this.fetchTransactionPage();
            }
        },
        created: function(){
            this.fetchTransactionPage();
        }
    }
    </script>

    <style lang="css">
    </style>
