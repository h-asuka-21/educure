<template>
    <v-navigation-drawer
        v-if="is_login"
        id="side_bar"
        expand-on-hover
        fixed
        dark
        permanent
        color="sidebar"
    >
        <v-list class="list">
            <v-list-item class="d-flex" id="logo_area">
                <v-img src="/image/educure_logo.png" alt="educure" id="logo"/>
                <v-img src="/image/educure_text.png" id="logo_text"/>
            </v-list-item>
            <v-list-item-group
                v-model="selected"
                dark
                :color="this.active_color"
            >
                <link-item
                    v-for="(item,key) in items"
                    :key="key"
                    :title="item.title"
                    :icon="item.icon"
                    :link="item.url"
                ></link-item>
            </v-list-item-group>
            <v-spacer/>
        </v-list>
        <v-list class="bottom">
            <v-list-item dark @click="$refs.profile.show()" two-line>
                <v-list-item-icon class="pt-3">
                    <v-icon>
                        mdi-face-profile
                    </v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>{{$utils.getUserName()}}</v-list-item-title>
                    <v-list-item-subtitle v-if="$utils.isStudent() || $utils.isUser()">{{$utils.getCompanyName()}}</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
            <v-list-item dark @click="$store.dispatch( $utils.getApiType().replace('/','')+'/logout')">
                <v-list-item-icon>
                    <v-icon>
                        mdi-logout
                    </v-icon>
                </v-list-item-icon>
                <v-list-item-title>ログアウト</v-list-item-title>
            </v-list-item>
        </v-list>
        <edit-profile ref="profile"/>
    </v-navigation-drawer>
</template>

<script>
import LinkItem from "./Sidebar/LinkItem";
import ProfileCard from "@/components/Student/Detail/ProfileCard";
import EditProfile from "@/components/Molecules/Sidebar/EditProfile";

export default {
    name: "Sidebar",
    components: {EditProfile, ProfileCard, LinkItem},
    data() {
        return {
            selected: null,
            items: [],
            active_color: ''
        }
    },
    computed: {
        is_login: {
            get() {
                return this.$utils.isLogin();
            },
            set(v) {
            }
        }
    },
    watch: {
        is_login(val) {
            this.getSidebarItems()
        }
    },
    created() {
        this.is_login = this.$utils.isLogin();
        this.getSidebarItems();
    },
    methods: {
        getSidebarItems() {
            if (this.is_login) {
                this.items = this.$sidebars[this.$utils.getThemeClass()];
                this.setActive(this.items);
                this.active_color = this.$colors[this.$utils.getThemeClass()].sidebar_active;
            }
        },
        setActive(items) {
            if (this.$utils.getHomeUrl() === this.$route.path) {
                this.selected = 0;
                return;
            }
            items.map((v, k) => {
                if (
                    this.$utils.getHomeUrl() !== this.$route.path &&
                    this.$route.path.indexOf(v.url) > -1) {
                    this.selected = k;
                }
            });
        }
    }

}
</script>

<style lang="scss" scoped>
#side_bar {
    height: 100vh !important;
    z-index: 99;
}
#logo_area{
    padding: 0 8px;
    #logo{
        max-width: 40px;
    }
    #logo_text{
        margin-left: 16px;
        max-width: 159px;
        margin-bottom: 8px;
    }
}
.v-navigation-drawer__content{
    position: relative;
}
.list{
    overflow: scroll;
    max-height: 75%;
}
.bottom{
    position: absolute;
    bottom: 0;
    width: 100%;
}
</style>
