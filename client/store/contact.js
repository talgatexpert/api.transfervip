export const state = () => ({
  errors: [],
  success: '',

})
export const mutators = {
  SET_ERROR(state, payload){
    state.errors = payload
  },
  SET_SUCCESS(state, payload){
    state.success = payload;
  },
};
export const actions = {
  async SEND_MESSAGE({commit},payload){
      await this.$axios.post('contact/email', payload).then(result => {
          commit('SET_SUCCESS', result.data.message)
      }).catch(error => {
        commit('SET_ERROR', error.response.data.errors)

      });
  }
}
