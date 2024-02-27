/**
 * master管理ユーザーのログイン状態管理
 */

import Vue from 'vue'

const authRoot = '/admin'
const $this = Vue.prototype;
export const state = () => ({
    is_login:false,
    data:{}
});

export const mutations = {
    setLoginState(state, status) {
        state.is_login = status;
    },
    setUserData(state,data){
        state.data = data;
    }
};

export const actions = {
    /**
     * ログイン実行
     * @param commit
     * @param state
     * @param data
     */
    async login({commit,state},data){
        $this.$nuxt.$store.dispatch('loader/show');
        try {
            const ret = await this.$axios.post(authRoot + $this.$apis.auth.login, data);
            console.log(ret.data);
            commit('setLoginState', true);
            commit('setUserData', ret.data);
        } catch (e) {
            console.error(e.response);
            $this.$nuxt.$store.dispatch('alert/error', 'ログインに失敗しました。メールアドレスとパスワードを確認してください');
        } finally {
            $this.$nuxt.$store.dispatch('loader/hide');
        }
    },
    async logout({commit,state},data){
        $this.$nuxt.$store.dispatch('loader/show');
        try{
            const ret = await this.$axios.post(authRoot + $this.$apis.auth.logout, data);
            commit('setLoginState',false);
            commit('setUserData',{});
            $this.$nuxt.$store.dispatch('alert/success', 'ログアウトしました');
        }catch(e) {
            $this.$utils.catchError(e)
        }finally {
            $this.$nuxt.$store.dispatch('loader/hide');
        }
    },
    async isLogin({commit,state}){
        $this.$nuxt.$store.dispatch('loader/show');
        try{
            const ret = await this.$axios.post(authRoot + $this.$apis.auth.me);
            console.log(ret);
            commit('setLoginState',true);
            commit('setUserData',ret.data);
        } catch (e) {
            if(state.is_login === true){
                // 既存のログイン状態がログイン済みだった場合
                $this.$utils.catchError(err)
            }
            commit('setLoginState',false);
            commit('setUserData',{});
        } finally {
            $this.$nuxt.$store.dispatch('loader/hide');
        }
    },
    clearStatus({commit}){
        commit('setLoginState',false);
        commit('setUserData',{});
    },
    setCompanyData({commit},data){
        commit('setCompanyData', data);
    }
};

export const getters = {
    isLogin(state) {
        return state.is_login
    },
    data(state){
        return state.data
    }
};
