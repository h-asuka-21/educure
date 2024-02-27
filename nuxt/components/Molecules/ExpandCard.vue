<template>
    <v-card
        :loading="loading"
        :disabled="disabled"
        outlined
    >
        <v-card-title>
            {{title}}
            <v-spacer></v-spacer>
            <v-btn
                icon
                @click="show = !show"
            >
                <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
            </v-btn>
        </v-card-title>
        <v-expand-transition>
            <div
                v-show="show"
            >
                <slot></slot>
            </div>
        </v-expand-transition>
    </v-card>
</template>

<script>
export default {
    name: "ExpandCard",
    props:{
        title:{
            type:String,
            required:true
        },
        loading:{
            type:Boolean,
            default:false
        },
        disabled:{
            type:Boolean,
            default:false
        },
        default_opened:{
            type:Boolean,
            default:false
        }
    },
    created() {
        if(this.default_opened && !this.loading){
            this.show = true
        }
    },
    watch: {
        loading(val) {
            if (val && this.default_opened) {
                this.show = true;
            }
        }
    },
    data(){
        return{
            show: false
        }
    }
}
</script>

<style scoped>

</style>
