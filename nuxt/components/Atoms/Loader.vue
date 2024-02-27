<template>
    <v-overlay id="loader" v-model="$store.state.loader.enable">
        <v-progress-circular indeterminate size="70" width="7"></v-progress-circular>
    </v-overlay>
</template>

<script>
    export default {
        name: "Loader"
    }
</script>

<style scoped>
#loader{
    max-width: 100vw;
}
</style>
