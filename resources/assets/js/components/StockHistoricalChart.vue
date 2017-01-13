<template lang="html">
    <div class="historical-chart">
        {{ symbol }}
    </div>
</template>

<script>
export default {
    props: {
        symbol : {
            required: true
        }
    },
    created: function(){
        console.log(this.symbol);
    }
}
</script>

<style lang="css">
</style>
