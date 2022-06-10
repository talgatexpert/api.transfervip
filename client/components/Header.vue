<template>
	<header class="header noprint">
		<div class="header-desc container-fluid">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="header-flex">
							<div class="header-logo">
								<nuxt-link :to="localePath('/')" exact="">
									<img :src="logo" alt=""/>
								</nuxt-link>
							</div>
							<nav>
								<ul class="header-menu">
									<li class="header-menu__item">
										<span class="header-menu__item-content">{{ $t('menu.business') }}</span>
										<ul class="header-menu__sub">
											<li>
												<nuxt-link class="hover-effect" :to="localePath('/corporate')">{{
														$t('menu.corporate')
													}}
												</nuxt-link>
											</li>
											<li>
												<nuxt-link class="hover-effect" :to="localePath('/transport')">{{
														$t('menu.transport')
													}}
												</nuxt-link>
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
										<nuxt-link class="hover-effect" :to="localePath('/contacts')"> {{ $t('menu.contacts') }}</nuxt-link>
									</li>
								</ul>
								<ul class="header-menu">
									<li class="header-menu__item" v-if="!$auth.loggedIn">
										<nuxt-link class="hover-effect" :to="localePath('/login')">{{ $t('menu.login') }}</nuxt-link>
									</li>
									<li class="header-menu__item" v-if="$auth.loggedIn">
										<nuxt-link class="hover-effect" :to="localePath('/panel')">{{ $t('menu.cabinet') }}</nuxt-link>
									</li>
									<li class="header-menu__item language">

                    <span class="header-menu__item-content"
                          v-for="(locale, index) in $i18n.locales" :key="index"
                          v-if="locale.code === $i18n.locale">{{ locale.name }}</span>

										<ul class="header-menu__sub">
											<li v-for="(locale, index) in $i18n.locales" :key="index" v-if="locale.code !== $i18n.locale">
												<a class="hover-effect" @click="changeLocale(locale.code,  $event)">
													{{ locale.name }}
												</a>

											</li>
										</ul>
									</li>


									<li class="header-menu__item currency">

                    <span class="header-menu__item-content"
                          v-for="(currency, index) in currencies" :key="index"
                          v-if="currency.code === activeCurrency">{{ currency.code }}</span>

										<ul class="header-menu__sub">
											<li v-for="(currency, index) in currencies" :key="index" v-if="currency.code !== activeCurrency">
												<span class="hover-effect"
												      @click="setCurrency(currency.code)">
													{{ currency.code }}
												</span>

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
					<img :src="logo" class="header-mobile__logo" alt=""/>

				</nuxt-link>
				<i class="i-menu"></i>
			</div>
		</div>
	</header>


</template>


<script>
import Cookies from 'js-cookie'
import {useUpdateURLParameter} from "~/hooks/url"
import {useSetting} from "../hooks/settings";

export default {
	name: "Header",
	data() {
		return { logo: require('~/assets/img/logo.png')};
	},


	computed: {
		currencies: (context) => {
			return context.$store.getters.getCurrencies
		},
		activeCurrency: (context) => {
			return context.$store.getters.getActiveCurrency
		},


	},

	async beforeCreate() {
		this.logo = await this.$store.getters.getLogo
	},

	async mounted() {

		if (this.$route.query.currency)
			await this.$store.dispatch('loadCurrency', this.$route.query.currency)
		await this.detectRedirectLocale()



	},
	methods: {
		async detectRedirectLocale() {
			const locale = this.$i18n.locale;
			let l = false;
			let str = this.trimChar(this.$route.fullPath, '/').split('/')
			for (const availableLocalesKey of this.$i18n.availableLocales) {
				l = false;
				if (availableLocalesKey === str[0]) {
					l = true;
					break;
				}
			}

			if (l === false) {
				str.unshift(locale)
			}
			let path = '/' + this.trimChar(str.join('/'), '/');
			if (str[1] === 'transfer' && path !== this.$route.fullPath) {
				await this.$router.replace(path);
			}
		},

		setCurrency(currencyCode) {
			this.$store.dispatch('loadCurrency', currencyCode)
			const url = useUpdateURLParameter(this.$route.fullPath, 'currency', currencyCode)
			this.$router.push(url)
			this.$nuxt.$emit('update-currency', currencyCode)
		},
		trimChar(string, charToRemove) {
			while (string.charAt(0) === charToRemove) {
				string = string.substring(1);
			}

			while (string.charAt(string.length - 1) === charToRemove) {
				string = string.substring(0, string.length - 1);
			}

			return string;
		},
		changeLocale(locale, event) {
			event.preventDefault();
			this.$i18n.setLocale(locale);
			console.log(this.$i18n)
			let str = this.trimChar(this.$route.fullPath, '/').split('/')
			str[0] = locale;
			let path = '/' + this.trimChar(str.join('/'), '/');
			// this.$nuxt.refresh()
			if (str[1] === 'transfer') {
				this.$router.push(path);
			}
		}
	},
	created() {
		this.$store.dispatch('loadCurrency', Cookies.get('currency'))
	},

};
</script>
