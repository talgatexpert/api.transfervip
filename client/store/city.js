import {CITY_URL, TRANSFER_CITIES_URL} from "~/routes/main";
export const state = () => ({
    cities: [],
    cityFrom: {},
    cityTo: {},
    startCity: {},
    endCity: {},
    errors: [],
});

export const mutations = {
    SET_CITIES: (state, payload) => {
        state.cities = payload;
    },
    CLEAR_CITIES(state, payload) {
        state.cities = payload;
    },
    CHANGE_DIRECTION: (state, payload) => {
        state.cityFrom = payload.cityTo;
        state.cityTo = payload.cityFrom;
    },
    SET_DIRECTION: (state, payload) => {
        state.cityFrom = payload.cityFrom;
        state.cityTo = payload.cityTo;
    },
    CLEAR_CITY_DATA(state) {
        state.cityFrom = {};
        state.cityTo = {};
    },
    SET_ERROR(state, payload) {
        state.errors = payload
    },
    SET_START_CITY(state, payload) {
        state.startCity = payload
    },
    SET_END_CITY(state, payload) {
        state.endCity = payload
    }


}

const setCity = (commit, payload, data) => {
    if (payload.start)
        commit('SET_START_CITY', data)
    else
        commit('SET_END_CITY', data)
}

export const actions = {
    async LOAD_CITY({commit}, payload) {
        await this.$axios.get(TRANSFER_CITIES_URL + '?search=' + payload?.search + '&orderby=' + payload.language + '&limit=5&city=' + payload.city)
            .then(({data}) => {
                if (data.data)
                    if (data.data.cities)
                        commit('SET_CITIES', data.data.cities)
            }).catch(error => {
                commit('SET_ERROR', error)
            });

    },
    SET_DIRECTION({commit}, payload) {
        commit('SET_DIRECTION', payload);
    },

    CHANGE_DIRECTION({commit}, payload) {
        commit('CHANGE_DIRECTION', payload);
    },

    CLEAR_CITY_DATA({commit}, payload) {
        commit('CLEAR_CITY_DATA');
    },
    CLEAR_CITIES({commit}, payload) {
        commit('CLEAR_CITIES', []);
    },
    async GET_CITY({commit}, payload) {
        await this.$axios.get(CITY_URL + payload.city + '?language=' + payload.language).then(({data}) => {
            if (data.data)
                setCity(commit, payload, data.data)
        }).catch(error => {
            commit('SET_ERROR', error)
        });
    }
}
export const getters = {
    getCities: state => state.cities,
    getCityFrom: state => state.cityFrom,
    getCityTo: state => state.cityTo,
    GET_ERRORS: state => state.errors,
    getStartCity: state => state.startCity,
    getEndCity: state => state.endCity
}
