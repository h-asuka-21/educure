<template>
    <div id="student">
        <div v-if="$store.state.student.is_login">
            <testing-alert/>
            <nuxt-child/>
        </div>
        <v-fade-transition v-else>
            <login-form/>
        </v-fade-transition>
    </div>
</template>

<script>
import LoginForm from "../components/Molecules/LoginForm";
import TestingAlert from "@/components/UserTest/TestingAlert";
export default {
    components: {TestingAlert, LoginForm},
}
</script>
<style lang="scss">
#student {
    min-height: 100%;
}
</style>
