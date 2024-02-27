<template>
    <v-menu
        :close-on-content-click="false"
        :nudge-right="40"
        transition="scale-transition"
        offset-y
        min-width="220px"
    >
        <template v-slot:activator="{ on }">
            <v-text-field
                v-model="data"
                :label="label"
                v-on="on"
                :clearable="clearable"
                :disabled="disabled"
            ></v-text-field>
        </template>
        <v-time-picker v-model="data" @input="time_picker = false" format="24hr" :allowed-minutes="allowed_minutes"
        ></v-time-picker>
    </v-menu>
</template>
<script>
    export default {
        data(){
            return {
                data:this.value,
                allowed_minutes:[0,5,10,15,20,25,30,35,40,45,50,55]

            }
        },
        props:{
            value:{
                required:true
            },
            label:{
                type:String,
                required: true
            },
            rules:{
                default:undefined
            },
            clearable:{
                type:Boolean,
                default:false
            },
            disabled:{
                type:Boolean,
                default:false
            }
        }
    }
</script>
