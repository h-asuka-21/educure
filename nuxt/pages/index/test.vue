<template>
    <main-container
        title="テスト"
        :tabs="[]"
    >
        <nuxt-child/>
    </main-container>
</template>

<script>
import MainContainer from "../../components/Molecules/MainContainer";
export default {
    components: {MainContainer},
    data(){
        return{

        }
    },
    created() {
        this.$store.dispatch('page/hideBackButton');
        this.$store.dispatch('page/hideTab');
        this.$store.dispatch('page/setTitle', 'テスト');

    }
};
</script>
