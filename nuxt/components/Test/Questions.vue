<template>
    <v-container>
        <v-row dense>
            <v-col v-if="show">
                <p class="subtitle">設問</p>
                <p class="overline">ドラッグアンドドロップで順序を変更できます。</p>
            </v-col>
        </v-row>
        <v-skeleton-loader
            type="image"
            :loading="loading"
        >
            <v-row
                dense
            >
                <v-col v-if="data.length > 0" >
                    <draggable
                        class="row dense" v-model="data" draggable=".item" >
                        <v-col cols="6"
                               class="item"
                               v-for="(item,key) in data " :key="key"
                        >
                            <item
                                :value="item"
                                @edit="addQuestion"
                                @delete="showConfirm(key)"
                                :show="show"
                            ></item>
                        </v-col>
                    </draggable>
                </v-col>
                <v-col v-else>
                    <p class="subtitle">設問が登録されていません</p>
                </v-col>
            </v-row>
            <v-row dense>
                <v-col class="d-flex justify-center">
                    <v-btn v-if="show"
                           color="secondary"
                           id="add_question"
                           dark @click="addQuestion()">追加</v-btn>
                </v-col>
            </v-row>
        </v-skeleton-loader>
        <edit-dialog
            ref="dialog"
            v-model="edit_item"
            @save="setDialog"
        >

        </edit-dialog>
        <confirm-dialog
            ref="confirm"
            message="この設問を削除してもよろしいですか？"
            @confirm="deleteQuestion"
        ></confirm-dialog>
    </v-container>
</template>

<script>
    import Item from "./Questions/Item";
    import EditDialog from "./Questions/EditDialog";
    import ConfirmDialog from "../Molecules/ConfirmDialog";

    export default {
        name: "Questions",
        components: {ConfirmDialog, EditDialog, Item},
        props: {
            value: {
                type: Array,
                required: true
            },
            loading: {
                type: Boolean,
                default: false
            },
            show: {
                type: Boolean,
                default: true
            }
        },
        data() {
            return {
                data: this.value,
                edit_item: {},
                is_edit: true,
                delete_key: null
            }
        },
        watch: {
            value(val) {
                this.data = val;
            },
            data(val) {
                this.$emit('input', val);
            }
        },
        methods: {
            addQuestion(item = null) {
                this.edit_item = item;
                this.is_edit = true;
                if (item === null) {
                    this.edit_item = {
                        name: null,
                        test_id: this.$route.params.id,
                        content: null,
                        image: null,
                        choice1: 1,
                        choice2: 2,
                        choice3: 3,
                        choice4: 4,
                        answer: 1
                    }
                    this.is_edit = false;
                }
                this.$refs.dialog.$refs.dialog.show();
            },
            setDialog() {
                if (!this.is_edit) {
                    this.data.push(this.edit_item)
                }
                this.edit_item = {};
            },
            showConfirm(key) {
                this.delete_key = key;
                this.$refs.confirm.show();
            },
            deleteQuestion() {

                if(this.data[this.delete_key].id !== undefined && this.data[this.delete_key].id !== null){
                    this.$emit('deleted',this.data[this.delete_key].id)
                }
                this.$delete(this.data, this.delete_key);

                this.delete_key = null;
            }
        },
    }
</script>

<style scoped>

</style>
