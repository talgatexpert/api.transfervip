export const state = () => ({
    transfers: [],
    message: '',
    booking_token: '',
    booking: '',
    errors: [],
    form: {},
    transfer_data: {},
    confirmBooking: {}
})

export const mutations = {
    SET_TRANSFERS(state, payload) {
        state.transfers = payload;
    },
    SET_CONFIRM_BOOKING(state, payload) {
        state.confirmBooking = payload;
    },
    SET_TRANSFER_DATA(state, payload) {
        state.transfer_data = payload;
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
        let url = `/transfers/${payload.city_from}/${payload.city_to}`;
        if (payload.currency) {
            url += '?currency=' + payload.currency
        }
        if (payload.return_trip && payload.currency) {
            url += '&return_trip=true'
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
        console.log(payload)
        if (payload?.updateCurrency)
            await this.$axios.put('bookings/' + payload.booking_token + '/updateCurrency', payload).then(({data}) => {
                commit('SET_BOOKING', data.data)
            }).catch(error => {
                commit('SET_ERROR', error.response.data.errors)
            })

        else {
            await this.$axios.put('bookings/' + payload.booking_token + '/updateReturnTrip', payload).then(({data}) => {
                commit('SET_BOOKING', data.data)
            })
            await this.$axios.put('bookings/' + payload.booking_token, payload).then(({data}) => {
                commit('SET_BOOKING', data.data)
                commit('SET_ERROR', [])
            }).catch(error => {
                commit('SET_ERROR', error.response.data.errors)
            })
        }
    },
    async getBooking({commit}, payload) {
        await this.$axios.get('bookings/' + payload.booking_token, payload).then(({data}) => {
            commit('SET_BOOKING', data.data)
        })
    },
    async setBookingForm({commit}, payload) {
        commit('SET_FORM', payload);
    },
    setTransferData({commit}, payload) {
        commit('SET_TRANSFER_DATA', payload)
    },
   async confirmBooking({commit}, payload){
        await this.$axios.put('bookings/' + payload.booking_token + '/confirm', payload).then(({data}) => {
            commit('SET_CONFIRM_BOOKING', data.data)
        }).catch(error => {
            commit('SET_ERROR', error.response.data.errors)
        })
    },
    async getConfirmBooking({commit}, payload) {
        await this.$axios.get('bookings/confirm/' + payload.booking_token, payload).then(({data}) => {
            commit('SET_CONFIRM_BOOKING', data.data)
        })
    },
}
export const getters = {
    transfers: (state) => state.transfers,
    bookingToken: (state) => state.booking_token,
    booking: async (state) => state.booking,
    errors: (state) => state.errors,
    confirmBooking: (state) => state.confirmBooking,
    form: (state) => state.form,
    transfer_data: (state) => state.transfer_data,
}
