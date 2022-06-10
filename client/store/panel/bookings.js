import {BOOKINGS_DETAIL_URL, BOOKINGS_TRANSFERS_URL, BOOKINGS_URL} from "~/routes/panel";

export const state = () => ({
    bookings: [],
    total: 0,
    errors: [],
    count: 0,
    booking: '',
    transfers: [],
    next_data: false,
    transfer_data: [],
    success: false,
});

export const mutations = {
    SET_BOOKINGS(state, payload) {
        state.bookings = payload
    },
    SET_SUCCESS(state, payload) {
        state.success = payload
    },
    SET_NEXT_DATA(state, payload) {
        state.next_data = payload
    },
    SET_TRANSFERS(state, payload) {
        state.transfers = payload
    },
    SET_TRANSFER_DATA(state, payload) {
        state.transfer_data = payload
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

        await this.$axios.post(BOOKINGS_URL, payload).then(({data}) => {
            commit('SET_SUCCESS', true)
            commit('SET_ERROR', []);

        }).catch(error => {
            commit('SET_SUCCESS', false)
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
    async GET_TRANSFERS({commit}, payload) {
        let url = BOOKINGS_TRANSFERS_URL + '?page=' + payload.page + '&city_from=' + payload.city_from + '&city_end=' + payload.city_end;
        await this.$axios.get(url, payload).then(async ({data}) => {
            commit('SET_TRANSFERS', data.data.items)
            if (data.data.last_page_url) {
                await this.$axios.get(data.data.last_page_url + '&city_from=' + payload.city_from + '&city_end=' + payload.city_end).then(({data}) => {
                    if (data.data.items.length > 0)
                        commit('SET_NEXT_DATA', true)
                    else
                        commit('SET_NEXT_DATA', false)
                }).catch(e => console.log(e))
            }
            commit('SET_TRANSFER_DATA', data.data)
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
    async GET_NOT_ACCEPTED_BOOKINGS({commit}) {
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
    GET_SUCCESS(state) {
        return state.success
    },
    GET_TOTAL(state) {
        return state.total
    },

    GET_COUNT(state) {
        return state.count
    },
    GET_TRANSFERS(state) {
        return state.transfers
    },
    GET_NEXT_DATA(state) {
        return state.next_data
    },
    GET_TRANSFER_DATA(state) {
        return state.transfer_data
    },
    GET_ERRORS(state) {
        return state.errors
    }
}
