<template lang="html">
    <div class="stock-buy-wrapper">
        <div class="columns is-multiline">
            <div class="column is-full has-text-centered">
                <h1 class="title is-3">{{ symbol }}</h1>
                <h2 class="subtitle is-6">{{ name }}</h2>
            </div>
            <div class="column is-half">
                <label class="label">Price</label>
                <p class="has-text-right">
                    <input class="input has-text-right" type="number" v-model="price" min="1" readonly>
                </p>
            </div>
            <div class="column is-half">
                <label class="label">Quantity</label>
                <p class="control">
                    <input class="input has-text-right" type="number" v-model="qty" min="1">
                </p>
            </div>
            <div class="column is-half">
                <label class="label">&nbsp;</label>
                <p>
                    <button class="button is-primary is-fullwidth" :class="{ 'is-loading' : isBuying }" @click="onBuyStock" >Buy Now</button>
                </p>
            </div>
            <div class="column is-half">
                <label class="label">Total</label>
                <p class="control">
                    <input class="input has-text-right" type="number" v-model="totalAmount" readonly>
                </p>
            </div>
            <div v-if=" isBuyingSuccess!=null " class="column is-full">
                <blockquote v-if=" isBuyingSuccess ">
                    Congratulations! Transaction complete.
                </blockquote>
                <blockquote v-else>
                    <ul>
                        <li v-for="error in buyingErrors">{{ error }}</li>
                    </ul>
                </blockquote>
            </div>
        </div>
    </div>
</template>
<script>
import Axios from 'axios';
import Events from './Events.js';
export default {
    data : function(){
        return {
            isBuying : false,
            isBuyingSuccess : null,
            buyingErrors : [],
            qty : 100,
            api : {
                buyStocks : hostname + "/api/v1/stock/buy",
            }
        }
    },
    computed: {
        totalAmount(){
            return parseFloat(this.qty * this.price).toFixed(2)
        }
    },
    props: {
        symbol : {
            required : true,
        },
        name : {
            required : true,
        },
        price : {
            required : true,
        }
    },
    methods: {
        onBuyStock: function(){
            var self = this;
            self.isBuying = true;
            self.isBuyingSuccess = null;
            Axios.post(self.api.buyStocks,{
                symbol : this.symbol,
                price : this.price,
                qty : this.qty
            }).then(function(response){
                self.isBuying = false;
                console.log(response);
                if(response.status == 200 && response.data.status == 'OK'){
                    Events.$emit('buyingSuccess',self.symbol);
                    self.isBuyingSuccess = true;
                }
            }).catch(function(error){
                self.isBuying = false;
                self.isBuyingSuccess = false;
                self.buyingErrors = error.response.data.error;
            });
        }
    }
}
</script>
<style lang="css">
</style>
