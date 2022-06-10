<template>
	<div class="form form-container" v-if="changed">
		<div class="padding-form">
			<div class="d-flex align-items-center justify-content-between">
				<h4 class="form-title m-0">{{ $t('checkout.transfer_details.title') }}</h4>

				<div class="form-action" @click="change">{{ $t('checkout.transfer_details.change') }}</div>
			</div>
		</div>
	</div>
	<div v-else-if="edit">
		<scale-loader :loading="loading"></scale-loader>
		<div v-if="!loading">
			<div class="form form-container">

				<div class="route-component padding-form  ">
					<h4 class="form-title" v-if="title">{{ title }}</h4>
					<div class="form-title__detail">
						<i class="i-time"></i> <span>{{ $t('checkout.transfer_details.title') }}</span>
					</div>
					<div class="route-list">
						<div class="route-list__start">{{ form.city_from }}</div>
						<span class="separator">-</span>
						<div class="route-list__end">{{ form.city_end }}</div>
					</div>
					<div class="route-details form-group">
						<label for="hotel">{{ $t('checkout.transfer_details.address') }}</label>
						<input type="text" v-model="form.address" class="form-control form-input" id="hotel">
						<span class="error-form d-block" v-if="errors.address">{{ $t('checkout.errors.address') }}</span>

						<label for="flight" class="mt-2 d-block">{{ $t('checkout.transfer_details.flight_number') }}</label>
						<input type="text" v-model="form.flight_number" placeholder="TCR3835" class="form-control form-input"
						       id="flight">
						<span class="error-form" v-if="errors.flight_number">{{ $t('checkout.errors.flight_number') }}</span>

						<div class="route-details__date">
							<datepicker class="v-datepicker" :class="errors.started_at ? 'border-red' : ''" :lang="$i18n.locale"
							            v-model="form.started_at" :disabled-start-date="{from: null, to: Date.now()}"/>

							<vue-timepicker class="timepicker" format="HH:mm" :minute-interval="5"
							                v-model="form.started_at_time"></vue-timepicker>
						</div>


					</div>
				</div>


				<div class="form-label border-form">
					<div class="padding-form">
						<div class="align-items-center row">
							<div class="col-lg-8 col-8">
								<div class="return-content ">
									<p>{{ $t('checkout.transfer_details.return_trip') }}</p>
									<span class="return-content__price">{{ form.price }} {{ form.currency }}</span>
								</div>
							</div>
							<div class="col-lg-4 col-4">
								<div class="custom-checkbox default-checkbox">
									<input type="checkbox" @change="updateReturnTrip" v-model="form.return_trip" id="return"
									       class="custom-checkbox__input color-grey">
									<label for="return" class="color-grey invert"></label>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="route-component padding-form  " v-if="form.return_trip">
					<h4 class="form-title" v-if="title">{{ title }}</h4>
					<div class="form-title__detail">
						<i class="i-time"></i> <span>{{ $t('checkout.transfer_details.travel_time') }}</span>
					</div>
					<div class="route-list">
						<div class="route-list__start">{{ form.city_end }}</div>
						<span class="separator">-</span>
						<div class="route-list__end">{{ form.city_from }}</div>
					</div>
					<div class="route-details form-group">
						<label for="return_booking_hotel">{{ $t('checkout.transfer_details.address') }}</label>
						<input type="text" v-model="form.return_booking.address" class="form-control form-input" id="return_booking_hotel">
						<span class="error-form" v-if="errors.return_booking && errors.return_booking.address">{{ $t('checkout.errors.address') }}</span>

						<label for="return_booking_flight" class="mt-2 d-block">{{ $t('checkout.transfer_details.flight_number') }}</label>
						<input type="text" v-model="form.return_booking.flight_number" placeholder="TCR3835"
						       class="form-control form-input" id="return_booking_flight">
						<span class="error-form" v-if="errors.return_booking && errors.return_booking.flight_number">{{ $t('checkout.errors.flight_number') }}</span>

						<div class="route-details__date">
							<datepicker class="v-datepicker" :disabled-start-date="{from: null, to: form.started_at}"  :lang="$i18n.locale" v-model=" form.return_booking.started_at"/>

							<vue-timepicker class="timepicker" format="HH:mm" :minute-interval="5"
							                v-model="form.return_booking.started_at_time"></vue-timepicker>
						</div>


					</div>
				</div>


			</div>
			<div class="form form-container">
				<div class="padding-form">
					<h5 class="form-title">{{ $t('checkout.passengers.title') }}</h5>
					<div class="label d-flex align-items-center justify-content-between">
						<div class="label-container">
							<div class="label-title">{{ $t('checkout.passengers.number') }}</div>
							<div class="label-subtitle">{{ $t('checkout.passengers.sub_number') }}</div>
						</div>
						<div class="label-counter">
							<div class="incrementer">
								<div @click="decrement"
								     v-bind:class="[disabled_decrement ? 'button-counter decrement button-counter__disabled' : 'button-counter decrement']"></div>
								<span class="incrementer-value">{{ form.passengers_number }}</span>
								<div @click="increment"
								     v-bind:class="[disabled_increment ? 'button-counter increment  button-counter__disabled' : 'button-counter increment']"></div>
							</div>
						</div>
					</div>
					<div class="label d-flex align-items-center justify-content-between">
						<div class="label-container">
							<div class="label-title">{{ $t('checkout.passengers.child_seat') }}</div>
							<div class="label-subtitle">{{ $t('checkout.passengers.sub_child_seat') }}</div>
						</div>
						<div class="label-counter">
							<div class="custom-checkbox default-checkbox">
								<input type="checkbox" id="child_seat"
								       v-model="form.add_child_seat"
								       class="custom-checkbox__input color-grey">
								<label for="child_seat" class="color-grey invert"></label>
							</div>
						</div>
					</div>

				</div>

			</div>
			<div class="form form-container">
				<div class="padding-form">
					<h5 class="form-title">{{ $t('checkout.passengers_details.title') }}</h5>

					<div class="form-group">
						<label for="name_surname">{{ $t('checkout.passengers_details.name_surname') }}</label>
						<input type="text" v-model="form.name" class="form-control form-input" id="name_surname">
						<span class="error-form" v-if="errors.name">{{ $t('checkout.errors.name') }}</span>
					</div>

					<div class="form-group">
						<label for="passenger_email">{{ $t('checkout.passengers_details.email') }}</label>
						<div class="form-group-with__description">
							<input type="text" v-model="form.email" class="form-control form-input" id="passenger_email">
							<div class="form-group__description">
								{{ $t('checkout.passengers_details.sub_email') }}
							</div>
						</div>
						<span class="error-form" v-if="errors.email">
							{{ $t('checkout.errors.email') }}</span>
					</div>
					<div class="form-group">
						<label for="passenger_phone">
							{{ $t('checkout.passengers_details.phone') }}
						</label>
						<div class="form-group-with__description">
							<input type="text" v-model="form.phone" placeholder="+90 000 000 00 00" class="form-control form-input"
							       id="passenger_phone">
							<div class="form-group__description">

								{{ $t('checkout.passengers_details.sub_phone') }}
							</div>
						</div>
						<span class="error-form" v-if="errors.phone">{{ $t('checkout.errors.phone') }}</span>
					</div>

					<div class="form-privacy">
						{{ $t('checkout.passengers_details.privacy') }}
						<nuxt-link to="/information/privacy-policy">Privacy Policy.</nuxt-link>

					</div>
				</div>

			</div>
		</div>
	</div>
</template>

<script>
import ReturnAmount from "./ReturnAmount";
import Route from "./Route";
import VueTimepicker from "vue2-timepicker";
import VueDatepickerUi from "vue-datepicker-ui";
import moment from "moment-timezone";
import ScaleLoader from "vue-spinner/src/ScaleLoader.vue";

export default {
	name: "TransferDetails",
	props: {
		changed: Boolean,
		edit: Boolean,
		change: Function,
		toggle: Function,
		step: String,
		// errors: Object,
	},
	components: {
		ReturnAmount,
		Route,
		ScaleLoader,
		VueTimepicker,
		Datepicker: VueDatepickerUi,
	},
	data() {
		return {
			title: '',
			errors: {},
			loading: true,
			return_back_form: "",
			max_passengers: 5,
			min_passengers: 1,
			passengers: 1,
			disabled_increment: false,
			disabled_decrement: true,
			form: {
				city_from: '',
				city_end: '',
				email: '',
				address: '',
				name: '',
				flight_number: '',
				return_trip: '',
				passengers_number: 1,
				add_child_seat: '',
				started_at: null,
				started_at_time: '00:00',
				currency: '',
				return_booking: {
					address: '',
					city_from: '',
					city_end: '',
					flight_number: '',
					started_at: '',
					started_at_time: null
				}
			},


		}
	},

	watch: {
		form: {
			async handler(val) {
				this.updateData(this.form);
			},
			deep: true,
		},
	},
	async mounted() {
		this.loading = true
		this.form.passengers_number = this.passengers;
		await this.getFormInfo();

		this.$nuxt.$on('update-currency', async (currencyCode) => {
			this.loading = true;
			await this.updateData({...this.form, currency: currencyCode, updateCurrency: true})
			console.log(currencyCode)
			// this.form = await this.getFormInfo();
			this.loading = false;

		})

	},

	methods: {

		async getFormInfo() {
			await this.$store.dispatch('transfer/getBooking', {
				booking_token: this.$route.query.booking_token,
				currency: this.$route.query.currency
			});
			const data = await this.$store.getters['transfer/booking'];
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


			this.passengers = this.form.passengers_number ?? 1;
			this.form = JSON.parse(JSON.stringify(data))
			this.form = Object.assign(this.form, city_data)
			if (this.form.return_trip) {
				this.form = {
					...this.form,
					passengers_number: this.form.passengers_number !== null ? this.form.passengers_number : 1,
					address: this.form.return_booking.address,
					flight_number: this.form.return_booking.flight_number,
					return_booking: {...this.form.return_booking, city_from: this.form.city_end, city_end: this.form.city_from}
				}
			}
			this.loading = false
		},

		continue_payment() {

		},
		async updateData(payload) {


			payload = {
				...payload,
				booking_token: this.$route.query.booking_token,
				currency: payload.updateCurrency === true ? payload.currency : this.$route.query.currency,
				started_at: moment(payload.started_at).format('DD.MM.YYYY'),
				return_booking: {
					...payload.return_booking,
					started_at: moment(payload.return_booking.started_at).format('DD.MM.YYYY'),
				}


			}

			clearTimeout(this.timer)
			this.timer = setTimeout(async () => {
				await this.$store.dispatch('transfer/saveBooking', {...payload, step: this.step})
				this.errors = JSON.parse(JSON.stringify(await this.$store.getters['transfer/errors']));
				const data = await this.$store.getters['transfer/booking'];
				this.disabled_decrement = false;
				this.disabled_increment = false;
				if (data.passengers_number <= this.min_passengers) {
					this.disabled_decrement = true
				}
				if (data.passengers_number >= this.max_passengers) {
					this.disabled_increment = true;
				}
				this.$emit('update-details', {
					...payload,
					total: data.total,
					total_with_currency: data.total_with_currency,
					price: data.price,
					price_with_currency: data.price_with_currency,
					return_price: data.return_price,
					return_price_with_currency: data.return_price_with_currency,
				})
				this.$emit('details-error', JSON.parse(JSON.stringify(this.errors)))
			}, 500)

		},

		async updateReturnTrip() {
			await this.updateData({...this.form, updateReturnTrip: true})
		},

		decrement(event) {

			this.form = JSON.parse(JSON.stringify(this.form))
			this.form.passengers_number -= 1;
			this.disabled_decrement = false;
			this.disabled_increment = false;

			if (this.form.passengers_number < this.min_passengers) {
				this.form.passengers_number = this.min_passengers;
				this.disabled_decrement = true
			}
			this.updateData(this.form)
		},
		increment() {
			this.form = JSON.parse(JSON.stringify(this.form))

			this.form.passengers_number += 1;
			this.disabled_increment = false;
			this.disabled_decrement = false;
			if (this.form.passengers_number > this.max_passengers) {
				this.form.passengers_number = this.max_passengers;
				this.disabled_increment = true;
			}
			this.updateData(this.form)
		}
	},
}
</script>

<style scoped>

</style>
