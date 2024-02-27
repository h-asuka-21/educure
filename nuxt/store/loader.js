export const state = () => ({
    enable:true,
    sub_enable:false,
});

export const mutations = {
    switch(state,val) {
        state.enable = val;
    },
    switchSub(state,val) {
        state.sub_enable = val;
    },
};

export const actions = {
    show({commit}){
        commit('switch', true);
    },
    hide({commit}){
        commit('switch', false);
    },
    showSub({commit}){
        commit('switchSub', true);
    },
    hideSub({commit}){
        commit('switchSub', false);
    }
};
