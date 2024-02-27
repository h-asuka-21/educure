<template>
    <v-tabs
        id="navigation_tabs" v-bind:class="$utils.getThemeClass()"
        v-model="active"
    >
        <v-tab v-for="(item,key) in value" :key="key" @click="$router.push(item.url)" v-bind:class="$utils.getThemeClass()">
            {{item.name}}
        </v-tab>
    </v-tabs>
</template>

<script>
    export default {
        name: "Tabs",
        props: {
            value: {
                required: true,
                type: Array
            }
        },
        data(){
            return{
                active:0
            }
        },
        watch:{
            "$route.path"(val){
                this.setCurrentTab();
            },
            value(val){
                this.setCurrentTab();
            }
        },
        created() {
            this.setCurrentTab();
        },
        methods:{
            setCurrentTab(){
                this.value.map((v, k) => {
                    if(this.$route.path === v.url){
                        this.active = k;
                    }
                });
            }

        }
    }
</script>

<style lang="scss">
    #navigation_tabs{
        background-color: transparent;
        > .v-item-group{
            background-color: transparent;
        }
        &.master{
            .v-tabs-slider{
                color: #E040FB
            ;
            }
        }
        &.admin{
            .v-tabs-slider{
                color: #00ACC1;
            }
        }
        &.student{
            .v-tabs-slider{
                color: #009688;
            }
        }
        .v-tab{
            &.v-tab--active{
                &.master{
                    color: #AB47BC;
                }
                &.admin{
                    color: #00ACC1;
                }
                &.student{
                    color: #009688;
                }
            }
        }
    }
</style>
