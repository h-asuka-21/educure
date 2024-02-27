<template>
    <v-btn :color="$colors.sub" dark @click="$emit('click')">{{label}}</v-btn>
</template>
<script>
    export default {
        props:{
            url:{
                type:String
            },
            action:{
                type:Function,
                required: false
            },
            label:{
                type:String,
                default:'新規作成'
            }
        }
    }
</script>
