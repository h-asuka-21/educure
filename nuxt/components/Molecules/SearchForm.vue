<template>
    <v-card outlined class="mb-3">
        <v-card-text class="py-1 px-2">
            <v-row v-if="keys !== undefined" dense class="mt-2 justify-center">
                <search-input
                    v-for="(item,key) in keys" :key="key"
                    :label="item.search_label||item.text"
                    v-model="value[item.search||item.value]"
                    :type="item.type"
                    :prefix="item.prefix"
                    :suffix="item.suffix"
                    :cols="getCols(keys)"
                    :searchable ="item.searchable"
                ></search-input>
            </v-row>
            <v-row class="pt-1" dense >
                <v-col id="buttons" class="d-flex pt-0 justify-center">
                    <v-btn
                        dark
                        :color="$colors.check"
                        @click="clear()"
                        class="search"
                    >
                        クリア
                    </v-btn>
                    <v-btn
                        dark
                        :color="$colors.main"
                        @click="$emit('search',value)"
                        class="ml-2 search"
                    >
                        検索
                    </v-btn>
                    <add-button
                        class="add_btn"
                        v-if="show_add"
                        @click="$emit('add')"
                        :label="add_label||'新規作成'"
                    >
                    </add-button>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
<script>
    import SearchInput from "./SearchForm/SearchInput";
    import AddButton from "../Atoms/AddButton";

    export default {
        components: {
            AddButton,
            SearchInput
        },
        props: {
            keys: {
                type: Array,
                required: true
            },
            value: {
                type: Object,
                required: true
            },
            type: {
                type: String,
                default: undefined
            },
            add_label: {
                type: String,
                default: undefined
            },
            show_add: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                cols: 'auto'
            }
        },
        created() {
            this.setCols();
        },
        methods: {
            setCols() {
                let keyLength = this.getKeyLength();

                if (keyLength === 2) {
                    this.cols = 6;
                }
                if (keyLength === 3) {
                    this.cols = 4;
                }
                if (keyLength === 4) {
                    this.cols = 3;
                }
                if (keyLength === 5) {
                    this.cols = 3;
                }
                if (keyLength === 6) {
                    this.cols = 4;
                }
                if (keyLength >= 7) {
                    this.cols = 3;
                }
            },
            clear() {
                this.keys.map((val) => {
                    if (val.required !== true) {
                        this.value[val.value] = null;
                    }
                });
                this.$emit('search', this.value);
            },
            getKeyLength() {
                let keyLength = 0;
                this.keys.map(v => {
                    if (v.searchable !== false) {
                        keyLength++;
                    }
                })
                return keyLength;
            },
            createInvoice() {
                this.$router.push('/invoice/create')
            },
            createPaymentNotice() {
                this.$router.push('/invoice/create_payment_notice')
            },
            getCols(key){
                let keyLength = this.getKeyLength();
                if(keyLength === 5){
                    if(key < 3){
                        return 4;
                    }
                    return 6;
                }
                if(keyLength === 7){
                    if(key < 6){
                        return 4;
                    }
                    return 6;
                }
                return this.cols;
            }
        }
    }
</script>
<style lang="scss" scoped>
#buttons{
    position: relative;
    .search{
        width: 70px;
    }
    .add_btn{
        position: absolute;
        right: 4px;
    }
}
</style>
