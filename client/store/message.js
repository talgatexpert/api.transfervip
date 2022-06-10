import {CONTACT_MESSAGE, CORPORATE, TRANSPORT} from "~/routes/main";

export const state = () => ({
    message: false,
    errors: [],
})

export const mutations = {
    SET_MESSAGE(state, payload) {
        state.message = payload
    },
    SET_ERRORS(state, payload) {
        state.errors = payload
    },

}

export const actions = {
    async SEND_MESSAGE_AS_TRANSPORT({commit}, payload) {
        await this.$axios.post(TRANSPORT, payload).then(({data}) => {
            if (data.success)
                commit('SET_ERRORS', [])
            commit('SET_MESSAGE', true)
        }).catch(error => {
            commit('SET_MESSAGE', false)
            commit('SET_ERRORS', error.response.data.errors)
        })
    },
    async SEND_MESSAGE_AS_CORPORATE({commit}, payload) {
        await this.$axios.post(CORPORATE, payload).then(({data}) => {
            if (data.success)
                commit('SET_ERRORS', [])
            commit('SET_MESSAGE', true)
        }).catch(error => {
            commit('SET_MESSAGE', false)
            commit('SET_ERRORS', error.response.data.errors)
        })
    },
    async SEND_MESSAGE_AS_CONTACT({commit}, payload) {
        await this.$axios.post(CONTACT_MESSAGE, payload).then(({data}) => {
            if (data.success)
                commit('SET_ERRORS', [])
            commit('SET_MESSAGE', true)
        }).catch(error => {
            commit('SET_MESSAGE', false)
            commit('SET_ERRORS', error.response.data.errors)
        })
    },
}

export const getters = {
    GET_ERRORS(state) {
        return state.errors;
    },
    GET_MESSAGE(state) {
        return state.message;
    }
}