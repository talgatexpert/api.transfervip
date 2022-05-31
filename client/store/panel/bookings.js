import {BOOKINGS_DETAIL_URL, BOOKINGS_URL} from "~/routes/panel";

export const state = () => ({
    bookings: [],
    total: 0,
    errors: [],
    count: 0,
    booking: '',
});

export const mutations = {
    SET_BOOKINGS(state, payload) {
        state.bookings = payload
    },
    SET_BOOKING(state, payload) {
        state.booking = payload
    },
    SET_ROLES(state, payload) {
        state.roles = payload
    },
    SET_COUNT(state, payload) {
        state.count = payload
    },
    SET_TOTAL(state, payload) {
        state.total = payload
    },
    SET_ERROR(state, payload) {
        state.errors = payload
    },

}
export const actions = {
    async GET_BOOKINGS({commit}, route) {
        await this.$axios.get(BOOKINGS_URL + route).then(result => {
            commit('SET_BOOKINGS', result.data.data.bookings)
            commit('SET_TOTAL', result.data.data.total)
        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },
    async CREATE_BOOKING({commit}, payload) {
        let formData = new FormData()
        formData.append('name', payload.name)
        formData.append('new_image', payload.new_image)
        formData.append('type', payload.type)
        formData.append('model', payload.model)
        formData.append('person_quantity', payload.person_quantity)
        formData.append('baggage_quantity', payload.baggage_quantity)
        formData.append('image', payload.image)
        formData.append('active', payload.active)
        let config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        }
        await this.$axios({
            method: 'POST',
            url: 'panel/bookings/',
            data: formData,
            config: config
        }).then(result => {
            commit('SET_BOOKINGS', result.data.data.bookings)
            commit('SET_TOTAL', result.data.data.total)

        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },
    async CLEAR_ERROR({commit}) {
        commit('SET_ERROR', [])
    },
    async GET_CAR({commit}, payload) {

    },
    async ACCEPT_BOOKING({commit}, payload) {
        await this.$axios.put(BOOKINGS_URL + payload.id).then(({data}) => {
            commit('SET_BOOKINGS', data.data.bookings)
            commit('SET_TOTAL', data.data.total)
        }).catch((error) => {
            commit('SET_ERROR', error.response.data.errors)

        })
    },
    async UPDATE_BOOKING({commit}, payload) {
        let formData = new FormData()
        formData.append('name', payload.name)
        formData.append('new_image', payload.new_image)
        formData.append('type', payload.type)
        formData.append('model', payload.model)
        formData.append('person_quantity', payload.person_quantity)
        formData.append('baggage_quantity', payload.baggage_quantity)
        formData.append('company_id', payload.company_id)
        formData.append('user_id', payload.user_id)
        formData.append('image', payload.image)
        formData.append('active', payload.active)
        let config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        }
        await this.$axios({
            method: 'POST',
            url: BOOKINGS_URL + payload.id,
            data: formData,
            config: config
        }).then(result => {
            commit('SET_BOOKINGS', result.data.data.bookings)
            commit('SET_TOTAL', result.data.data.total)

        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },
    async GET_BOOKING({commit}, payload) {
        await this.$axios.get(BOOKINGS_URL + payload.id).then(({data}) => {
          commit('SET_BOOKING', data.data)
        }).catch(e => console.log(e))
    },
    async SEARCH_BOOKING({commit}, payload) {
        await this.$axios.get(BOOKINGS_URL + payload).then(result => {
            commit('SET_BOOKINGS', result.data.data.bookings)
            commit('SET_TOTAL', result.data.data.total)
        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },
    async DELETE_BOOKING({commit}, payload) {
        await this.$axios.delete(BOOKINGS_URL + payload.id).then(result => {
            commit('SET_BOOKINGS', result.data.data.bookings)
            commit('SET_TOTAL', result.data.data.total)
        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },
    async GET_NOT_ACCEPTED_BOOKINGS({commit}){
       await this.$axios.get(BOOKINGS_DETAIL_URL + 'count').then(({data}) => {
            commit('SET_COUNT', data.count)
        }).catch(e => console.log(e))
    },

}
export const getters = {
    GET_BOOKINGS(state) {
        return state.bookings
    },
    GET_BOOKING(state) {
        return state.booking
    },
    GET_TOTAL(state) {
        return state.total
    },

    GET_COUNT(state) {
        return state.count
    },
    GET_ERRORS(state) {
        return state.errors
    }
}
