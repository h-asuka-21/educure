<template>
    <v-autocomplete
        class="multi-autocomplete"
        multiple
        outlined
        :items="items"
        :loading="loading"
        :disabled="loading || disabled"
        :label="label"
        v-model="data"
        :rules="getRules()"
        :dense="dense"
        :clearable="clearable"
        :hint="hint"
        :persistent-hint="persistentHint"
    >
        <template v-slot:item="data">
            <template>
                <v-list-item-content>
                    <v-list-item-title v-html="data.item.text"></v-list-item-title>
                    <v-list-item-subtitle v-if="data.item.description" v-html="data.item.description"></v-list-item-subtitle>
                </v-list-item-content>
            </template>
        </template>
    </v-autocomplete>

</template>

<script>
import rules from "@/plugins/validation/rules";

export default {
    name: "MultiAutocompleteWithApi",
    props: {
        url: {
            type: String,
            required: true
        },
        value: {
            required: true,
        },
        label: {
            type: String,
            required: true,
        },
        required: {
            type: Boolean,
            default: false
        },
        param: {
            type: Object,
            default: () => {
                return {}
            }
        },
        dense: {
            type: Boolean,
            default: true
        },
        clearable: {
            type: Boolean,
            default: false
        },
        rules:{
            default: undefined
        },
        itemDisabled:{
            default:'disabled'
        },
        disabled: {
            default: false
        },
        hint:{
            type:String,
            default:undefined
        },
        persistentHint:{
            type:Boolean,
            default:false
        },
    },
    data() {
        return {
            data: this.value,
            loading: true,
            items: []
        };
    },
    watch: {
        value(val) {
            this.data = val;
            const objs = [];
            if( val !== undefined && val !== null && val instanceof Array){
                val.map((v) => {
                    this.items.map(v2 => {
                        if (v2.value === v) {
                            objs.push(v2);
                        }
                    })
                })
            }
            this.$emit('objs', objs);
        },
        data(val) {
            this.$emit('input', val);
        }
    },
    created() {
        this.setRequired();
        this.callApi();
    },
    methods: {
        async callApi() {
            try {
                const res = await this.$axios.get(this.url, {params: this.param})
                const items = res.data;
                items.map(v => {
                    if(this.itemDisabled.indexOf(v.value) === -1){
                        this.items.push(v);
                    }
                })
                console.log(this.data);
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.loading = false
            }
        },
        setRequired() {
            if (this.required) {
                this.rules.push(v => this.$validation.required(v, this.label));
            }
        },
        getRules(){
            if(this.loading){
                return this.rules;
            }
            if(this.disabled){
                return [];
            }
            return this.rules;
        }
    }
}
</script>

<style lang="scss">
.multi-autocomplete{
    div.v-list-item{
        font-weight: bold !important;
        &:has(>div.disabled) {
            color:red!important;
        }
    }
}

</style>
