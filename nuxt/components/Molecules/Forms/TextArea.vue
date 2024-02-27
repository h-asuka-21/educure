<template>
    <v-textarea
        outlined
        v-model="data"
        :label="label"
        :rules="rules"
        :id="id"
    >
    </v-textarea>
</template>

<script>
    export default {
        name: "TextArea",
        props:{
            type:{
                type:String,
                default:'text',
            },
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
            id:{
                type:String
            }
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

<style scoped>

</style>
