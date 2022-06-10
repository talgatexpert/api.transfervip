import {SETTINGS_URL} from "~/routes/main";
import {prepareSettings} from "~/store/setting";

export const state = () => ({
    errors: [],
    site_info: '',

});

export const mutations = {

    SET_DATA(state, payload) {
        state[payload.key] = payload.value
    },
    SET_ERROR(state, payload) {
        state.errors = payload
    },

}


export const actions = {
    async GET_DATA({commit}, payload) {
        await this.$axios.get(SETTINGS_URL + payload.key)
            .then(({data}) => prepareSettings(payload, data, commit))
            .catch(error => {
                if (error.response)
                    commit('SET_ERROR', error.response.data.errors)
            })
    },

}

export const getters = {

    GET_SITE_INFO(state) {
        return state.site_info
    },

    GET_ERRORS(state) {
        return state.errors;
    }
}
