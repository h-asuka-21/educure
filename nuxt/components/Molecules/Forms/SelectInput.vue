<template>
    <v-select
        outlined
        v-model="data"
        :label="label"
        :rules="rules"
        :dense="dense"
        :items="items"
        :clearable="clearable"
        :id="id"
        :data_test="data_test"
        value="{ data }"
        :disabled="disabled"
        :hint="hint"
        :persistent-hint="persistentHint"
    ></v-select>
</template>

<script>
    export default {
        name: "SelectInput",
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
            dense:{
                type:Boolean,
                default:true
            },
            items:{
                type:Array,
                required:true
            },
            clearable:{
                type:Boolean,
                default:false
            },
            id:{
                type:String,
            },
            data_test:{
                type:String,
            },
            disabled:{
                type:Boolean,
                default:false
            },
            hint:{
                type:String,
                default:undefined
            },
            persistentHint:{
                type:Boolean,
                default:false
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
