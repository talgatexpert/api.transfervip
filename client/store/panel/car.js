

export const state = () => ({
  cars: [],
  total: 0,
  errors: [],
});

export const mutations = {
  SET_CARS(state, payload) {
    state.cars = payload
  },
  SET_ROLES(state, payload) {
    state.roles = payload
  },
  SET_TOTAL(state, payload) {
    state.total = payload
  },
  SET_ERROR(state, payload) {
    state.errors = payload
  },

}
export const actions = {
  async GET_CARS({commit}, route) {
    const url = `cars${route}`;
    await this.$axios.get(url).then(result => {
      commit('SET_CARS', result.data.data.cars)
      commit('SET_TOTAL', result.data.data.total)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async CREATE_CAR({commit}, payload) {
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
      url: 'cars/',
      data: formData,
      config: config
    }).then(result => {
      commit('SET_CARS', result.data.data.cars)
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
  async UPDATE_CAR({commit}, payload) {
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
      url: 'cars/' + payload.id,
      data: formData,
      config: config
    }).then(result => {
      commit('SET_CARS', result.data.data.cars)
      commit('SET_TOTAL', result.data.data.total)

    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async SEARCH_CAR({commit}, payload) {
    const url = `cars${payload}`;
    await this.$axios.get(url).then(result => {
      commit('SET_CARS', result.data.data.cars)
      commit('SET_TOTAL', result.data.data.total)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async DELETE_CAR({commit}, payload) {
      await this.$axios.delete('cars/' + payload.id).then(result => {
        commit('SET_CARS', result.data.data.cars)
          commit('SET_TOTAL', result.data.data.total)
      }).catch(error => {
        commit('SET_ERROR', error.response.data.errors)
      })
  },

}
export const getters = {
  GET_CARS(state) {
    return state.cars
  },
  GET_TOTAL(state) {
    return state.total
  },
  GET_ERRORS(state) {
    return state.errors
  }
}
