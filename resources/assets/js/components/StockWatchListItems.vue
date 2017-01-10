<template lang="html">
    <div class="column is-one-quarter">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    {{ symbol }}&nbsp;<small class="is-small">{{ data.statistics['name'] }}</small>
                </p>
                <a class="card-header-icon">
                    <span class="icon">
                        <i class="fa fa-angle-down"></i>
                    </span>
                </a>
            </header>
            <div class="card-content">
                <div class="content">
                    <nav class="level is-mobile">
                        <div class="level-item has-text-centered">
                            <div>
                                <p class="heading">Open</p>
                                <p class="title">{{ data.statistics['open'] }}</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                                <p class="heading">Close</p>
                                <p class="title">{{ data.statistics['previous-close'] }}</p>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
            <footer class="card-footer">
                <a class="card-footer-item">Buy</a>
                <a class="card-footer-item">Sell</a>
                <modal btnText="More Info" btnClass="card-footer-item">
                    <template slot="header">{{ data.statistics['symbol'] }} : {{ data.statistics['name'] }}</template>
                    <div class="content">
                        <div class="has-text-centered">
                            <h1 class="title is-2">{{ data.statistics['symbol'] }}</h1>
                            <h5 class="subtitle">{{ data.statistics['name'] }}</h5>
                        </div>
                        <div class="columns has-text-centered">
                            <div class="column">
                                <h3 class="title">{{ data.statistics['open'] }}</h3>
                                <h6 class="subtitle is-6">Open</h6>
                            </div>
                            <div class="column">
                                <h3 class="title">{{ data.statistics['previous-close'] }}</h3>
                                <h6 class="subtitle is-6">Previous Close</h6>
                            </div>
                            <div class="column">
                                <h3 class="title">{{ data.statistics['revenue'] }}</h3>
                                <h6 class="subtitle is-6">Revenue</h6>
                            </div>
                            <div class="column">
                                <h3 class="title">{{ data.statistics['percent-change'] }}</h3>
                                <h6 class="subtitle is-6">Change</h6>
                            </div>
                        </div>
                        <p>{{ data.profile.longBusinessSummary }}</p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="2">Statistics</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Market Capitalization</td><td>{{ data.statistics['market-capitalization'] }}</td></tr>
                                <tr><td>Outstanding Shares</td><td>{{ data.statistics['outstanding-shares'] }}</td></tr>
                                <tr><td>Float Shares</td><td>{{ data.statistics['float-shares'] }}</td></tr>
                                <tr><td>Last Trade Size</td><td>{{ data.statistics['last-trade-size'] }}</td></tr>
                                <tr><td>Stock Exchange</td><td>{{ data.statistics['stock-exchange'] }}</td></tr>
                                <tr><td>Day High</td><td>{{ data.statistics['day-high'] }}</td></tr>
                                <tr><td>Day Low</td><td>{{ data.statistics['day-low'] }}</td></tr>
                                <tr><td>Year High</td><td>{{ data.statistics['year-high'] }}</td></tr>
                                <tr><td>Year High Change</td><td>{{ data.statistics['year-high-change'] }} ({{ data.statistics['year-high-change-percent'] }})</td></tr>
                                <tr><td>Year Low</td><td>{{ data.statistics['year-low'] }}</td></tr>
                                <tr><td>Year Low Change</td><td>{{ data.statistics['year-low-change'] }} ({{ data.statistics['year-low-change-percent'] }})</td></tr>
                                <tr><td>Earnings per Share</td><td>{{ data.statistics['earnings-per-share'] }}</td></tr>
                                <tr><td>Ask Price</td><td>{{ data.statistics['ask-price'] }}</td></tr>
                                <tr><td>Ask Size</td><td>{{ data.statistics['ask-size'] }}</td></tr>
                                <tr><td>Bid Price</td><td>{{ data.statistics['bid-price'] }}</td></tr>
                                <tr><td>Bid Size</td><td>{{ data.statistics['bid-size'] }}</td></tr>
                                <tr><td>Average Daily Volume</td><td>{{ data.statistics['average-daily-volume'] }}</td></tr>
                            </tbody>
                        </table>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="2">Other Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Website</td><td><a :href="data.profile['website']" target="_blank">{{ data.profile['website'] }}</a></td></tr>
                                <tr><td>Address</td><td>{{ data.profile['address1'] }}</td></tr>
                                <tr><td>City</td><td>{{ data.profile['city'] }}</td></tr>
                                <tr><td>State</td><td>{{ data.profile['state'] }}</td></tr>
                                <tr><td>Country</td><td>{{ data.profile['country'] }}</td></tr>
                                <tr><td>ZIP</td><td>{{ data.profile['zip'] }}</td></tr>
                                <tr><td>Industry</td><td>{{ data.profile['industry'] }}</td></tr>
                                <tr><td>Fulltime Employees</td><td>{{ data.profile['fullTimeEmployees'] }}</td></tr>
                                <tr><td>Phone</td><td>{{ data.profile['phone'] }}</td></tr>
                                <tr><td>Sector</td><td>{{ data.profile['sector'] }}</td></tr>
                            </tbody>
                        </table>

                    </div>
                </modal>
            </footer>
        </div>
    </div>
</template>

<script>

import Modal from './utils/Modal.vue';

export default {
    props : {
        symbol : String(),
        data : Object()
    },
    components: {
        modal : Modal
    }

}
</script>

<style lang="css">
</style>
