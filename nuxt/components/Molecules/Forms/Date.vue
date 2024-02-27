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
                :rules="rules"
                :hint="hint"
                :persistent-hint="persistentHint"
            ></v-text-field>
        </template>
        <v-date-picker v-model="data"  :type="type"
        ></v-date-picker>
    </v-menu>
</template>
<script>
    export default {
        name:'Date',
        data() {
            return {
                data: this.value
            }
        },
        watch: {
            value(val) {
                console.log(val);
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
            type:{
                type:String,
                default: 'date'
            },
            dense:{
                type:Boolean,
                default:true
            },
            id:{
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
            }
        }
    }
</script>
