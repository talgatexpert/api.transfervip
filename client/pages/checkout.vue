<template>
	<section class="checkout">
		<div class="container">
			<div class="content-info ">
				<div class="row">
					<div class="col-lg-12 col-md-12">

						<div class="progress">
							<div class="progress__content">
								<div v-bind:class="[step_1 ? 'progress-step _active' : 'progress-step']">
									<div class="top-line">
										<span class="progress-step__step">1.</span>
										<span class="progress-step__label">{{ $t('checkout.progressBar.step_1') }}</span>
									</div>
									<div class="bottom-line"><span class="progress-circle">

          </span>
										<div class="progress-divider-holder">
											<div class="progress-divider"></div>
											<div
													v-bind:class="[step_1 ? 'progress-divider-active _completed' : 'progress-divider-active']"></div>
										</div>
									</div>
								</div>
								<div v-bind:class="[step_2 ? 'progress-step _active step-2' : 'progress-step  step-2']">
									<div class="top-line"><span class="progress-step__step">
      2.
    </span><span class="progress-step__label">
     {{ $t('checkout.progressBar.step_2') }}
    </span></div>
									<div class="bottom-line"><span class="progress-circle"></span>
										<div class="progress-divider-holder">
											<div v-bind:class="[step_2 ? 'progress-divider-active _completed' : 'progress-divider']"></div>
											<!----></div>
									</div>
								</div>
								<div v-bind:class="[step_3 ? 'progress-step _active step-3' : 'progress-step step-3']">
									<div class="top-line"><span class="progress-step__step">
      3.
    </span><span class="progress-step__label">
      {{ $t('checkout.progressBar.step_3') }}
    </span></div>
									<div class="bottom-line"><span class="progress-circle"></span>
										<div class="progress-divider-holder">
											<div class="progress-divider"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12" v-if="loading_confirm">
						<scale-loader :loading="loading_confirm"></scale-loader>
					</div>
					<div v-if="!loading_confirm" class="col-lg-8 col-md-12">
						<transfer-details :edit="edit"
						                  :change="changeDetail"
						                  :errors="errors"
						                  :step="target_step"
						                  @update-details="updateDetails"
						                  @details-error="getErrors"
						                  :changed="changed"></transfer-details>
						<payment @select-payment="selectPayment" :step="target_step" v-if="show_payment"></payment>

						<button class="btn btn-green w-100 mt-3" :class="errors ? 'btn-secondary' : '' " :disabled="errors === true"
						        @click="continue_payment">Continue
						</button>
					</div>
					<div v-if="!loading_confirm" class="col-lg-4 col-md-12">
						<div class="form-total-end">
							<div class="form form-container">
								<div class="form-result-title__box">

									<i class="i-car"></i>
									<div class="box__content">
										<div class="box__content-title">{{ transferDetail.car.full_name }} {{ transferDetail.car.type }}
										</div>
										<div class="box__content-subtitle">up to {{ form.passengers_number }} passengers,
											{{ transferDetail.car.baggage_quantity }} pieces of
											baggage
										</div>
									</div>
								</div>
								<div class="padding-form ">

									<return-amount :city-from="form.city_from" :price="form.price_with_currency"
									               :city-end="form.city_end"></return-amount>
									<return-amount v-if="form.return_trip" :city-from="form.city_end"
									               :price="form.return_price_with_currency" :city-end="form.city_from"
									               class="mt-3"></return-amount>


								</div>
							</div>
							<div class="form form-container form-amount">
								<div class="padding-form">
									<div class="box__amount">
										<div class="total-text">Total:</div>
										<div class="total">{{ form.total_with_currency }}</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>

<script>


import Vue from "vue";
import 'vue-datepicker-ui/lib/vuedatepickerui.css';
import 'vue2-timepicker/dist/VueTimepicker.css'
import Route from "../components/Route";
import ReturnAmount from "../components/ReturnAmount";
import TransferDetails from "../components/TransferDetails";
import Payment from "../components/Payment";
import VueMoment from 'vue-moment'
import moment from 'moment-timezone'
import login from "./login";
import ScaleLoader from "vue-spinner/src/ScaleLoader.vue";

if (!Vue.moment) {
	Vue.use(VueMoment, {
		moment,
	})
}

export default Vue.extend({

	name: "checkout",
	components: {Payment, TransferDetails, ReturnAmount, ScaleLoader},
	data() {
		return {
			edit: true,
			changed: false,
			return_amount: '',
			loading_confirm: false,
			component: '',
			show_payment: false,
			paymentMethod: '',
			step_1: true,
			step_2: false,
			step_3: false,
			finish: false,
			target_step: 'step_1',
			errors: false,
			form: {
				passengers_number: 1,
			},
			transferDetail: null,
			timer: undefined

		}
	},
	async asyncData({app, query}) {
		await app.store.dispatch('transfer/getBooking', {booking_token: query.booking_token,});
		const data = await app.store.getters['transfer/booking'];
		const getCityTranslation = (data) => {
			let city_data = {
				city_from: '',
				city_end: ''
			};
			if (app.i18n.locale === 'tr') {
				city_data.city_from = data.city_from.turkish
				city_data.city_end = data.city_end.turkish
			} else if (app.i18n.locale === 'ru') {
				city_data.city_from = data.city_from.russian
				city_data.city_end = data.city_end.russian
			} else if (app.i18n.locale === 'en') {
				city_data.city_from = data.city_from.english
				city_data.city_end = data.city_end.english
			}
			return city_data
		};
		let form = JSON.parse(JSON.stringify(data));
		form = Object.assign(form, getCityTranslation(data))

		return {form, transferDetail: data, target_step: data.step ?? 'step_1'}
	},
	async mounted() {
		await this.getFormInfo();
		this.showStep()

	},
	watch: {
		async target_step(val) {
			await this.$store.dispatch('transfer/saveBooking', {...this.form, step: val})
		},
	},
	methods: {

		showStep() {
			switch (this.form.step) {
				case 'step_2':
					this.edit = false;
					this.changed = true;
					this.show_payment = true;
					break
				case 'step_3':
					this.edit = false;
					this.changed = true;
					this.show_payment = true;
					break
			}
		},
		async getFormInfo() {
			const data = await this.$store.getters['transfer/booking'];
			const city_data = this.getCityTranslation(data)
			this.form = JSON.parse(JSON.stringify(this.form));
			this.form = Object.assign(this.form, data)
			this.form = Object.assign(this.form, city_data)
			if (this.returnTrip) {
				this.form = {
					...this.form, city_from: this.form.city_end, city_end: this.form.city_from,
					address: this.form.return_booking.address,
					flight_number: this.form.return_booking.flight_number,
					started_at: this.form.return_booking.started_at,
					started_at_time: this.form.return_booking.started_at_time,
				}
			}
		},
		getErrors(errors) {
			let count = Object.keys(errors).length;
			this.errors = count !== 0;

		},

		async continue_payment() {
			if (this.errors) {
				return;
			}
			console.log(this.errors)


			this.edit = false;
			this.changed = true;
			this.show_payment = true;
			window.scrollTo(0, 0);

			this.step_2 = true

			this.target_step = 'step_2'

			if (this.finish) {
				this.loading_confirm = true;

				await this.$store.dispatch('transfer/confirmBooking', {...this.form, payment_method: this.paymentMethod})
				this.loading_confirm = false
				await this.$router.push({
					path: this.$i18n.locale === 'tr' ? '/success' : '/' + this.$i18n.locale + '/success',
					query: {
						booking_token: this.$route.query.booking_token,
						car_id: this.$route.query.car_id,
						transfer_id: this.$route.query.transfer_id,
					}
				});
			}
		},
		selectPayment(payload) {
			this.target_step = 'step_3'
			this.step_3 = true;
			this.finish = true;
			if (payload.method) {
				this.paymentMethod = payload.method
			}
		},
		changeDetail() {
			this.edit = true;
			this.changed = false;
			this.show_payment = false;
			this.finish = false;
			this.step_2 = false;
			this.step_3 = false;
		},
		getCityTranslation(data) {
			const city_data = {
				city_from: '',
				city_end: ''
			};
			if (this.$i18n.locale === 'tr') {
				city_data.city_from = data.city_from.turkish
				city_data.city_end = data.city_end.turkish
			} else if (this.$i18n.locale === 'ru') {
				city_data.city_from = data.city_from.russian
				city_data.city_end = data.city_end.russian
			} else if (this.$i18n.locale === 'en') {
				city_data.city_from = data.city_from.english
				city_data.city_end = data.city_end.english
			}
			return city_data
		},
		async updateDetails(payload) {

			this.form = payload
		}
	}

})
</script>

