import Cookies from "js-cookie";
import login from "~/pages/login";
import {useSetting} from "~/hooks/settings";

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
    active_currency: 'TRY',
    how_work_text: "",
    company_date: "",
    socials: "",
    logo: 'logo.png',
    errors: [],
    role: '',
    hidden_roles: [],
    meta: [],
    site_info: {},
})

export const mutations = {
    SET_CURRENCY(state, payload) {
        state.active_currency = payload;
    },
    GET_WORK_TEXT(state, payload) {
        state.how_work_text = payload;
    },
    SET_SITE_INFO(state, payload) {
        state.site_info = payload;
    },
    SET_ERROR(state, payload) {
        state.errors = payload
    },
    SET_ROLE(state, payload) {
        state.role = payload
    },
    SET_LOGO(state, payload) {
        state.logo = payload
    },
    SET_META(state, payload) {
        state.meta = payload
    },
    GET_COMPANY_DATE(state, payload) {
        state.company_date = payload;
    },
    SET_SOCIALS(state, payload) {
        state.socials = payload;
    }
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

        const logo = await useSetting(this.$axios, 'logo')
        const info = await useSetting(this.$axios, 'site_info')

        commit('SET_SITE_INFO', info)




        commit('SET_SOCIALS', [
            {
                name: "Instagram",
                link:  info.instagram ?? "https://instagram.com",
                logo: "img/icons/instagram.svg",
            },
            {
                name: "Facebook",
                link: info.facebook ?? "https://facebook.com",
                logo: "img/icons/facebook.svg",
            },
        ])
        commit('SET_LOGO', logo)
        commit('SET_CURRENCY', this.$cookies.get('currency') ?? 'TRY')


        await this.$axios.get('/meta').then(({data}) => {
            commit('SET_META', data.data);
        }).catch(e => {
            console.log(e)
        })

    },

    CLEAR_ROLE({commit}) {
        commit('SET_ROLE', '');
    },


    async loadCurrency({commit}, payload) {
        try {
            if (payload === undefined) {
                this.$cookies.set('currency', 'TRY');
                payload = this.$cookies.get('currency');
            }
            this.$cookies.set('currency', payload);
            commit('SET_CURRENCY', payload)
        } catch (e) {
            console.log(e)
        }
    },
    async howWorkText({commit}, payload) {
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


}
export const getters = {
    getCurrencies(state) {
        return state.currencies
    },
    GET_ROLE(state) {
        return state.role
    },
    GET_SITE_INFO(state) {
        return state.site_info
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
