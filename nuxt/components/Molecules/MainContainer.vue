<template>
    <v-container id="content_container">
        <sub-loader></sub-loader>
        <v-row dense>
            <v-col class="title_col">
                <v-skeleton-loader
                    :loading="$store.state.page.title === null"
                    type="heading"
                >
                    <p class="title">{{$store.state.page.title}}</p>
                </v-skeleton-loader>
            </v-col>
        </v-row>
        <v-row
            v-if="$store.state.page.show_back_button"
            dense>
            <v-col class="tabs_col">
                <v-btn
                    class="page_back_btn"
                    icon
                    @click="back"
                >
                    <v-icon>mdi-skip-previous</v-icon>
                    戻る
                </v-btn>
            </v-col>
        </v-row>
        <v-row
            v-if="$store.state.page.show_tab"
            dense>
            <v-col class="tabs_col">
                <tabs v-model="$store.state.page.tabs || tabs"></tabs>
            </v-col>
        </v-row>
        <v-row dense>
            <v-col class="main_col">
                <slot></slot>
            </v-col>
        </v-row>
    </v-container>

</template>

<script>
    import Tabs from "./MainContainer/Tabs";
    import SubLoader from "../Atoms/SubLoader";
    export default {
        name: "MainContainer",
        components: {SubLoader, Tabs},
        props:{
            title:{
                required:true,
                type:String
            },
            tabs:{
                required:true,
                type:Array
            }
        },
        created() {
            this.$store.dispatch('page/setTitle', this.title);
        },
        methods:{
            back(){
                if(this.$store.state.page.backurl) {
                    this.$router.push(this.$store.state.page.backurl);
                    return;
                }
                this.$router.back()
            }
        }
    }
</script>

<style lang="scss">
    #content_container{
        max-width: 98%;
    }
</style>
