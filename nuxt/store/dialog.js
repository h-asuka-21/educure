export const state = () => ({
    loading:false,
    disable:false
});

export const mutations = {
    loading(state,val) {
        state.loading = val;
        state.disable = val;
    },
    disable(state,val) {
        state.disable = val;
    }
};

export const actions = {
    loading({commit},val = true){
        commit('loading', val);
    },
    disable({commit},val = true){
        commit('disable', val);
    },
};
