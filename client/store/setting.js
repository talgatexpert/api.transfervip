export const state = () => ({
  meta: [],
  errors: [],
  logo: '',

});

export const mutations = {
  SET_META(state, payload) {
    state.meta = payload
  },
  SET_ERROR(state, payload) {
    state.errors = payload
  },
  SET_LOGO(state, payload) {
    state.logo = payload
  },
}

export const actions = {
  async SET_META({commit}, payload) {
    await this.$axios.put('settings/meta', {
      value: payload,
      key: 'meta',
      serialized: 1
    }).then(result => {
      commit('SET_META', result.data.data.value)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async GET_META({commit}, payload) {
    await this.$axios.get('settings/meta').then(result => {
      commit('SET_META', result.data.data.value)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async UPLOAD_IMAGE({commit}, payload) {
    let formData = new FormData()
    formData.set('image', payload.image)
    formData.set('key', payload.key)
    let config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    await this.$axios({
      method: 'post',
      url: 'settings',
      data: formData,
      config: config
    }).then(response => {
      const data = response.data.data;
      commit('SET_LOGO', data.value);
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  },
  async GET_LOGO({commit}){
    await this.$axios.get('settings/logo').then(result => {
      const data = result.data.data;
      if (data && data.hasOwnProperty('value'))
        commit('SET_LOGO',   data.value)
    }).catch(error => {
      commit('SET_ERROR', error.response.data.errors)
    })
  }
}

export const getters = {
  GET_META(state) {
    return state.meta
  },
  GET_LOGO(state) {
    return state.logo
  },
  GET_ERRORS(state) {
    return state.errors;
  }
}
