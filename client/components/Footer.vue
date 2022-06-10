<template>
	<footer class="footer">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6 col-sm-12">
					<div class="footer-box">
						<div class="footer-box__title">{{ $t('menu.business') }}</div>
						<ul class="footer-box__list">
							<li class="footer-box__list-item">
								<nuxt-link class="hover-effect" :to="`/${$i18n.locale}/corporate`">{{ $t('menu.corporate') }}</nuxt-link>
							</li>
							<li class="footer-box__list-item">
								<nuxt-link class="hover-effect" :to="`/${$i18n.locale}/transport`">{{ $t('menu.transport') }}</nuxt-link>
							</li>
<!--							<li class="footer-box__list-item">-->
<!--								<nuxt-link class="hover-effect" :to="`/${$i18n.locale}/hotel`">{{ $t('menu.hotel') }}</nuxt-link>-->
<!--							</li>-->
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12">
					<div class="footer-box">
						<div class="footer-box__title">{{ $t('menu.company') }}</div>
						<ul class="footer-box__list">
							<li class="footer-box__list-item">
								<nuxt-link class="hover-effect" :to="`/${$i18n.locale}/about`"> {{ $t('menu.about') }}</nuxt-link>
							</li>
							<li class="footer-box__list-item">
								<nuxt-link class="hover-effect" :to="`/${$i18n.locale}/contacts`"> {{ $t('menu.contacts') }}</nuxt-link>
							</li>

						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12">
					<div class="footer-box">
						<div class="footer-box__title"> {{ $t('menu.information') }}</div>
						<ul class="footer-box__list">
							<li class="footer-box__list-item">
								<nuxt-link class="hover-effect" :to="`/${$i18n.locale}/information/terms-of-use`"> {{ $t('menu.terms') }}</nuxt-link>
							</li>
							<li class="footer-box__list-item">
								<nuxt-link class="hover-effect" :to="`/${$i18n.locale}/information/privacy-policy`"> {{ $t('menu.privacy') }}</nuxt-link>
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
							<li class="footer-box__list-item" v-if="activeCurrencyData">{{ activeCurrency }} <span
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
							<li class="footer-list__item footer-list__site-item" v-if="site_info">{{ site_info.date }} -
								{{ timestamp }} | {{site_info.name}}
							</li>
						</ul>

						<ul class="footer-list footer-list__social">
							<li class="footer-list__item footer-list__social-item" v-for="(social, key) in socials" :key="key">
								<a :href="social.link" :title="social.name" target="_blank"
								   :class="`i-${social.name.toLowerCase()}`"></a>
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
import moment from "moment-timezone";

export default {
	name: "Footer",
	props: {
		site_info: {
			default: null,
			type: Object
		}
	},
	data() {
		return {timestamp: null, activeCurrencyData: null}
	},

	computed: {
		currencies() {
			return this.$store.getters.getCurrencies
		},
		activeCurrency() {
			return this.$store.getters.getActiveCurrency
		},

		companyDate() {
			return this.$store.getters.getCompanyDate

		},
		socials() {
			return this.$store.getters.getSocials
		},


	},


	methods: {},

	async beforeCreate() {
		const currencies = await this.$store.getters.getCurrencies
		const active = await this.$store.getters.getActiveCurrency;
		this.timestamp = moment(Date.now()).format('YYYY');
		this.activeCurrencyData = currencies.filter(item => item.code.indexOf(active) > -1)[0];
	},
	created() {

	},
}
</script>

<style scoped>

</style>
