<template lang="html">
    <div class="tab-pane" v-show="isActive">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name : 'tab',
    data : function(){
        return {
            isActive : false
        }
    },
    props: {
        name : {
            required: true,
        },
        selected : {
            type: Boolean,
            default: false,
        },
    },
    created: function(){
        this.isActive = this.selected;
    }
}
</script>

<style lang="css">
</style>
