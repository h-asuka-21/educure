<template>
    <v-overlay v-model="$store.state.loader.sub_enable" z-index="9998" >
        <v-progress-circular indeterminate size="70" width="7"></v-progress-circular>
    </v-overlay>
</template>

<script>
    export default {
        name: "SubLoader"
    }
</script>

<style scoped>

</style>
