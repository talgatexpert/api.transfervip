import {CARS_URL, TRANSFERS_URL} from "~/routes/panel";

export const state = () => ({
  transfers: [],
  total: 0,
  errors: [],
  cars: [],
  transfer: {},
});

export const mutations = {
  SET_TRANSFERS(state, payload) {
    state.transfers = payload
  },
  SET_TRANSFER(state, payload) {
    state.transfer = payload
  },
  SET_TOTAL(state, payload) {
    state.total = payload
  },
  SET_CARS(state, payload) {
    state.cars = payload
  },
  SET_ERROR(state, payload) {
    state.errors = payload
  },
}
export const actions = {
  async GET_TRANSFERS({commit}, payload) {
    await this.$axios.get(TRANSFERS_URL + payload).then(result => {
      commit('SET_TRANSFERS', result.data.data.transfers)
      commit('SET_TOTAL', result.data.data.total)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async GET_ONE({commit}, payload) {
    await this.$axios.get(TRANSFERS_URL + payload.id).then(result => {
      commit('SET_TRANSFER', result.data.data.transfer)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },

  async GET_CARS({commit}, payload){
    await this.$axios.get(CARS_URL + '?search=' + payload.name).then(result => {
      commit('SET_CARS', result.data.data);
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },

  async SAVE_TRANSFER({commit}, payload){

    if (!payload.id){
        await this.$axios.post(TRANSFERS_URL, payload).then(result => {
          commit('SET_CARS', result.data.data);
          commit('SET_TOTAL', result.data.data.total)
        }).catch(error => {
          commit('SET_ERROR', error.response.data.errors)
        })
    }else{
      await this.$axios.put(TRANSFERS_URL + payload.id , payload).then(result => {
        commit('SET_CARS', result.data.data);
        commit('SET_TOTAL', result.data.data.total)
      }).catch(error => {
        commit('SET_ERROR', error.response.data.errors)
      })
    }

  },
  async CLEAR_ERROR({commit}) {
    commit('SET_ERROR', [])
  },
  async SEARCH_TRANSFERS({commit}, payload){
    await this.$axios.get(TRANSFERS_URL + '?search=' + payload).then(result => {
      commit('SET_TRANSFERS', result.data.data.transfers);
      commit('SET_TOTAL')
    }).catch(error => {

      console.log(error)
      commit('SET_ERROR', error.response.data.errors)
    })
  }
}

export const getters = {
  GET_TRANSFERS(state) {
    return state.transfers;
  },
  GET_ONE(state) {
    return state.transfer;
  },
  GET_TOTAL(state) {
    return state.total;
  },
  GET_ERRORS(state) {
    return state.errors;
  }
}
