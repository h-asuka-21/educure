<template>
    <v-menu
        :close-on-content-click="false"
        :nudge-right="40"
        transition="scale-transition"
        offset-y
        min-width="220px">
        <template v-slot:activator="{ on }">
            <v-text-field
                :id="id"
                outlined
                v-model="data"
                :label="label"
                v-on="on"
                readonly
                :clearable="clearable"
                :disabled="disabled"
                :dense="dense"
            ></v-text-field>
        </template>
        <v-date-picker v-model="data"  type="month"
        ></v-date-picker>
    </v-menu>
</template>
<script>
    export default {
        name:'Month',
        data(){
            return {
                data:this.value
            }
        },
        watch: {
            value(val) {
                this.data = val;
            },
            data(val) {
                this.$emit('input', val);
            }
        },
        props:{
            value:{
                required:true
            },
            label:{
                type:String,
                required: false,
                default: ''
            },
            rules:{
                default: undefined
            },
            clearable:{
                type:Boolean,
                default:false
            },
            disabled:{
                type:Boolean,
                default:false
            },
            dense:{
                type:Boolean,
                default:true
            },
            id:{
                type:String,
                default:undefined
            }
        }
    }
</script>
