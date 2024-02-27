<template>
    <div id="master">
        <nuxt-child v-if="$store.state.admin.is_login"></nuxt-child>
        <v-fade-transition
            v-else >
            <login-form></login-form>
        </v-fade-transition>
    </div>
</template>

<script>
    import LoginForm from "../components/Molecules/LoginForm";
    export default {
        name: "master",
        components: {LoginForm},
        created() {
            this.$store.dispatch('page/sideBar', true);
        }
    }
</script>

<style scoped>

</style>
