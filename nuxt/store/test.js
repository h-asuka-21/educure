export const state = () => ({
    data:null,
    score_id:null,
    start_time: null,
    test_url: null
});

export const mutations = {
    // スケジュール遅延アラート
    data(state,param) {
        state.data = param;
    },
    setAnswer(state, {param,key}) {
        state.data.questions[key] = param;
    },
    score_id(state,param) {
        state.score_id = param;
    },
    start_time(state,param) {
        state.start_time = param;
    },
    test_url(state,param) {
        state.test_url = param;
    },
};

export const actions = {
    // スケジュール遅延アラート
    data({commit,state},param) {
        commit('data',param);
    },
    setAnswer({commit, state}, {param,key}) {
        commit('setAnswer', {param:param,key:key});
    },
    score_id({commit,state},param) {
        commit('score_id',param);
    },
    start_time({commit,state},param) {
        commit('start_time',param);
    },
    test_url({commit,state},param) {
        commit('test_url',param);
    },
    clear({commit,state}) {
        commit('data',null);
        commit('score_id',null);
        commit('start_time',null);
        commit('test_url',null);
    },
};
