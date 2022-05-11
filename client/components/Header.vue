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
	data() {
		return {};
	},
	computed: {
		currencies: (context) => {
			return context.$store.getters.getCurrencies
		},
		activeCurrency: (context) => {
			return context.$store.getters.getActiveCurrency
		},
		logo(ctx) {
			return ctx.$store.getters['getLogo'];
		},

	},

	mounted() {
		if (this.$route.query.currency)
			this.$store.dispatch('loadCurrency', this.$route.query.currency)
		// this.activeCurrency = this.$route.query.currency ?? this.$store.getters.getActiveCurrency;
	},
	methods: {
		updateURLParameter(url, param, paramVal) {
			var TheAnchor = null;
			var newAdditionalURL = "";
			var tempArray = url.split("?");
			var baseURL = tempArray[0];
			var additionalURL = tempArray[1];
			var temp = "";

			if (additionalURL) {
				var tmpAnchor = additionalURL.split("#");
				var TheParams = tmpAnchor[0];
				TheAnchor = tmpAnchor[1];
				if (TheAnchor)
					additionalURL = TheParams;

				tempArray = additionalURL.split("&");

				for (var i = 0; i < tempArray.length; i++) {
					if (tempArray[i].split('=')[0] != param) {
						newAdditionalURL += temp + tempArray[i];
						temp = "&";
					}
				}
			} else {
				var tmpAnchor = baseURL.split("#");
				var TheParams = tmpAnchor[0];
				TheAnchor = tmpAnchor[1];

				if (TheParams)
					baseURL = TheParams;
			}

			if (TheAnchor)
				paramVal += "#" + TheAnchor;

			var rows_txt = temp + "" + param + "=" + paramVal;
			return baseURL + "?" + newAdditionalURL + rows_txt;
		},
		setCurrency(currencyCode) {
			this.$store.dispatch('loadCurrency', currencyCode)
			const url = this.updateURLParameter(this.$route.fullPath, 'currency', currencyCode)
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
			let str = this.trimChar(this.$route.fullPath, '/').split('/')
			if (locale === 'tr' && (str[0] === "ru" || str[0] === "en")) {
				str[0] = ''
			} else if (locale === 'ru' && (str[0] === "" || str[0] === "en")) {
				str[0] = 'ru'
			} else if (locale === 'en' && (str[0] === "" || str[0] === "ru")) {
				str[0] = 'en'
			} else {
				if (!str[1]) {
					str[1] = str[0];
					str[0] = locale
				}
				if (str[0] !== 'tr' || str[0] !== 'en' || str[0] !== 'ru') {
					str.unshift(locale)
				}
			}
			let path = '/' + this.trimChar(str.join('/'), '/');
			this.$nuxt.refresh()
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
