<template>
    <v-simple-table>
        <tbody>
        <tr v-for="(header,key) in headers" :key="key">
            <th>{{header.text}}</th><td>{{header.prefix}}{{showData(item[header.value],header)}}{{header.suffix}}</td>
        </tr>
        </tbody>
    </v-simple-table>

</template>

<script>
export default {
    name: "VerticalTable",
    props:{
        headers:{
            type:Array,
            required:true
        },
        item:{
            type: Object,
            required:true
        }
    },
    methods: {
        showData(item,header){
            if(item === undefined||item === null){
                return '';
            }
            if (header.type === 'date') {
                return this.$moment(item).format('YYYY年M月D日');
            } else if (header.type === 'time') {
                return this.$moment(item).format('HH:mm');
            }
            return item;

        }
    }
}
</script>

<style scoped>

</style>
