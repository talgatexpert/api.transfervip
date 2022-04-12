import Cookies from "js-cookie";
import login from "~/pages/login";

export const state = () => ({
  currencies: [
    {
      code: "TRY",
      short_name: "TL",
      symbol: "₺",
    },
    {
      code: "USD",
      short_name: "USD",
      symbol: "$",
    },
    {
      code: "EUR",
      short_name: "EUR",
      symbol: "€",
    },
  ],
  active_currency: Cookies.get('currency') ?? "TRY",
  how_work_text: "",
  company_date: "",
  socials: "",
  logo: 'logo.png',
  errors: [],
  role: '',
  hidden_roles: [],
})
const currencies = [
  {
    code: "TRY",
    short_name: "TL",
    symbol: "₺",
  },
  {
    code: "RUB",
    short_name: "Р",
    symbol: "₽",
  },
  {
    code: "USD",
    short_name: "USD",
    symbol: "$",
  },
  {
    code: "EUR",
    short_name: "EUR",
    symbol: "€",
  },
];

export const mutations = {
  SET_CURRENCY(state, payload) {
    state.active_currency = payload;
  },
  GET_WORK_TEXT(state, payload) {
    state.how_work_text = payload;
  },
  SET_ERROR(state, payload) {
    state.errors = payload
  },
  SET_ROLE(state, payload) {
    state.role = payload
  },
  GET_COMPANY_DATE(state, payload) {
    state.company_date = payload;
  },
  GET_SOCIALS(state, payload) {
    state.socials = payload;
  }
}

const loadCurrency = () => {
  return new Promise(resolve => {
    setTimeout(() => {
      resolve(currencies)
    }, 1000)
  })
}
export const actions = {

  async nuxtServerInit({commit}, {req, $gates}) {
    if (this.$auth.loggedIn) {
      await this.$axios.get('user/roles').then(result => {
        $gates.setPermissions(result.data.data.user.permissions)
        $gates.setRoles(result.data.data.user.role)
        commit('SET_ROLE', result.data.data.user.role)
      }).catch(error => {
        commit('SET_ERROR', error.response.data.errors)
      })
    }
  },

  CLEAR_ROLE({commit}){
    commit('SET_ROLE', '');
  },


  loadCurrency: async function ({commit}, payload) {
    try {
      const currencies = await loadCurrency();
      if (payload === undefined) {
        Cookies.set('currency', 'TRY');
        payload = Cookies.get('currency');
      }
      Cookies.set('currency', payload, 20);
      commit('SET_CURRENCY', payload)
    } catch (e) {
      console.log(e)
    }
  },
  howWorkText: async ({commit}, payload) => {
    const text = `<p>All you need to do is <strong>choose a trip route</strong> in the search form via our website.</p>
                <p><strong>Select the vehicle</strong> base on your preferences and capacity. And provide additional
                  requirements - the child seat, the booster, or the wheelchair accessible vehicle.</p>
                <p>The driver will be waiting for you on the arrival zone with a personal sign.</p>
                <p><strong>Free waiting time up to 60 minutes.</strong></p>`
    commit('GET_WORK_TEXT', text)
  },
  getCompanyDate: async ({commit}) => {
    commit('GET_COMPANY_DATE', 2012);
  },
  getSocials: async ({commit}) => {

    const payload = [
      {
        name: "Instagram",
        link: "https://instagram.com",
        logo: "img/icons/instagram.svg",
      },
      {
        name: "Facebook",
        link: "https://facebook.com",
        logo: "img/icons/facebook.svg",
      },
    ];
    commit('GET_SOCIALS', payload);
  }


}
export const getters = {
  getCurrencies(state) {
    return state.currencies
  },
  GET_ROLE(state) {
    return state.role
  },
  GET_HIDDEN_ROLES(state) {
    return state.hidden_roles
  },
  getCompanyDate(state) {
    return state.company_date
  },
  getHowWorkText(state) {
    return state.how_work_text
  },
  getActiveCurrency(state) {
    return state.active_currency
  },
  getSocials(state) {
    return state.socials
  },
  getLogo(state) {
    return state.logo
  }

}
