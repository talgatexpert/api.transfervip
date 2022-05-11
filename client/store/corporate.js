export const state = () => ({
    success: '',
    errors: [],

})

export const mutations = {

    SET_SUCCESS(state, payload) {
        state.success = payload
    },
    SET_ERROR(state, payload) {
        state.errors = payload
    },

}
export const actions = {
    async sendCorporate({commit}, payload) {
        this.$axios.post('corporate', payload).then(({data}) => {
            if (data.success) {
                commit('SET_SUCCESS', true)
            }
        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    }
}
export const getters = {
    success: (state) => state.success,

}
