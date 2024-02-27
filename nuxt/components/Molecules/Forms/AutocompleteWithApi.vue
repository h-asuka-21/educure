<template>
    <v-autocomplete
        outlined
        :items="items"
        :loading="loading"
        :disabled="disabled"
        :label="label"
        v-model="data"
        :rules="rules"
        :dense="dense"
        :clearable="clearable"
        :id="id"
        value="{ data }"
        :hint="hint"
        :persistent-hint="persistentHint"
        :solo-inverted="solo_inverted"
        :hide-details="hide_details"
    />
</template>

<script>
export default {
    name: "AutocompleteWithApi",
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
        id: {
            type: String,
            default: undefined
        },
        disabled: {
            type: Boolean,
            default: false
        },
        rules: {
            type: Array,
            default: undefined
        },
        hint: {
            type: String,
            default: undefined
        },
        persistentHint: {
            type: Boolean,
            default: false
        },
        solo_inverted: {
            type: Boolean,
            default: false
        },
        hide_details: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            data: this.value,
            loading: true,
            items: [],
        };
    },
    watch: {
        value(val) {
            this.data = val;
        },
        data(val) {
            this.$emit('input', val);
            this.items.map((v) => {
                if (v.value === val) {
                    this.$emit('text', v.text);
                }
            })
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
                this.items = res.data;
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
            }
        },
        setRequired() {
            if (this.required) {
                this.rules.push(v => this.$validation.required(v, this.label));
            }
        },
    }
}
</script>

<style scoped>

</style>
