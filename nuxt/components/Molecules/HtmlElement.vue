<template>
    <div v-if="content"
         v-html="content"
    />
</template>

<script>
export default {
    name: "HtmlElement",
    props:{
        content:{
            type:String,
        }
    },
    mounted() {
        let as = document.getElementsByTagName('a');
        for (let i = 0; i < as.length; i++) {
            as[i].setAttribute('target', '_blank');
        }
    },
}
</script>

<style scoped>

</style>
