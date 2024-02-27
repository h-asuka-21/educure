<template>
    <v-container>
        <v-row dense>
            <v-col>ドラッグアンドドロップで順序を入れ替えることができます</v-col>
        </v-row>
        <draggable
            class="row row--dense"
            v-model="data"
            draggable=".step_items"
            v-if="data.length > 0"
            :disabled="disabled"
        >
            <v-col cols="6"
                   class="step_items"
                   v-for="(item,key) in data " :key="key"
            >
                <item
                    :value="item"
                    @edit="addStep"
                    @delete="showConfirm(key)"
                    :show="show"
                ></item>
            </v-col>
        </draggable>
        <v-row
            dense
            v-else>
            <v-col class="subtitle">ステップが登録されていません</v-col>
        </v-row>
        <v-row dense>
            <v-col class="d-flex justify-center">
                <v-btn v-if="show"
                       color="secondary"
                       id="add_step"
                       dark @click="addStep()">追加</v-btn>
            </v-col>
        </v-row>
        <edit-dialog
            ref="dialog"
            v-model="edit_item"
            @save="setDialog"
        >

        </edit-dialog>
        <confirm-dialog
            ref="confirm"
            message="このステップを削除してもよろしいですか？"
            @confirm="deleteStep"
        ></confirm-dialog>
    </v-container>
</template>

<script>
    import Item from "./Steps/Item";
    import EditDialog from "./Steps/EditDialog";
    import ConfirmDialog from "../Molecules/ConfirmDialog";

    export default {
        name: "Steps",
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
            },
            disabled:{
                type:Boolean,
                default: false
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
            addStep(item = null) {
                this.edit_item = item;
                this.is_edit = true;
                if (item === null) {
                    this.edit_item = {
                        name: null,
                        curriculum_id: this.$route.params.id,
                        content: null,
                        image: null,
                        target_days: null,
                        deadline_days: null,
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
            deleteStep() {

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
