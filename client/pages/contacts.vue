<template>
	<v-app>
		<div class="main-layout">
			<div class="maps">
				<iframe
						:src="maps"
						width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
						referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
			<div class="container mb-5">
				<div class="row">
					<div class="col-12">
						<h3 v-html="$t('contacts.subtitle')" class="home-subtitle mt-4"></h3>
					</div>
					<div class="col-12">
						<div class="contacts-info">
							<div v-for="(item, key) in information" class="row" :key="key">
								<div class="col-4">
									<div class="contacts-info__label">{{ item.name }}:</div>
								</div>
								<div class="col-6">
									<div class="contacts-info__value">{{ item.value }}</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<v-form class="row" @submit.prevent="onSubmit" ref="contact_form">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<v-text-field
								v-model="form.name"
								label="Name and surname"
								:error-messages="errors.name"
						></v-text-field>

						<v-text-field
								v-model="form.email"
								:rules="emailRules"
								label="Email address"
								:error-messages="errors.email"

						></v-text-field>
						<v-text-field
								v-model="form.text"
								label="Message"
								:error-messages="errors.text"

						></v-text-field>

						<recaptcha v-if="recaptcha" class="mt-3 mb-3"/>
						<p class="text-danger" v-if="captcha_error">Failed to execute recaptcha</p>
						<v-btn
								:disabled="disabled"
								depressed
								type="submit"
								class="btn-green btn-send"
						>
							Submit
						</v-btn>
					</div>
				</v-form>

			</div>
			<message :message="message" :type="type" v-if="show" :status="status"></message>

		</div>
	</v-app>
</template>

<script>
import login from "./login";
import {getMetaData} from "../hooks/meta";
import message from "../components/message";

export default {
	name: "contacts",
	async asyncData({app, store, $axios, i18n}) {
		await store.dispatch('main/GET_DATA', {key: 'site_info', action: 'SET_DATA'});
		const info = await store.getters['main/GET_SITE_INFO'];
		let information = [];
		let maps = null;
		if (info) {
			information = [
				{
					name: app.i18n.t('contacts.company_name'),
					value: info.name
				},
				{
					name: app.i18n.t('contacts.address'),
					value: info.address
				},
				{
					name: app.i18n.t('contacts.city'),
					value: info?.city
				},
				{
					name: app.i18n.t('contacts.country'),
					value: info.country
				},
				{
					name: app.i18n.t('contacts.email'),
					value: info.email
				},
			];
			maps = info.maps
		}
		const meta = await getMetaData($axios, i18n, i18n.t('menu.contacts'));

		return {information: information, ...meta, maps}
	},
	head() {
		return {
			title: this.title,
			meta: this.meta
		}
	},
	components: {message},

	data() {
		return {
			title: 'Lux elit travel Transfer Company in Turkey',
			meta: [],
			disabled: false,
			information: [],
			maps: 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12770.305088710937!2d30.6189308!3d36.8526229!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x7c1e1929663c7e7a!2zVmlsbGEgRG9tIEVtbGFrIFJlYWwgRXN0YXRlINCd0LXQtNCy0LjQttC40LzQvtGB0YLRjCBMVEQu!5e0!3m2!1sru!2str!4v1649252403262!5m2!1sru!2str',
			valid: false,
			email: '',
			message: '',
			show: false,
			type: '',
			status: '',
			form: {
				name: null,
				text: null,
				email: null,
			},
			emailRules: [
				v => !!v || 'E-mail is required',
				v => /.+@.+/.test(v) || 'E-mail must be valid',
			],
			errors: [],
			recaptcha: true,
			captcha_error: false,
		}
	},

	async mounted() {
		try {

			if (localStorage.getItem('_grecaptcha')) {
				this.$recaptcha.destroy();
				this.recaptcha = false;
			} else {
			}
		} catch (e) {
			console.error(e);
		}
	},
	methods: {

		async captcha() {
			if (!localStorage.getItem('_grecaptcha'))
				return await this.$recaptcha.getResponse().then(r => this.captcha_error = false)
						.catch(error => this.captcha_error = true);
		},


		async onSubmit() {
			await this.captcha()

			if (!this.captcha_error && this.$refs.contact_form.validate()) {
				this.disabled = true;
				await this.$store.dispatch('message/SEND_MESSAGE_AS_CONTACT', this.form)
				const success = await this.$store.getters['message/GET_MESSAGE'];
				this.errors = await this.$store.getters['message/GET_ERRORS'];
				this.disabled = false;


				if (success) {
					this.show = true
					this.message = "Ваше сообщение отправленно администрации. В скором времени с вами свяжутся";
					this.status = "Успешно"
					this.type = 'success';
				} else if (!success) {
					this.show = true
					if (this.errors) {
						this.message = 'Произошла ошибка. Попробуйте еще раз';
						this.status = 'Error'
						this.type = 'error'
					}
				}

				setTimeout(e => this.show = false, 6000)

			}

		},
	}
}
</script>

