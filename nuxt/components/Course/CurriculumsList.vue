<template>
    <v-card outlined
            :loading="loading"
            :disabled="loading"
    >
        <v-card-title>カリキュラム</v-card-title>
        <v-card-text>
            <v-row dense>
                <v-col cols="11">
                    <autocomplete-with-api
                        ref="autocomplete"
                        v-model="add"
                        :url="$utils.getApiUrl($apis.autocomplete.curriculum,true,true)"
                        label="カリキュラムを追加"
                        @text="v => this.add_text = v"
                    ></autocomplete-with-api>
                </v-col>
                <v-col cols="1">
                    <v-btn dark :color="$colors.main" @click="AddCurriculum">追加</v-btn>
                </v-col>
                <v-col cols="12">
                    ドラッグアンドドロップで順序を入れ替えることができます
                </v-col>
            </v-row>
            <draggable
                v-if="curriculums.length > 0"
                class="row" v-model="curriculums" draggable=".item">
                <v-col cols="4" class="item" v-for="(curriculum,key) in curriculums" :key="key">
                    <v-card>
                        <v-card-title>
                            {{key + 1}}
                            <v-spacer>
                            </v-spacer>
                            <icon-close-button
                                @click="setDelete(key)"
                            ></icon-close-button>
                        </v-card-title>
                        <v-card-text>
                            {{ curriculum.name }}
                        </v-card-text>
                    </v-card>
                </v-col>
            </draggable>
            <p v-else class="pt-3">登録済みのカリキュラムがありません。</p>
            <v-row justify="center">
                <v-btn dark :color="$colors.main" @click="saveCurriculums">登録</v-btn>
            </v-row>
        </v-card-text>
        <confirm-dialog
            ref="confirm"
            message="カリキュラムを削除してもよろしいですか？"
            @confirm="deleteCurriculum"
        ></confirm-dialog>
    </v-card>
</template>

<script>

import IconCloseButton from "~/components/Atoms/IconCloseButton";
import ConfirmDialog from "~/components/Molecules/ConfirmDialog";
import AutocompleteWithApi from "~/components/Molecules/Forms/AutocompleteWithApi";
export default {
    name: "CurriculumsList",
    components: {AutocompleteWithApi, ConfirmDialog, IconCloseButton},
    data() {
        return {
            curriculums: [],
            add: null,
            add_text: null,
            delete_target: null,
            loading: false,
            delete: []
        }
    },
    props: {
        edit: {
            type: Boolean,
            default: false
        }
    },
    created() {
        if(this.edit){
            this.getCurriculums();
        }
    },
    methods:{
        async getCurriculums(){
            try{
                this.loading = true;
                const result = await this.$axios.get(this.$utils.getApiUrl(this.$apis.course_curriculums,true));
                console.log(result);
                this.curriculums = result.data;
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
            }
        },
        setDelete(key){
            this.delete_target = key;
            this.$refs.confirm.show()
        },
        deleteCurriculum(){
            this.delete.push(this.curriculums[this.delete_target]);
            this.$delete(this.curriculums, this.delete_target);
            this.delete_target = null;
        },
        AddCurriculum(){
            if(this.hasCurriculum()){
                this.$store.dispatch('alert/error', this.add_text + 'は既に登録されています。');
                this.add = null;
                this.add_text = null;
                return;
            }
            this.curriculums.push({
                id: this.add,
                name: this.add_text
            })
            this.add = null;
            this.add_text = null;
        },
        hasCurriculum(){
            let ret = false;
            this.curriculums.map(v => {
                if (v.id === this.add) {
                    ret = true;
                    return;
                }
            });
            return ret;
        },
        async saveCurriculums(){
            try{
                this.loading = true;
                const ret = await this.$axios.post(this.$utils.getApiUrl(this.$apis.course_curriculums, true), this.curriculums);
                console.log(ret);
            }catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
            }
        }
    },
}
</script>

<style scoped>

</style>
