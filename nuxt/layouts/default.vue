<template>
    <div id="app">
        <v-app>
            <v-main id="main-content" v-bind:class="$utils.getThemeClass()">
                <alert></alert>
                <loader></loader>
                <v-container fluid class="main-container" v-bind:class="$utils.isLogin()? 'logged_in':''">
                    <v-row>
                        <v-col class="nav_bar" v-if="!$store.state.loader.enable && $store.state.page.sideBar">
                            <sidebar id="v-step-1"></sidebar>
                        </v-col>
                        <v-col class="main" v-if="!$store.state.loader.enable">
                            <Nuxt v-if="!$store.state.page.notFound"/>
                            <not-found v-else/>
                        </v-col>
                    </v-row>
                </v-container>
            </v-main>
        </v-app>
    </div>
</template>
<script>
    import Alert from "../components/Atoms/Alert";
    import Loader from "../components/Atoms/Loader";
    import Sidebar from "@/components/Molecules/Sidebar";
    import NotFound from "@/components/Molecules/NotFound";
    export default {
        components: {NotFound, Loader, Alert, Sidebar},
        data() {
            return {
                loaded: false,
                route_type: 'student'
            }
        },
        created() {
            if (this.$route.path.indexOf('/master') === 0) {
                this.route_type = 'master';
                this.$store.dispatch('admin/isLogin');
            } else if (this.$route.path.indexOf('/admin') === 0) {
                this.route_type = 'admin';
                this.$store.dispatch('user/isLogin');
            } else {
                this.$store.dispatch('student/isLogin');
            }
        }
    }
</script>
<style lang="scss">
    html {
        font-family: 'Source Sans Pro',
        -apple-system,
        BlinkMacSystemFont,
        'Segoe UI',
        Roboto,
        'Helvetica Neue',
        Arial,
        sans-serif;
        font-size: 16px;
        word-spacing: 1px;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
        -moz-osx-font-smoothing: grayscale;
        -webkit-font-smoothing: antialiased;
        box-sizing: border-box;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
    }

    #main-content {
        &.student {
            background-color: #E0F2F1;
        }
        &.admin{
            background-color: #E0F7FA;
        }
        &.master {
            background-color: #f9f2fa;
        }

        > .v-main__wrap {
            > .main-container {
                &.logged_in {
                    padding-left: 56px;
                    min-height: 90%;
                    > .row{
                        min-height: 91vh;
                        > div {
                            min-height: 91vh;
                        }
                    }
                }
            }
        }

        .col {
            &.nav_bar {
                max-width: 0;
                width: 0;
                padding: 0;
            }

            &.main {
                padding-left: 60px;
                margin-left: -20px;
            }
        }
    }
</style>
