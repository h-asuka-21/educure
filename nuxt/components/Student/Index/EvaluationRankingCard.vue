<template>
    <v-card class="fill-height"
            :loading="loading"
            :disabled="loading"
            outlined>
        <v-card-title>総合評価ランキング
            <v-spacer/>
            <v-tooltip bottom max-width="300">
                <template v-slot:activator="{ on, attrs }">
                    <v-icon
                        color="gray"
                        v-bind="attrs"
                        v-on="on"
                    >
                        mdi-information-outline
                    </v-icon>
                </template>
                <span>
                    ※この順位は所属会社内の受講者の総合評価点から算出しています。<br>
                    ※総合評価点は各カリキュラムのテストや、講師営業からの評価等の項目を総合しており、各項目の評価が揃った段階で順位に反映されます。<br>
                    （カリキュラムの初期の段階では順位に反映されません。）
                </span>
            </v-tooltip>
        </v-card-title>
        <v-expand-transition>
            <v-card-text v-if="!loading" :class="scroll_class">
                <v-list
                    v-if="ranking"
                >
                    <v-list-item dense v-if="rank">
                        <v-list-item-content>
                            <v-list-item-subtitle>
                                あなたの現在の順位は<span class="font-weight-bold title">{{ rank }}位</span>です。
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item
                        v-for="item in ranking.data"
                        :key="item.order"
                        @click="clickRow(item)"
                    >
                        <v-list-item-avatar>
                            <v-icon
                                :class="getClass(item.order)"
                                v-text="item.order"
                            ></v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title>{{item.name}}</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-action>
                        <span>
                            <span class="score">{{item.all_ave_score}}</span>
                            pt
                        </span>
                        </v-list-item-action>
                    </v-list-item>
                </v-list>
            </v-card-text>
        </v-expand-transition>
    </v-card>
</template>

<script>
import DataTable from "@/components/Molecules/DataTable";
import SimpleCardDialog from "@/components/Molecules/SimpleCardDialog";

export default {
    name: "EvaluationRankingCard",
    components: {SimpleCardDialog, DataTable},
    data() {
        return {
            headers: [
                {text: '順位', value: 'order', sortable: false},
                {text: '名前', value: 'name', sortable: false},
                {text: '評価点', value: 'all_ave_score', sortable: false},
            ],
            data: {},
            loading:false,
            rank: 0,
            ranking: undefined,
            scroll_class: '',
        }
    },
    created() {
        if ( !this.$route.path.match('/admin')) {
            this.getSelfRank();
        } else {
            this.getRanking();
            this.scroll_class = 'scroll_zone'
        }
    },
    methods: {
        async getSelfRank(){
            try{
                this.loading = true;
                const ret = await this.$axios.get(this.$utils.getApiUrl(this.$apis.student_self_ranking,true,true))
                this.rank = ret.data;
            }catch (e) {
                this.$utils.catchError(e);
            } finally {
                await this.getRanking();
            }
        },
        async getRanking(){
            try{
                this.loading = true;
                const ret = await this.$axios.get(this.$utils.getApiUrl(this.$apis.student_evaluation_ranking, true));
                if (this.$utils.isStudent()) {
                    this.ranking = ret.data;
                    if (this.ranking.data) {
                        this.ranking.data = ret.data.data.slice(0,3);
                    }
                } else {
                    this.ranking = ret.data;
                }
                console.log(this.ranking);
            }catch (e){
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
            }
        },
        getClass(order){
            let ret = 'white--text pr-1'
            if(order === 1){
                return ret+= ' amber';
            }
            return ret += ' teal';
        },
        clickRow(item){
            if(!this.$utils.isStudent()){
                this.$router.push(this.$utils.getHomeUrl() + '/student/' + item.id);
            }
        }
    }
}
</script>

<style scoped lang="scss">
.fill-height {
    padding-bottom: 0;
    .data_table {
        padding: 0 !important;
    }
}
.score{
    font-size: 1.2rem;
    color: #607D8B;
}
.scroll_zone{
    overflow: scroll;
    max-height: 290px;
}
</style>
