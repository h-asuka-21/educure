<template>
    <div>
        <p class="subtitle">{{label}}</p>
        <wysiwyg class="" v-model="data"></wysiwyg>
        <v-input
            v-model="data"
            type="hidden"
            :rules="rules"
        ></v-input>
    </div>
</template>

<script>
    export default {
        name: "WysiwigInput",
        props:{
            label:{
                type:String,
                required:true
            },
            value:{
                required:true
            },
            rules:{
                type:Array
            },
        },
        data(){
            return {
                data:this.value
            }
        },
        watch:{
            value(val){
                this.data = val;
            },
            data(val){
                this.$emit('input', val);
            }
        }
    }
</script>
<style lang="scss">
    .editr--toolbar{
        div[title="Insert Image"]{
            display: none!important;
        }
    }
</style>
