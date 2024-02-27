<template>
    <v-card :color="$colors.main" dark raised class="page_title">
        <v-card-title>
            {{title}}
        </v-card-title>
    </v-card>
</template>
<script>
    export default {
        props:{
            title:{
                type:String,
                required:true
            }
        }
    }
</script>
<style lang="scss">
    .page_title{
        display: inline-block!important;
        position: relative!important;;
        z-index: 1;
        left: 10px;
        top: 47px;
        > div{
            padding: 8px 10px;
        }
    }
</style>
