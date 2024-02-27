<template>
    <v-text-field
        outlined
        :type="type"
        v-model="data"
        :label="label"
        :rules="rules"
        :dense="dense"
        :clearable="clearable"
        :hint="hint"
        :append-outer-icon="suffix"
        :disabled="disabled"
        :id="id"
        :counter="counter"
        :persistent-hint="persistentHint"
        :readonly="readonly"
        :prepend-icon="icon"
    ></v-text-field>
</template>

<script>
    export default {
        name: "TextInput",
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
            dense:{
                type:Boolean,
                default:true
            },
            clearable:{
                type:Boolean,
                default:false
            },
            suffix:{
                type:String,
                default:undefined
            },
            hint:{
                type:String,
                default:undefined
            },
            persistentHint:{
                type:Boolean,
                default:false
            },
            disabled:{
                type:Boolean,
                default:false
            },
            id:{
                type:String
            },
            counter:{
                type:String
            },
            readonly:{
                type:Boolean,
                default: false
            },
            icon:{
                type:String,
                default:undefined
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
