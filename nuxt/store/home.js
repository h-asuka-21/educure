export const state = () => ({
    delay_alert: null
});

export const mutations = {
    // スケジュール遅延アラート
    delayAlert(state,param) {
        state.delay_alert = param;
    },
};

export const actions = {
    // スケジュール遅延アラート
    delayAlert({commit,state},param){
        commit('delayAlert',param);
    },
};

export const getters = {
    getDelayAlert(state){
        return state.delay_alert;
    }
};
