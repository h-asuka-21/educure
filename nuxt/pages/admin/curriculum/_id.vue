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
import EditForm from "~/components/Curriculum/EditForm";

export default {
    components: {EditForm},
    data() {
        return {
            data:{
                curriculum: {},
                steps: [],
            },
            loading: true,
            disabled: false,
            show: true
        }
    },
    created() {
        if ( this.$route.path.match('admin/curriculum/*')) {
            this.disabled = true;
            this.show = false;
        }
        this.$store.dispatch('page/setTitle', 'カリキュラム詳細');
        // this.$store.dispatch('page/hideTab');
        this.$store.dispatch('page/showBackButton');
        this.$store.dispatch('page/setBackUrl',null)
        this.getDetail();
    },
    methods:{
        async getDetail(){
            this.data = {
                curriculum: {},
                steps: [],
                deleted: []
            }
            this.loading = true;
            const url = '/user' + this.$apis.curriculum + '/' + this.$route.params.id;
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
