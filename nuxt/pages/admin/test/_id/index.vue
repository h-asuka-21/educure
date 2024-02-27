<template>
    <edit-form
        v-model="data"
        :loading="loading"
        @reload="getDetail()"
        @hide="$emit('reload')"
        :disabled="disabled"
        :show="show"
    >
    </edit-form>
</template>

<script>
import Detail from "../../../../components/Test/Detail";
import Questions from "../../../../components/Test/Questions";
import EditForm from "../../../../components/Test/EditForm";
export default {
    components: {EditForm, Questions, Detail},
    data() {
        return {
            data:{
                test: {},
                questions: [],
            },
            loading: true,
            disabled: false,
            show: true
        }
    },
    created() {
        if ( this.$route.path.match('admin/test/*')) {
            this.disabled = true;
            this.show = false;
        }
        this.$store.dispatch('page/setTitle', 'テスト詳細');
        // this.$store.dispatch('page/hideTab');
        this.$store.dispatch('page/showBackButton');
        this.getDetail();
    },
    methods:{
        async getDetail(){
            this.data = {
                test: {},
                questions: [],
                deleted: []
            }
            this.loading = true;
            const url = '/user' + this.$apis.test + '/' + this.$route.params.id;
            try{
                const res = await this.$axios.get(url);
                this.data = res.data;
                this.loading = false;
                this.data.deleted = [];
            } catch (e) {
                this.$utils.catchError(e);
            }
        }
    }
}
</script>

<style scoped>

</style>
