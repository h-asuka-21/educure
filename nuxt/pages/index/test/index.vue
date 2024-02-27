<template>
    <div id="test_list">
        <v-row>
            <v-col cols="6" v-for="(test,key) in tests" :key="key">
                <list-card
                    :test="test"
                ></list-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import ListCard from "~/components/Test/ListCard";
export default {
    name: "index",
    components: {ListCard},
    data(){
        return {
            tests:[
                {
                    name:'テスト',
                    type:0,
                    curriculum_name:'テストカリキュラム',
                    test_time:10
                },
                {
                    name:'CABテスト',
                    type:2,
                    test_time:15
                },
                {
                    name:'総合テスト',
                    type:1,
                    course_name: 'PHPコース',
                    test_time:30
                },
            ]
        }
    },
    created() {
        // this.getTests();
    },
    methods:{
        // async getTests(){
        //     try{
        //         this.$store.dispatch('loader/showSub')
        //         const result = await this.$axios.get(this.$utils.getApiUrl(this.$apis.test));
        //         this.tests = result.data;
        //     }catch (e){
        //         this.$utils.catchError(e);
        //     } finally {
        //         this.$store.dispatch('loader/hideSub');
        //     }
        // }
    }
}
</script>

<style scoped>

</style>
