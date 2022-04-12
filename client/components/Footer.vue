<template>
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="footer-box">
            <div class="footer-box__title">{{ $t('menu.business') }}</div>
            <ul class="footer-box__list">
              <li class="footer-box__list-item">
                <nuxt-link class="hover-effect" to="/corporate">{{ $t('menu.corporate') }}</nuxt-link>
              </li>
              <li class="footer-box__list-item">
                <nuxt-link class="hover-effect" to="/transport">{{ $t('menu.transport') }}</nuxt-link>
              </li>
              <li class="footer-box__list-item">
                <nuxt-link class="hover-effect" to="/hotel">{{ $t('menu.hotel') }}</nuxt-link>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="footer-box">
            <div class="footer-box__title">{{ $t('menu.company') }}</div>
            <ul class="footer-box__list">
              <li class="footer-box__list-item">
                <nuxt-link class="hover-effect" to="/about"> {{ $t('menu.about') }}</nuxt-link>
              </li>
              <li class="footer-box__list-item">
                <nuxt-link class="hover-effect" to="/contacts"> {{ $t('menu.contacts') }}</nuxt-link>
              </li>

            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="footer-box">
            <div class="footer-box__title"> {{ $t('menu.information') }}</div>
            <ul class="footer-box__list">
              <li class="footer-box__list-item">
                <nuxt-link class="hover-effect" to="/information/terms-of-use"> {{ $t('menu.terms') }}</nuxt-link>
              </li>
              <li class="footer-box__list-item">
                <nuxt-link class="hover-effect" to="/information/privacy-policy"> {{ $t('menu.privacy') }}</nuxt-link>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="footer-box text-right">
            <ul class="footer-box__list footer-box__list--end">
              <li class="footer-box__list-item" v-for="(locale, index) in $i18n.locales" :key="index"
                  v-if="locale.code === $i18n.locale">
                <img :src="require(`~/assets/img/${locale.icon}`)" alt="">
                <span class="footer-box__list-item--name">{{ locale.name }}</span>
              </li>
              <li class="footer-box__list-item">{{ activeCurrency }} <span
                class="footer-box__list-item--name"> {{ activeCurrencyData.symbol }}</span></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="footer-end">
            <ul class="footer-list footer-list__payment">
              <li class="footer-list__item"><i class="i-visa"></i></li>
              <li class="footer-list__item"><i class="i-mastercard"></i></li>
            </ul>
            <ul class="footer-list footer-list__site">
              <li class="footer-list__item footer-list__site-item">{{ $t('menu.all_right') }}</li>
              <li class="footer-list__item footer-list__site-item">{{ companyDate }} - {{ timestamp }} |  VIP TRANSFER </li>
            </ul>

            <ul class="footer-list footer-list__social">
              <li class="footer-list__item footer-list__social-item" v-for="(social, key) in socials" :key="key">
                <a :href="social.link" :title="social.name" target="_blank" :class="`i-${social.name.toLowerCase()}`"></a>
              </li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </footer>
</template>

<script>
import Cookies from "js-cookie";

export default {
  name: "Footer",
  data() {
    return {timestamp: ""}
  },

  computed: {
    currencies: (context) => {
      return context.$store.getters.getCurrencies
    },
    activeCurrency: (context) => {
      return context.$store.getters.getActiveCurrency
    },

    companyDate: (context) => {
      return context.$store.getters.getCompanyDate

    },
    socials: (context) => {
      return context.$store.getters.getSocials

    },

    activeCurrencyData: (context) => {
      const currencies = context.$store.getters.getCurrencies
      const active = context.$store.getters.getActiveCurrency;
      return currencies.filter(item => item.code.indexOf(active) > -1)[0];

    }

  },


  methods: {
    setCurrency(event, s) {

      const currency = event.target.dataset.currency;
      this.$store.dispatch('loadCurrency', currency)
    },
    getNow: function () {
      const today = new Date();
      this.timestamp = today.getFullYear();
    }
  },

  created() {
    this.getNow();
    this.$store.dispatch('loadCurrency', Cookies.get('currency'))
    this.$store.dispatch('getCompanyDate')
    this.$store.dispatch('getSocials')

  },
}
</script>

<style scoped>

</style>
