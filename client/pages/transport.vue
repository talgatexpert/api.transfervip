<template>
	<section>
		<div class="main-form bg-corp">
			<div class="container">
				<h1 v-html="$t('transport.title')" class="home-title m-0"></h1>
				<p v-html="$t('transport.subtitle')" class="home-subtitle"></p>


				<button class="btn btn-green d-flex mt-5 mx-auto" v-b-modal.business-corp>Sign up</button>
			</div>
		</div>

		<div class="container">
			<div class="row content-info">
				<div class="col-lg-3 col-md-6">
					<div class="privileges-card">
						<div class="privileges-card__title">+5000</div>
						<div class="privileges-card__text">Worldwide destinations</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="privileges-card">
						<div class="privileges-card__title">More than 1000
						</div>
						<div class="privileges-card__text">Happy customers every day
						</div>
					</div>

				</div>
				<div class="col-lg-3 col-md-6">
					<div class="privileges-card">
						<div class="privileges-card__title">24/7</div>
						<div class="privileges-card__text">Customer support services</div>
					</div>

				</div>
				<div class="col-lg-3 col-md-6">
					<div class="privileges-card">
						<div class="privileges-card__title">100%</div>
						<div class="privileges-card__text">Quality of transportation services</div>
					</div>

				</div>
			</div>

			<div class="row content-info corp-clients-block">
				<div class="col-12">
					<h2 class="content-info__title text-center">Our clients</h2>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6">
					<img src="~/assets/img/client.png" alt="">
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6">
					<img src="~/assets/img/client.png" alt="">
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6">
					<img src="~/assets/img/client.png" alt="">
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6">
					<img src="~/assets/img/client.png" alt="">
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6">
					<img src="~/assets/img/client.png" alt="">
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6">
					<img src="~/assets/img/client.png" alt="">
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6">
					<img src="~/assets/img/client.png" alt="">
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6">
					<img src="~/assets/img/client.png" alt="">
				</div>
			</div>
		</div>

		<div class="container-fluid bg-dark-blue corp-clients-prefooter">
			<button class="btn btn-green" v-b-modal.business-corp>Sign Up</button>
		</div>

		<modal :classes-modal="['modal', 'modal-form']" :action="signUp" :title="$t('menu.corporate')" id="business-corp"
		       :inputs="inputs" :show-modal="true"></modal>
		<message :message="message" :type="type" v-if="show" :status="status"></message>
	</section>

</template>


<script>

import Modal from "../components/Modal";
import message from "../components/message";
import {getMetaData} from "../hooks/meta";

export default {
	name: "transport",
	components: {
		Modal, message
	},
	async asyncData({$axios, i18n}) {
		return getMetaData($axios, i18n)
	},
	head() {
		return {
			title: this.title,
			meta: this.meta
		}
	},
	data() {
		return {
			title: 'Lux elit travel Transfer Company in Turkey',
			meta: [],
			inputs: [
				{
					name: "Name",
					id: "name",
					translatedName: 'Name',
					type: "text",
					value: '',
					error: '',
				},
				{
					name: "Email",
					id: "email",
					translatedName: 'Email',
					type: "email",
					value: '',
					error: '',

				},
				{
					name: "company_name",
					id: "company_name",
					translatedName: 'Company name',
					type: "text",
					value: '',
					error: '',


				},
				{
					name: "Phone",
					id: "phone",
					translatedName: 'Phone',
					type: "text",
					value: '',
					error: '',


				},
			],
			errors: [],
			message: 'Eror',
			status: 'Eror',
			type: 'error',
			show: false,
		}
	},
	methods: {

		clear() {
			let i = 0;
			for (const argument of this.inputs) {
				this.inputs[i].error = "";
			}
		},
		async signUp(inputs, modal, event) {
			this.clear();
			const payload = {
				name: inputs.filter(i => i.id === 'name')[0]?.value,
				phone: inputs.filter(i => i.id === 'phone')[0]?.value,
				company_name: inputs.filter(i => i.id === 'company_name')[0]?.value,
				email: inputs.filter(i => i.id === 'email')[0]?.value,
			};
			await this.$store.dispatch('message/SEND_MESSAGE_AS_TRANSPORT', payload);
			const message = await this.$store.getters['message/GET_MESSAGE'];
			this.errors = await this.$store.getters['message/GET_ERRORS'];

			let i = 0;
			for (let [key, error] of Object.entries(this.errors)) {
				if (this.inputs[i].id === key) {
					this.inputs[i].error = error.join('<br>')
				}
				i++;
			}


			if (!message) {
				this.show = true
				if (this.errors) {
					this.message = 'Произошла ошибка';
					this.status = 'Error'
					this.type = 'error'
				}
			} else {
				this.show = true
				this.message = "Вы успешно зарегистрованы данные отправленны вам на электронную почту";
				this.status = "Успешно"
				this.type = 'success';
				this.$bvModal.hide('business-corp');
				this.inputs = this.inputs.map(i=> {i.value=""; return i;})
			}
			setTimeout(e => this.show = false, 6000)


		}
	},

}
</script>
