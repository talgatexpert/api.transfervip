import {SETTINGS_URL} from "~/routes/panel";

export const state = () => ({
    meta: [],
    errors: [],
    logo: '',
    name: '',
    site_info: '',

});

export const mutations = {
    SET_META(state, payload) {
        state.meta = payload
    },
    SET_DATA(state, payload) {
        state[payload.key] = payload.value
    },
    SET_ERROR(state, payload) {
        state.errors = payload
    },

}

export const prepareSettings = (payload, data, commit = null, needCommit = true) => {
    if (data.data)
        if (data.data.value)
            if (needCommit)
                if (payload.action)
                    if (data.data.value[payload.key])
                        commit(payload.action, {key: payload.key, value: data.data.value[payload.key]})
                    else
                        commit(payload.action, {key: payload.key, value: data.data.value})
                else
                    commit('SET_DATA', {key: payload.key, value: data.data.value})
            else {
                if (data.data.value[payload.key])
                    return data.data.value[payload.key]
                else
                    return data.data.value
            }

}

export const actions = {
    async SET_DATA({commit}, payload) {
        await this.$axios.put(SETTINGS_URL + 'data/' + payload.key, {
            value: payload.value,
            key: payload.key,
            serialized: 1
        })
            .then(({data}) => prepareSettings(payload, data, commit))
            .catch(error => commit('SET_ERROR', error.response.data.errors))
    },
    async GET_DATA({commit}, payload) {
        await this.$axios.get(SETTINGS_URL + payload.key)
            .then(({data}) => prepareSettings(payload, data, commit))
            .catch(error => commit('SET_ERROR', error.response.data.errors))
    },
    async UPLOAD_IMAGE({commit}, payload) {
        let formData = new FormData();
        formData.set('image', payload.image);
        formData.set('key', payload.key);
        await this.$axios.post(SETTINGS_URL + 'update/' + payload.key, formData, {
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            .then(({data}) => prepareSettings(payload, data, commit))
            .catch(error => commit('SET_ERROR', error.response.data.errors))
    },

}

export const getters = {
    GET_META(state) {
        return state.meta;
    },
    GET_SITE_INFO(state) {
        return state.site_info;
    },
    GET_NAME(state) {
        return state.name;
    },
    GET_LOGO(state) {
        return state.logo;
    },
    GET_ERRORS(state) {
        return state.errors;
    }
}
