<template>
    <v-col v-if="searchable !== false && label !== ''" :cols="cols" class="py-0 search-input">
        <date
            v-if="type === 'date'"
            :label="label"
            :rules="rules"
            :clearable="clearable"
            v-model="data"
            :prefix="prefix"
            :suffix="suffix"
            :dense="true"
        ></date>
        <select-input
            v-else-if="type==='test_type'"
            :label="label"
            v-model="data"
            :clearable="true"
            :items="$selects.test_type"
        >
        </select-input>
        <select-input
            v-else-if="type==='missing_type'"
            :label="label"
            v-model="data"
            :clearable="true"
            :items="$selects.missing_type"
        >
        </select-input>
        <autocomplete-with-api
            :clearable="true"
            v-else-if="type==='course'"
            label="コース"
            v-model="data"
            :url="$utils.getApiUrl($apis.autocomplete.course_group,true)"
            />
        <autocomplete-with-api
            :clearable="true"
            v-else-if="type==='curriculum'"
            label="カリキュラム"
            v-model="data"
            :url="$utils.getApiUrl($apis.autocomplete.curriculum,true,true)"
            />
        <autocomplete-with-api
            :clearable="true"
            v-else-if="type==='company'"
            label="企業"
            v-model="data"
            :url="$utils.getApiUrl($apis.autocomplete.company,true,true)"
            />
        <select-input
            v-else-if="type === 'after_graduation_flg'"
            :label="label"
            v-model="data"
            :clearable="true"
            :items="$selects.after_graduation_flg"
        ></select-input>
        <v-switch
            class="mt-1 pl-8"
            v-else-if="type==='flg'"
            :label="label"
            v-model="data"
        ></v-switch>
        <text-input
            v-else
            outlined
            :label="label"
            :rules="rules"
            :clearable="true"
            v-model="data"
            :prepend-icon="prefix"
            :append-outer-icon="suffix"
            :type="getType(type)"
        >
        </text-input>
    </v-col>
</template>
<script>
    import Date from "../Forms/Date";
    import TextInput from "../Forms/TextInput";
    import AutocompleteWithApi from "../Forms/AutocompleteWithApi";
    import SelectInput from "../Forms/SelectInput";
    export default {
        components: {
            SelectInput,
            AutocompleteWithApi,
            TextInput,
            Date
        },
        props:{
            label:{
                type:String,
                required:true
            },
            value:{
                required:true
            },
            type:{
                required:true
            },
            // validationルール
            rules:{
                default: undefined
            },
            clearable:{
                type:Boolean,
                default:false
            },
            prefix: {
                type: String,
                default: ''
            },
            suffix:{
                type:String,
                default:''
            },
            cols:{
                type:[String,Number],
                default:'auto'
            },
            searchable:{
                default:undefined
            }
        },
        data() {
            return {
                data: this.value,
                input: null
            }
        },
        watch:{
            value(val) {
                this.data = val;
            },
            data(val){
                this.$emit('input', val);
            },
        },
        methods:{
            getType(type){
                if(type === 'int' || type === 'price'){
                    return 'number';
                }
                return '';
            }
        }
    }
</script>
<style lang="scss">
.search-input{
    .v-text-field__details{
        display: none!important;
    }
}
</style>
