<template>
    <div>
        <v-row dense>
            <v-col cols="12">
                <v-card outlined>
                    <v-card-text class="py-1">
                        <v-row dense>
                            <v-col cols="6">
                                <autocomplete-with-api
                                    label="企業"
                                    v-model="param.company_id"
                                    :clearable="true"
                                    :url="$utils.getApiUrl($apis.autocomplete.company,true)"
                                ></autocomplete-with-api>
                            </v-col>
                            <v-col cols="6">
                                <date
                                    label="表示開始日"
                                    v-model="param.start_date"
                                ></date>
                            </v-col>
                            <v-col cols="12">
                                <div class="active guide">
                                    <v-icon x-small color="green">mdi-check</v-icon>は受講済み
                                </div>
                                <v-row dense justify="center">
                                    <v-btn dark @click="getItems" :color="$colors.main">検索</v-btn>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col v-for="(item,key) in data" cols="6" :key="key">
                <v-card outlined class="fill-height">
                    <v-card-title>{{$moment(key).format('YYYY年M月D日')}}({{$utils.getWeekStr($moment(key))}})
                        <v-spacer/>
                        <span v-if="item.length">
                            {{item.length}}人
                        </span>
                    </v-card-title>
                    <v-card-text class="px-0 pb-2">
                        <div v-if="!item.length" class="text-center">
                            予約がありません。
                        </div>
                        <div v-else>
                            <v-list dense subheader three-line>
                                <v-list-item v-for="student in item"
                                             @click="$router.push($utils.getHomeUrl()+'/student/'+student.id)"
                                             :key="student.id"
                                             three-line
                                             :class="isActive(student)">
                                    <v-list-item-content class="pl-1 px-0 py-0">
                                        <v-list-item-title>
                                            <v-icon v-if="student.attendance_flg" x-small>mdi-check</v-icon>
                                            {{ student.name }}({{student.company_name}})</v-list-item-title>
                                        <v-list-item-subtitle>{{$moment('2020-01-01 '+ student.start_time).format('HH:mm')}} - {{$moment('2020-01-01 '+ student.end_time).format('HH:mm')}}</v-list-item-subtitle>
                                        <v-list-item-subtitle></v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import AutocompleteWithApi from "@/components/Molecules/Forms/AutocompleteWithApi";
import Date from "@/components/Molecules/Forms/Date";
export default {
    name: "index",
    components: {Date, AutocompleteWithApi},
    data() {
        return {
            data: [],
            loading: true,
            param: {}
        }
    },
    created() {
        console.log(this.$route);
        this.$store.dispatch('page/hideTab');
        this.$store.dispatch('page/hideBackButton');
        this.$store.dispatch('page/setBackUrl', this.$route.path);
        this.param.start_date = this.$moment().format('YYYY-MM-DD');
        this.getItems();
    },
    methods:{
        async getItems(){
            try{
                await this.$store.dispatch('loader/showSub');
                const ret = await this.$axios.get(this.$utils.getApiUrl(this.$apis.reserved_students,true),{params:this.param});
                this.data = ret.data;
                console.log(this.data);
            }catch (e){
                this.$utils.catchError(e);
            } finally {
                await this.$store.dispatch('loader/hideSub');
            }
        },
        isActive(student){
            if(student.attendance_flg){
                return 'v-list-item--active';
            }
            return '';
        }
    }
}
</script>

<style scoped lang="scss">
.v-list-item{
    height: 36px!important;
    min-height: auto!important;
    padding: 0!important;
    border-bottom: 1px solid #9E9E9E;
    &.v-list-item--active {
        color:#4CAF50!important;
        .v-list-item__subtitle{
            color:#4CAF50!important;
        }
    }
}
.active{
    color:#4CAF50!important;
    &.guide{
        position: absolute;
        top: 54px;
    }
}
</style>
