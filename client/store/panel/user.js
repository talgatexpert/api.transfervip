import {ROLES_URL, UPDATE_CURRENT_USER, USERS_URL} from "~/routes/panel";

export const state = () => ({
    users: [],
    total: 0,
    errors: [],
    roles: [],
    role: '',
    hidden_roles: [],
});


export const mutations = {
    SET_USERS(state, payload) {
        state.users = payload
    },
    SET_ROLES(state, payload) {
        state.roles = payload
    },
    SET_HIDDEN_ROLES(state, payload) {
        state.hidden_roles = payload
    },
    SET_TOTAL(state, payload) {
        state.total = payload
    },
    SET_ROLE(state, payload) {
        state.role = payload
    },
    SET_ERROR(state, payload) {
        state.errors = payload
    },

}
export const actions = {
    async GET_USERS({commit}, route) {
        await this.$axios.get(USERS_URL + route).then(result => {
            commit('SET_USERS', result.data.data.users)
            commit('SET_ROLES', result.data.data.roles)
            commit('SET_TOTAL', result.data.data.total)
        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },
    async CREATE_USER({commit}, payload) {


        await this.$axios.post(USERS_URL, {
            name: payload.name,
            password: payload.password,
            email: payload.email,
            active: payload.active,
            role_id: payload.role_id
        }).then(result => {


            commit('SET_USERS', result.data.data.users)
            commit('SET_ROLES', result.data.data.roles)
            commit('SET_TOTAL', result.data.data.total)
        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },
    async CLEAR_ERROR({commit}) {
        commit('SET_ERROR', [])
    },
    async GET_USER({commit}, payload) {

    },
    async UPDATE_USER({commit}, payload) {
        await this.$axios.put(USERS_URL + payload.id, payload).then(result => {
            commit('SET_USERS', result.data.data.users)
            commit('SET_ROLES', result.data.data.roles)
            commit('SET_TOTAL', result.data.data.total)

        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },
    async SEARCH_USER({commit}, payload) {
        await this.$axios.get(USERS_URL + payload).then(result => {
            commit('SET_USERS', result.data.data.users)
            commit('SET_ROLES', result.data.data.roles)
            commit('SET_TOTAL', result.data.data.total)
        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },
    async DELETE_USER({commit}, payload) {
        await this.$axios.delete('panel/users/' + payload.id).then(result => {
            commit('SET_USERS', result.data.data.users)
            commit('SET_ROLES', result.data.data.roles)
            commit('SET_TOTAL', result.data.data.total)
        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },

    async GET_TARGET_ROLE({commit}) {
        this.$axios.get(ROLES_URL).then(result => {
            commit('SET_ROLE', result.data.data.role)
            commit('SET_HIDDEN_ROLES', result.data.data.roles)
        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },
    async UPDATE_LOGGED_USER({commit}, payload) {
        this.$axios.put(UPDATE_CURRENT_USER, payload).then(({data}) => {
            commit('SET_ERROR', '');
            this.$auth.setUser(data)
        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    }

}
export const getters = {
    GET_USERS(state) {
        return state.users
    },
    GET_ROLES(state) {
        return state.roles
    },
    GET_ROLE(state) {
        return state.role
    },
    GET_HIDDEN_ROLES(state) {
        return state.hidden_roles
    },
    GET_TOTAL(state) {
        return state.total
    },
    GET_ERRORS(state) {
        return state.errors
    }
}
