export const state = () => ({
  cities: [],
  total: 0,
  errors: [],


});

export const mutations = {
  SET_CITIES(state, payload) {
    state.cities = payload;
  },
  SET_TOTAL(state, payload) {
    state.total = payload;
  },
  SET_ERROR(state, payload) {
    state.errors = payload
  },
}


export const actions = {

  async GET_CITIES({commit}, route) {
    const url = `city${route}`;
    await this.$axios.get(url).then(result => {


      const data = result.data.data.cities.map(item => {
        return {
          id: item.id,
          ...item.translations
        }
      });
      commit('SET_CITIES', data)
      commit('SET_TOTAL', result.data.data.total)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async SEARCH_CITIES({commit}, route) {
    const url = `city${route}`;
    await this.$axios.get(url).then(result => {


      const data = result.data.data.cities.map(item => {
        return {
          id: item.id,
          ...item.translations
        }
      });
      commit('SET_CITIES', data)
      commit('SET_TOTAL', result.data.data.total)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async UPDATE_CITY({commit}, payload) {
    await this.$axios.put('city/' + payload.id, {
      cities: payload.cities,
      name: payload.name
    }).then(result => {
      const data = result.data.data.cities.map(item => {
        return {
          id: item.id,
          ...item.translations
        }
      });

      commit('SET_CITIES', data)
      commit('SET_TOTAL', result.data.data.total)

    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async DELETE_CITY({commit}, payload) {
    await this.$axios.delete('city/' + payload.id).then(result => {
      const data = result.data.data.cities.map(item => {
        return {
          id: item.id,
          ...item.translations
        }
      });
      commit('SET_CITIES', data)
      commit('SET_TOTAL', result.data.data.total)

    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async CREATE_CITY({commit}, payload) {
    await this.$axios.post('city', {
      name: payload.name,
      cities: payload.cities
    }).then(result => {
      const data = result.data.data.cities.map(item => {
        return {
          id: item.id,
          ...item.translations
        }
      });

      commit('SET_CITIES', data)
      console.log(result.data.data)
      commit('SET_TOTAL', result.data.data.total)

    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },

}

export const getters = {
  GET_CITIES(state) {
    return state.cities
  },

  GET_ERRORS(state) {
    return state.errors;
  },
  GET_TOTAL(state) {
    return state.total;
  }
}
