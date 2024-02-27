<template>
    <div>
        <v-file-input
            v-if="!name"
            chips
            outlined
            :label="label"
            v-model="data"
            dense
            accept="application/zip"
            show-size
            :disabled="disabled"
        >
        </v-file-input>
        <text-input
            v-else
            :label="label"
            v-model="name"
            :clearable="true"
            :readonly="true"
            :disabled="disabled"
            icon="mdi-paperclip"
        ></text-input>
    </div>
</template>

<script>
import TextInput from "@/components/Molecules/Forms/TextInput";
export default {
    name: "ZipInput",
    components: {TextInput},
    props: {
        label: {
            type: String,
            required: true
        },
        value: {
            required: true
        },
        file_name: {
            type: String,
            default: undefined
        },
        disabled:{
            type:Boolean,
            default:false
        },
    },
    data() {
        return {
            data: this.value,
            name: this.file_name
        }
    },
    created() {
    },

    watch: {
        value(val) {
            this.data = val;
        },
        data(val) {
            this.$emit('input', val);
        },
        file_name(v) {
            this.name = v
        },
        name(v) {
            if(v === null){
                this.data = null;
            }
            this.$emit('name', v);
        },
    },
    methods: {}

}
</script>

<style scoped>

</style>
