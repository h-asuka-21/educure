import createPersistedState from 'vuex-persistedstate'

export default ({ store }) => {

    window.onNuxtReady(() => {
        createPersistedState({
            key: 'educure_state',
            paths: [
                "test"
            ],
        })(store)
    })
}
