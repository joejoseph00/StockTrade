<template>
    <div>
        <a @click=" isModalOpen = true " :class="btnClass ? btnClass : 'button is-small'">{{ btnText }}</a>
        <div class="modal is-active" v-if="isModalOpen">
            <div class="modal-background" @click=" isModalOpen = false "></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title is-marginless"><slot name="header"></slot></p>
                    <button class="delete" @click=" isModalOpen = false "></button>
                </header>
                <section class="modal-card-body">
                    <slot></slot>
                </section>
                <footer class="modal-card-foot">
                    <slot name="footer">
                        <a class="button is-pulled-right" @click=" isModalOpen = false ">Close</a>
                    </slot>
                </footer>
            </div>
        </div>
    </div>
</template>


<script>
export default{
    name : 'modal',
    props : {
        btnText : String,
        btnClass : String
    },
    data : function(){
        return {
            isModalOpen : false
        }
    },
    isComputed : {
        btnClass : 'button is-small'
    }
}
</script>
