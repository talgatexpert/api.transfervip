<template>
  <header class="header noprint">
    <div class="header-desc container-fluid">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="header-flex">
              <div class="header-logo">
                <nuxt-link :to="localePath('/')" exact="">
                  <img :src="require(`~/assets/img/${logo}`)" alt=""/>
                </nuxt-link>
              </div>
              <nav>
                <ul class="header-menu">
                  <li class="header-menu__item">
                    <span class="header-menu__item-content">{{ $t('menu.business') }}</span>
                    <ul class="header-menu__sub">
                      <li>
                        <nuxt-link class="hover-effect" :to="localePath('/corporate')">{{ $t('menu.corporate') }}</nuxt-link>
                      </li>
                      <li>
                        <nuxt-link class="hover-effect" :to="localePath('/transport')">{{ $t('menu.transport') }}</nuxt-link>
                      </li>

                    </ul>
                  </li>
                  <li class="header-menu__item">
                    <nuxt-link class="hover-effect" :to="localePath('/about')"> {{ $t('menu.about') }}</nuxt-link>
                  </li>
<!--                  <li class="header-menu__item">-->
<!--                    <nuxt-link class="hover-effect"  :to="localePath('/delivery')"> {{ $t('menu.delivery') }}</nuxt-link>-->
<!--                  </li>-->
                  <li class="header-menu__item">
                    <nuxt-link class="hover-effect"  :to="localePath('/contacts')"> {{ $t('menu.contacts') }}</nuxt-link>
                  </li>
                </ul>
                <ul class="header-menu">
                  <li class="header-menu__item" v-if="!this.$auth.loggedIn">
                    <nuxt-link class="hover-effect"  :to="localePath('/login')">{{ $t('menu.login') }}</nuxt-link>
                  </li>
                  <li class="header-menu__item" v-if="this.$auth.loggedIn">
                    <nuxt-link class="hover-effect"  :to="localePath('/panel')">{{ $t('menu.cabinet') }}</nuxt-link>
                  </li>
                  <li class="header-menu__item language">

                    <span class="header-menu__item-content"
                          v-for="(locale, index) in $i18n.locales" :key="index"
                          v-if="locale.code === $i18n.locale">{{ locale.name }}</span>

                    <ul class="header-menu__sub">
                      <li   v-for="(locale, index) in $i18n.locales" :key="index"  v-if="locale.code !== $i18n.locale">
                        <nuxt-link class="hover-effect" :to="switchLocalePath(locale.code)">
                          {{ locale.name }}
                        </nuxt-link>

                      </li>
                    </ul>
                  </li>


                  <li class="header-menu__item currency">

                    <span class="header-menu__item-content"
                          v-for="(currency, index) in currencies" :key="index"
                          v-if="currency.code === activeCurrency">{{ currency.code }}</span>

                    <ul class="header-menu__sub">
                      <li v-for="(currency, index) in currencies" :key="index" v-if="currency.code !== activeCurrency">
                        <a class="hover-effect"  :href="currency.code"  :data-currency="currency.code" @click.prevent="setCurrency">
                          {{ currency.code }}
                        </a>

                      </li>
                    </ul>
                  </li>

                </ul>
              </nav>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="header-mobile">
      <div class="header-mobile__menu">
        <nuxt-link to="/" exact="">
          <img :src="require(`~/assets/img/${logo}`)" class="header-mobile__logo" alt=""/>

        </nuxt-link>
        <i class="i-menu"></i>
      </div>
    </div>
  </header>


</template>


<script>
import Cookies from 'js-cookie'

export default {
  name: "Header",
  computed: {
    currencies: (context) => {
      return context.$store.getters.getCurrencies
    },
    activeCurrency: (context) => {
      return context.$store.getters.getActiveCurrency
    },
    logo(ctx){
      return ctx.$store.getters['getLogo'];
    },

  },
  methods: {
    setCurrency(event) {

      const currency = event.target.dataset.currency;
      this.$store.dispatch('loadCurrency', currency)
    },
    changeLocale(locale){
      this.$i18n.setLocale(locale);
    }
  },
  created() {
    this.$store.dispatch('loadCurrency', Cookies.get('currency'))
  },

};
</script>
