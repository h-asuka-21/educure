<template>
    <v-list-item
        dark
        @click="$router.push(link)">
        <v-list-item-icon v-if="icon">
            <v-icon>
                {{icon}}
            </v-icon>
        </v-list-item-icon>
        <v-list-item-title>{{title}}</v-list-item-title>
    </v-list-item>
</template>
<script>
    export default {
        name: 'LinkItem',
        props: {
            link: {
                type:String,
                required:true,
            },
            icon: {
                type:String,
                required:true,
            },
            title: {
                type:String,
                required:true,
            },
        },
    }
</script>
