import Cookies from "js-cookie";


export const state = () => ({
  cities: [],
  cityFrom: {},
  cityTo: {},
  errors: [],
});

export const mutations = {
  SET_CITIES: (state, payload) => {
    state.cities = payload;
  },
  CHANGE_DIRECTION: (state, payload) => {
    state.cityFrom = payload.cityTo;
    state.cityTo = payload.cityFrom;
  },
  SET_DIRECTION: (state, payload) => {
    state.cityFrom = payload.cityFrom;
    state.cityTo = payload.cityTo;
  },
  CLEAR_CITY_DATA(state) {
    state.cityFrom = {};
    state.cityTo = {};
  },
  SET_ERROR(state, payload) {
    state.errors = payload
  },

}

/* Encode string to slug */
function convertToSlug(str) {

  //replace all special characters | symbols with a space
  str = str.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ')
    .toLowerCase();

  // trim spaces at start and end of string
  str = str.replace(/^\s+|\s+$/gm, '');

  // replace space with dash/hyphen
  str = str.replace(/\s+/g, '-');
  return str;
  //return str;
}


export const actions = {
  async LOAD_CITY({commit}, payload) {
    switch (this.$i18n.locale) {
      case "tr":
        await this.$axios.get('transfer/cities?search=' + payload.search + '&orderby=turkish&limit=5&city=' + payload.city).then(result => {
          commit('SET_CITIES', result.data.data.cities)
        }).catch(error => {
          commit('SET_ERROR', error)
        });
        break
      case "ru":
        await this.$axios.get('transfer/cities?search=' + payload.search  + '&orderby=russian&limit=10&city=' + payload.city).then(result => {
          commit('SET_CITIES', result.data.data.cities)
        }).catch(error => {
          commit('SET_ERROR', error)
        });
        break
      case "en":
        await this.$axios.get('transfer/cities?search=' + payload.search  + '&orderby=english&limit=10&city=' + payload.city).then(result => {
          commit('SET_CITIES', result.data.data.cities)
        }).catch(error => {
          commit('SET_ERROR', error)
        });
        break
    }
  },
  SET_DIRECTION({commit}, payload) {
    commit('SET_DIRECTION', payload);
  },

  CHANGE_DIRECTION({commit}, payload) {
    commit('CHANGE_DIRECTION', payload);
  },
  // GET_CITY_BY_SLUG({commit}, payload) {
  //   let result = {}
  //   if (payload.from) {
  //     result['cityFrom'] = {
  //       name: list.filter(item => item.name === payload.from)[0].name,
  //       slug: list.filter(item => item.name === payload.from)[0].name,
  //     };
  //   }
  //   if (payload.to) {
  //     result['cityTo'] = {
  //       name: list.filter(item => item.name === payload.to)[0].name,
  //       slug: list.filter(item => item.name === payload.to)[0].name,
  //     };
  //   }
  //   commit('SET_DIRECTION', result);
  // },
  CLEAR_CITY_DATA({commit}, payload) {
    commit('CLEAR_CITY_DATA');
  },
}
export const getters = {
  getCities: state => state.cities,
  getCityFrom: state => state.cityFrom,
  getCityTo: state => state.cityTo,
  GET_ERRORS: state => state.errors,
}
