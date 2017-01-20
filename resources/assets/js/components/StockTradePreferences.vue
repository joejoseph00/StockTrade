<template lang="html">
    <div id="tab-profile">
        <div class="section content">
            <div class="container">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Profile Settings
                        </p>
                    </header>
                    <div class="card-content">
                        <div v-if="isLoading">
                            <p>
                                {{ messages.loading }}
                                <progress class="progress is-small" v-bind:class="{ 'is-danger' : isLoadingFailed }" v-bind:value="loadingPercent" max="100">{{ loadingPercent }}%</progress>
                                <a v-if="isLoadingFailed" class="button is-link" @click="onRetryFetch">Try Again</a>
                            </p>
                        </div>
                        <div v-else class="media">
                            <div class="media-left">
                                <figure class="image" style="height: 128px; width: 128px;">
                                    <img src="http://bulma.io/images/placeholders/128x128.png" alt="Image">
                                </figure>
                            </div>
                            <div class="media-content">
                                <form class="control">
                                    <div class="columns">
                                        <div class="column is-6">
                                            <label class="label">Fullname</label>
                                            <p class="control">
                                                <input class="input" type="text" placeholder="Your Fullname" v-model="profile.fullname" required>
                                            </p>
                                            <label class="label">Username</label>
                                            <p class="control">
                                                <input class="input" type="text" placeholder="Your Username" v-model="profile.username" required>
                                            </p>
                                            <label class="label">Email Address</label>
                                            <p class="control">
                                                <input class="input" type="email" placeholder="Your Email Address" v-model="profile.email" required>
                                            </p>
                                            <label class="label">Password</label>
                                            <p class="control">
                                                <input class="input" type="password" placeholder="*********" value="">
                                            </p>
                                            <label class="label">Confirm Password</label>
                                            <p class="control">
                                                <input class="input" type="password" placeholder="*********" value="">
                                            </p>
                                        </div>
                                        <div class="column is-4">
                                            <label class="label">Country</label>
                                            <p class="control">
                                                <span class="select">
                                                    <select>
                                                        <option>Select Country</option>
                                                    </select>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </form>
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
            messages : {
                loading : "Getting Profile Information..."
            },
            api : {
                getUserProfile : hostname + "/api/v1/user/profile"
            },
        }
    },
    methods : {
        fetchProfileData(){
            var self = this;
            self.isLoadingFailed = false;
            self.isLoading = true;
            self.loadingPercent = 0;
            if(self.progressInterval) clearInterval(self.progressInterval);
            self.progressInterval = setInterval(function(){
                self.loadingPercent += 1;
                if(self.loadingPercent>100) self.loadingPercent = 0;
            },50);

            Axios.get(self.api.getUserProfile).then(function(response){
                if(response.status == 200 && response.data.status == 'OK'){
                    self.profile = response.data.profile;
                    self.isLoading = false;
                }
                else{
                    self.isLoadingFailed = true;
                }
                clearInterval(self.progressInterval);
            }).catch(function(error){
                self.isLoadingFailed = true;
            });
        }
    },
    created: function(){
        var self = this;
        this.fetchProfileData();
    }
}
</script>

<style lang="css">
</style>
