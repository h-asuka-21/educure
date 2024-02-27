export const state = () => ({
    show_tab:true,
    title: null,
    show_back_button:false,
    tabs:false,
    backurl: null,
    show_add:false,
    notFound: false,
    sideBar: true
});

export const mutations = {
    hideTab(state) {
        state.show_tab = false;
    },
    showTab(state) {
        state.show_tab = true;
    },
    add(state,val) {
        state.show_add = val;
    },
    hideBackButton(state) {
        state.show_back_button = false;
    },
    showBackButton(state) {
        state.show_back_button = true;
    },
    setTitle(state,title) {
        state.title = title;
    },
    setTabs(state,tabs) {
        state.tabs = tabs;
    },
    setBackUrl(state,url) {
        state.backurl = url;
    },
    notFound(state, val) {
        state.notFound = val
    },
    sideBar(state,val){
        state.sideBar = val
    }
};

export const actions = {
    hideTab({commit,state}){
        commit('hideTab');
    },
    showTab({commit,state}){
        commit('showTab');
    },
    setTabs({commit, state}, tabs) {
        commit('setTabs', tabs);
    },
    setTitle({commit,state},title){
        commit('setTitle',title);
    },
    setBackUrl({commit,state},url){
        commit('setBackUrl',url);
    },
    hideBackButton({commit,state}) {
        commit('hideBackButton');
    },
    showBackButton({commit,state}) {
        commit('showBackButton');
    },
    notFound({commit},val=true){
        commit('notFound', val);
    },
    sideBar({commit},val=true){
        commit('sideBar', val);
    }
};
