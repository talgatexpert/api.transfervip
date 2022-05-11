export const state = () => ({
    transfers: [],
    message: '',
    booking_token: '',
    booking: '',
    errors: [],
    form: {},
})

export const mutations = {
    SET_TRANSFERS(state, payload) {
        state.transfers = payload;
    },
    SET_TOKEN(state, payload) {
        state.booking_token = payload
    },
    NOT_FOUND(state, payload) {
        state.message = payload
    },
    SET_BOOKING(state, payload) {
        state.booking = payload
    },
    SET_ERROR(state, payload) {
        state.errors = payload
    },
    SET_FORM(state, payload) {
        state.form = payload
    }
}
export const actions = {
    async get({commit}, payload) {
        console.log(`/transfers/${payload.city_from}/${payload.city_to}`)
        let url = `/transfers/${payload.city_from}/${payload.city_to}`;
        if (payload.currency) {
            url += '?currency=' + payload.currency
        }
        await this.$axios.$get(url).then(({data}) => {

            if (data) {
                commit('SET_TRANSFERS', data)
            }
        }).catch(e => {
            const data = e.response?.data
            if (data) {
                commit('NOT_FOUND', data.message)
            }
        })
    },
    async setBooking({commit}, payload) {
        await this.$axios.post('bookings', payload).then(({data}) => {
            if (data.success)
                commit('SET_TOKEN', data.booking_token)
        })
    },
    async saveBooking({commit}, payload) {
        await this.$axios.put('bookings/' + payload.booking_token, payload).then(({data}) => {
            commit('SET_BOOKING', data.data)
            commit('SET_ERROR', [])
        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },
    async getBooking({commit}, payload) {
        await this.$axios.get('bookings/' + payload.booking_token, payload).then(({data}) => {
            commit('SET_BOOKING', data.data)
        })
    },
    async setBookingForm({commit}, payload) {
        commit('SET_FORM', payload);
    },
}
export const getters = {
    transfers: (state) => state.transfers,
    bookingToken: (state) => state.booking_token,
    booking: async (state) => state.booking,
    errors: (state) => state.errors,
    form: (state) => state.form,
}
