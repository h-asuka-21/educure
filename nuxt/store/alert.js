import Vue from 'vue'

export const state = () => ({
    enable:false,
    message: '',
    color:null
});

export const mutations = {
    show(state, params) {
        state.enable = true;
        state.message = params.message;
        state.color = params.color;
    },
    hide(state){
        state.enable = false;
        state.message = '';
        state.color = null;
    },
    setStatus(state,val){
        state.enable = val;
    }
};

export const actions = {
    success({commit,state},message){
        commit('show',{
            message:message,
            color: 'success'
        })
    },
    info({commit,state},message){
        commit('show',{
            message:message,
            color: 'info'
        })
    },
    warning({commit,message}){
        commit('show',{
            message:message,
            color: 'warning'
        })
    },
    error({commit,state}, message){
        commit('show',{
            message:message,
            color: 'error'
        })
    },
    clear({commit}){
        commit('hide');
    }
};

export const getters = {
    getEnable: state => {
        return state.enable;
    }
};
