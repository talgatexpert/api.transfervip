export const state = () => ({
  companies: [],
  total: 0,
  errors: [],
  users: [],
});

export const mutations = {
  SET_COMPANY(state, payload) {
    state.companies = payload
  },
  SET_USERS(state, payload) {
    state.users = payload
  },
  SET_TOTAL(state, payload) {
    state.total = payload
  },
  SET_ERROR(state, payload) {
    state.errors = payload
  },

}
export const actions = {
  async GET_COMPANY({commit}, route) {
    const url = `company${route}`;
    await this.$axios.get(url).then(result => {
      commit('SET_COMPANY', result.data.data.companies)
      commit('SET_TOTAL', result.data.data.total)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async CREATE_COMPANY({commit}, payload) {

    await this.$axios.post('company', {
      name: payload.name,
      owner: payload.owner,
      email: payload.email,
      tax: parseInt(payload.tax.replace('/[^0-9]/g', '')),
      phone: payload.phone,
      active: payload.active,
    }).then(result => {
      commit('SET_COMPANY', result.data.data.companies)
      commit('SET_TOTAL', result.data.data.total)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async CLEAR_ERROR({commit}) {
    commit('SET_ERROR', [])
  },
  async GET_USERS({commit}, payload) {
    await this.$axios.post('users/inactive', {
      email: payload.email
    }).then(result => {
      commit('SET_USERS', result.data.data.users)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async UPDATE_COMPANY({commit}, payload) {
    await this.$axios.put('company/' + payload.id, {
      name: payload.name,
      owner: payload.owner,
      email: payload.email,
      tax: parseInt(payload.tax.replace('/[^0-9]/g', '')),
      phone: payload.phone,
      active: payload.active,
    }).then(result => {
      commit('SET_COMPANY', result.data.data.companies)
      commit('SET_TOTAL', result.data.data.total)

    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async SEARCH_COMPANY({commit}, payload) {
    const url = `company${payload}`;
    await this.$axios.get(url).then(result => {
      commit('SET_COMPANY', result.data.data.companies)
      commit('SET_TOTAL', result.data.data.total)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async DELETE_COMPANY({commit}, payload) {
    await this.$axios.delete('company/' + payload.id).then(result => {
      commit('SET_COMPANY', result.data.data.companies)
      commit('SET_TOTAL', result.data.data.total)

    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },

}
export const getters = {
  GET_COMPANY(state) {
    return state.companies
  },
  GET_USERS(state) {
    return state.users;
  },
  GET_TOTAL(state) {
    return state.total
  },
  GET_ERRORS(state) {
    return state.errors
  }
}
