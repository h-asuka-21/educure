<template>
    <v-card class="fill-height"
            :loading="loading"
            :disabled="loading"
            :color="backgroundColor"
            outlined>
        <v-card-title>{{ title }}
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
                    ※この順位はエンジニアになった受講者が存在する企業から算出しています。<br>
                    （達成率　= エンジニア達成数  / (エンジニア達成数 + 辞退者数））<br>
                    ※完了生が存在しない企業は反映されません。<br>
                </span>
            </v-tooltip>
        </v-card-title>
        <v-expand-transition>
            <v-card-text v-if="!loading" class="scroll_zone">
                <v-list
                    :color="backgroundColor"
                    v-if="ranking"
                >
                    <v-list-item
                        v-for="item in ranking.data"
                        :key="item.order"
                    >
                        <v-list-item-avatar>
                            <v-icon
                                :class="getClass(item.order)"
                                v-text="item.order"
                            ></v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title>{{item.company_name}}</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-action>
                        <span>
                            <span class="score">{{item.percentage}}</span>
                            %
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
    name: "CompanyRankingCard",
    components: {SimpleCardDialog, DataTable},
    props: {
        order: {
            type: String,
            required: true
        },
        title: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            headers: [
                {text: '順位', value: 'order', sortable: false},
                {text: '企業名', value: 'company_name', sortable: false},
                {text: '達成率', value: 'percentage', sortable: false},
            ],
            data: {},
            loading:false,
            ranking: undefined,
            backgroundColor: 'blue lighten-3'
        }
    },
    created() {
        this.getRanking();
        if (this.order === 'worst') {
            this.backgroundColor = 'pink lighten-3'
        }
    },
    methods: {
        async getRanking(){
            try{
                this.loading = true;
                let params = {order: this.order};
                const ret = await this.$axios.get(this.$utils.getApiUrl(this.$apis.company_ranking, true),{params: params});
                this.ranking = ret.data;
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
}
.scroll_zone{
    overflow: scroll;
    max-height: 311px;
}
</style>
