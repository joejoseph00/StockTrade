<template lang="html">
    <div id="stocktrade-login">
        <div class="container is-fluid">
            <div class="columns">
                <div class="column">
                    <div class="section content">
                        <h3>Create your demo account</h3>
                        <stocktrade-registration></stocktrade-registration>
                    </div>
                </div>
                <div class="column">
                    <div class="section content">
                        <h3>Login to start trading</h3>
                        <div class="is-boxed">
                            <label class="label">Email</label>
                            <p class="control">
                                <input class="input" type="text" placeholder="jsmith@example.org">
                            </p>
                            <label class="label">Password</label>
                            <p class="control">
                                <input class="input" type="password">
                            </p>
                            <hr>
                            <p class="control">
                                <button class="button is-primary">Login</button>
                                <button class="button is-default">Cancel</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import StockTradeRegistration from './StockTradeRegistration.vue';

export default {
    name : 'stocktrade-login',
    components : {
        'stocktrade-registration' : StockTradeRegistration
    }
}
</script>

<style lang="css">
</style>
