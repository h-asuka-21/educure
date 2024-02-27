<template>
    <v-dialog
        v-if="dialog"
        v-model="dialog"
        :max-width="max_width"
        scrollable
        :persistent="persistent"
    >
        <v-card
            :loading="$store.state.dialog.loading"
            :disabled="$store.state.dialog.disable"
        >
            <v-card-title
                v-if="title!==undefined"
            >{{title}}</v-card-title>
            <v-card-text>
                <slot></slot>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "SimpleCardDialog",
        data(){
            return{
                dialog:false
            }
        },
        props: {
            title: {
                type: String,
                default:undefined
            },
            max_width:{
                type: String,
                required: false,
                default: '620'
            },
            persistent:{
                type:Boolean,
                default: false
            }
        },
        methods:{
            show(){
                this.dialog = true;
            },
            hide(){
                this.dialog = false
            }
        }
    }
</script>

<style scoped>

</style>
