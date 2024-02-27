<template>
    <v-btn
        :color="color"
        icon @click="$emit('click')">
        <v-icon>mdi-close-circle-outline</v-icon>
    </v-btn>
</template>
<script>
    export default {
        name: "IconCloseButton",
        props:{
            color:{
                type:String,
                default:''
            }
        }
    }
</script>

<style scoped>

</style>
