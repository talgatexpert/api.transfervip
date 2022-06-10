<template>
	<div>
		<header-admin update="" :breadcrumbs-list="breadcrumbs" :pageTitle="pageTitle"></header-admin>
		<v-form class="mt-7" v-model="valid" ref="form" @submit.prevent="save" style="margin: 0.8rem">

			<div class="grid grid-col-lg-2 grid-col-1">
				<div class="main-box-bg m-0  p-5">
					<v-row>
						<v-col cols="12" lg="6" md="6" sm="12">
							<v-autocomplete :value="form.city_from" :items="citiesFrom" return-object data-from=""
							                v-model="form.city_from"
							                @change="updateCity"
							                @keyup="findCity"
							                :rules="rules.text"
							                :item-text="language"
							                prepend-icon="mdi-crosshairs-gps"
							                label="Nereden" :error-messages="errors.city_from"></v-autocomplete>
						</v-col>
						<v-col cols="12" lg="6" md="6" sm="12">
							<v-autocomplete :items="citiesTo" data-to @keyup="findCity" return-object
							                :error-messages="errors.city_end"
							                v-model="form.city_end"
							                @change="updateCity"
							                required
							                :rules="rules.text"

							                prepend-icon="mdi-crosshairs-gps"
							                :item-text="language"
							                item-value="id"
							                label="Nereye"></v-autocomplete>
						</v-col>
						<v-col cols="12" lg="6" md="12">
							<v-text-field
									v-model="form.address"
									label="Adres"
									required
									:rules="rules.text"

									placeholder="Otel ismi"
									prepend-icon="mdi-city-variant"
									:error-messages="errors.address"
							></v-text-field>
						</v-col>
						<v-col cols="12" lg="6" md="6">
							<v-text-field
									v-model="form.flight_number"
									label="Uçuş Numarası"
									placeholder="TCK6526"
									:rules="rules.text"

									required
									prepend-icon="mdi-airplane-takeoff"
									:error-messages="errors.flight_number"
							></v-text-field>
						</v-col>
						<v-col cols="12" lg="6" md="6">
							<v-menu
									ref="menu1"
									v-model="menu1"
									:close-on-content-click="false"
									transition="scale-transition"
									offset-y
									max-width="290px"
									min-width="auto"
							>
								<template v-slot:activator="{ on, attrs }">
									<v-text-field
											v-model="startDateFormatted"
											label="Başlangıç tarihi"
											persistent-hint
											prepend-icon="mdi-calendar"
											v-bind="attrs"
											required
											readonly
											:rules="rules.text"

											v-on="on"
											:error-messages="errors.started_at"

									></v-text-field>
								</template>
								<v-date-picker
										no-title
										:value="form.started_at"
										locale="tr"
										:min="today"
										first-day-of-week="1"
										v-model="started_at"
										@input="menu1 = false"
								></v-date-picker>
							</v-menu>
						</v-col>
						<v-col cols="12" lg="6" md="6">
							<v-menu
									ref="menu2"
									v-model="menu2"
									:close-on-content-click="false"
									transition="scale-transition"
									offset-y
									max-width="290px"
									min-width="auto"
							>
								<template v-slot:activator="{ on, attrs }">
									<v-text-field
											v-model="form.started_at_time"
											label="Başlangıç zamani"
											persistent-hint
											prepend-icon="mdi-clock-time-eight-outline"
											v-bind="attrs"
											:rules="rules.text"

											required
											readonly
											v-on="on"
											:error-messages="errors.started_at_time"
									></v-text-field>
								</template>
								<v-time-picker

										v-model="form.started_at_time"
										class="mt-4"
										format="24hr"

										@input="menu2 = false"

								></v-time-picker>
							</v-menu>
						</v-col>
					</v-row>
					<v-divider class="mt-5 mb-5"></v-divider>


					<!--						TODO RETURN TRIP START-->
					<v-row v-if="form.return_trip">
						<v-card-title class="text-center w-100 d-block">{{
								$t('checkout.transfer_details.return_trip')
							}}
						</v-card-title>
						<v-col cols="12" lg="6" md="6" sm="12">
							<v-autocomplete :value="form.city_end" :items="citiesTo" return-object data-from=""
							                v-model="form.city_end"
							                @change="updateCity"
							                @keyup="findCity"
							                :rules="form.return_trip ? rules.text : []"

							                :item-text="language"
							                prepend-icon="mdi-crosshairs-gps"
							                label="Nereden" :error-messages="errors.city_end"></v-autocomplete>
						</v-col>
						<v-col cols="12" lg="6" md="6" sm="12">
							<v-autocomplete :items="citiesFrom" data-to @keyup="findCity" return-object
							                :error-messages="errors.city_from"
							                v-model="form.city_from"
							                @change="updateCity"
							                :rules="form.return_trip ? rules.text : []"

							                prepend-icon="mdi-crosshairs-gps"
							                :item-text="language"
							                item-value="id"
							                label="Nereye"></v-autocomplete>
						</v-col>
						<v-col cols="12" lg="6" md="12">
							<v-text-field
									v-model="form.return_trip_address"
									label="Adres"
									:rules="form.return_trip ? rules.text : []"

									placeholder="Otel ismi"
									prepend-icon="mdi-city-variant"
									:error-messages="errors.return_trip_address"
							></v-text-field>
						</v-col>
						<v-col cols="12" lg="6" md="6">
							<v-text-field
									v-model="form.return_trip_flight_number"
									label="Uçuş Numarası"
									placeholder="TCK6526"
									:rules="form.return_trip ? rules.text : []"

									prepend-icon="mdi-airplane-takeoff"
									:error-messages="errors.return_trip_flight_number"
							></v-text-field>
						</v-col>
						<v-col cols="12" lg="6" md="6">
							<v-menu
									ref="menu1"
									v-model="menu4"
									:close-on-content-click="false"
									transition="scale-transition"
									offset-y
									max-width="290px"
									min-width="auto"
							>
								<template v-slot:activator="{ on, attrs }">
									<v-text-field
											v-model="endDateFormatted"
											label="Başlangıç tarihi"
											persistent-hint
											prepend-icon="mdi-calendar"
											v-bind="attrs"
											readonly
											:rules="form.return_trip ? rules.text : []"

											v-on="on"
											:error-messages="errors.return_trip_started_at"

									></v-text-field>
								</template>
								<v-date-picker
										no-title
										:value="form.return_trip_started_at"
										locale="tr"
										:min="today"

										first-day-of-week="1"
										v-model="ended_at"
										@input="menu4 = false"
								></v-date-picker>
							</v-menu>
						</v-col>
						<v-col cols="12" lg="6" md="6">
							<v-menu
									ref="menu2"
									v-model="menu3"
									:close-on-content-click="false"
									transition="scale-transition"
									offset-y
									max-width="290px"
									min-width="auto"
							>
								<template v-slot:activator="{ on, attrs }">
									<v-text-field
											v-model="form.return_trip_started_at_time"
											label="Başlangıç zamani"
											persistent-hint
											prepend-icon="mdi-clock-time-eight-outline"
											v-bind="attrs"
											readonly
											v-on="on"
											:rules="form.return_trip ? rules.text : []"

											:error-messages="errors.return_trip_started_at_time"
									></v-text-field>
								</template>
								<v-time-picker

										v-model="form.return_trip_started_at_time"
										class="mt-4"
										format="24hr"

										@input="menu3 = false"

								></v-time-picker>
							</v-menu>
						</v-col>
					</v-row>
					<!--						TODO RETURN TRIP END-->

				</div>
				<div class="main-box-bg m-0  p-5">
					<v-row>
						<v-col cols="12" lg="6" md="6">
							<v-text-field
									v-model="form.name"
									label="First Name Last Name"
									persistent-hint
									prepend-icon="mdi-comment-text"

									:rules="rules.text"

									:error-messages="errors.name"

							></v-text-field>
						</v-col>
						<v-col cols="12" lg="6" md="6">
							<v-text-field
									v-model="form.email"
									label="Email"
									:rules="rules.email"

									persistent-hint
									prepend-icon="mdi-email"

									:error-messages="errors.email"

							></v-text-field>
						</v-col>
						<v-col cols="12" lg="6" md="6">
							<v-text-field
									v-model="form.phone"
									label="Phone"
									persistent-hint
									:rules="rules.text"

									prepend-icon="mdi-cellphone"

									:error-messages="errors.phone"

							></v-text-field>
						</v-col>
						<v-col cols="12" lg="12" md="6">
							<div class="form form-container v-bookings-model">
								<div class="padding-form">
									<h5 class="form-title">{{ $t('checkout.passengers.title') }}</h5>
									<div class="label d-flex align-items-center justify-content-between">
										<div class="label-container">
											<div class="label-title">{{ $t('checkout.passengers.number') }}</div>
											<div class="label-subtitle">{{ $t('checkout.passengers.sub_number') }}</div>
										</div>
										<div class="label-counter">
											<div class="v-incrementer">
												<div @click="decrement"
												     v-bind:class="[disabled_decrement ? 'button-counter decrement button-counter__disabled' : 'button-counter decrement']"></div>
												<span class="v-incrementer-value">{{ form.passengers_number }}</span>
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

								<div class="padding-form">
									<div class="align-items-center row">
										<div class="col-lg-8 col-8">
											<div class="return-content ">
												<p class="mb-0 label-title">{{ $t('checkout.transfer_details.return_trip') }}</p>
												<span class="return-content__price">{{ form.price }} {{ form.currency }}</span>
											</div>
										</div>
										<div class="col-lg-4 col-4">
											<div class="custom-checkbox default-checkbox">
												<input type="checkbox" v-model="form.return_trip" id="return"
												       class="custom-checkbox__input color-grey">
												<label for="return" class="color-grey invert"></label>
											</div>
										</div>

									</div>
								</div>


							</div>
							<payment @select-payment="selectPayment"></payment>

						</v-col>
						<v-col cols="12" lg="6" md="6">
							<v-btn type="submit" block depressed class="mt-5"
							       :disabled="disabled"
							       color="primary">Kaydetmek
							</v-btn>
						</v-col>

					</v-row>
				</div>
			</div>
			<scale-loader :loading="loading" class="mt-5"></scale-loader>
			<div class="grid grid-col-xl-3 grid-col-lg-2 grid-col-md-2 grid-col-1 mt-5">


				<div class="main-box-bg p-0 h-max-content"
				     :class="(active.transfer_id === car.transfer_id && active.id === car.id)? 'border-green' : 'border-transparent'"

				     v-for="(car, key) in transfers" :key="`car_${key}`"
				>

					<booking-car :car="car"
					             @change="setCar"
					             :transfer-id="car.transfer_id"></booking-car>


				</div>

			</div>
			<v-row>
				<v-col cols="12" md="12"
				       v-if="transfers"

				>
					<v-pagination
							v-model="page"
							:length="last"
							@input="next()"

					></v-pagination>
				</v-col>

				<v-col cols="12" md="12" v-if="total === 0">
					<v-card-title class="text-center d-block">Транспортные средства не найдены</v-card-title>
				</v-col>
				<v-col cols="12" md="12">
					<v-divider></v-divider>
				</v-col>

			</v-row>

		</v-form>
	</div>
</template>

<script>
import HeaderAdmin from "../../../components/admin/HeaderAdmin";
import bookingCar from "../../../components/admin/bookingCar";
import Vue from 'vue'
import VueMoment from 'vue-moment'
import moment from 'moment-timezone'
import {useLocaleCode} from "../../../hooks/locale";
import login from "../../login";
import ScaleLoader from "vue-spinner/src/ScaleLoader.vue";
import Payment from "../../../components/admin/Payment";
import {getMetaData} from "../../../hooks/meta";


if (!Vue.moment) {
	Vue.use(VueMoment, {
		moment,
	})
}


export default {
	name: "id",
	layout: 'admin',
	components: {HeaderAdmin, bookingCar, ScaleLoader, Payment},
	head() {
		return {
			title: this.title,
			meta: this.meta
		}
	},
	data() {
		return {
			title: '',
			meta: '',
			loading: false,
			disabled: false,
			page: 1,
			valid: true,
			active: false,
			breadcrumbs: [
				{
					text: this.$t('panel.dashboard'),
					disabled: false,
					href: '/' + this.$i18n.locale + '/panel/',
				},
				{
					text: this.$t('panel.menu.bookings'),
					disabled: false,
					href: '/' + this.$i18n.locale + '/panel/bookings',
				},
				{
					text: 'Ekle',
					disabled: false,
					href: '/' + this.$i18n.locale + '/panel/bookings/' + this.$route.params.id,
				},
			],
			citiesFrom: [],
			language: 'turkish',
			transfers: null,

			rules: {
				email: [
					v => !!v || 'E-mail is required',
					v => /.+@.+/.test(v) || 'E-mail must be valid',
				],
				text: [
					v => !!v || 'Field is required',
				],
			},
			total: null,
			last: 1,
			citiesTo: [],
			next_page: null,
			prev_page: null,
			// description: '',
			// price: '',
			started_at: '',
			ended_at: '',
			// time: '',
			errors: [],
			startDateFormatted: '',
			endDateFormatted: '',
			menu1: false,
			menu2: false,
			menu3: false,
			menu4: false,
			disabled_increment: false,
			disabled_decrement: true,
			// selected_cars: [],
			// cars: [],
			// cityFrom: '',
			// cityTo: '',
			min_passengers: 1,
			passengers_number: 1,
			max_passengers: 5,
			form: {
				min_passengers: 1,
				transfer_id: null,
				pay_type: '',
				name: null,
				city_end: null,
				city_from: null,
				flight_number: null,
				passengers_number: 1,
				max_passengers: 5,
				started_at_time: null,
				started_at: null,
				address: null,
				cars: [],
				car: null,
				add_child_seat: false,
				return_trip: false,
				return_trip_address: null,
				return_trip_flight_number: null,
				return_trip_started_at: null,
				return_trip_started_at_time: null,
			},
			id: this.$route.params.id !== "new" ? this.$route.params.id : false,

		}
	},

	async asyncData({store, i18n, $axios}) {
		const language = useLocaleCode(i18n);
		await store.dispatch('panel/city/SEARCH_CITIES', '?limit=5&language=' + language + '&');
		let fromCities = await store.getters['panel/city/GET_CITIES'];
		let toCities = await store.getters['panel/city/GET_CITIES'];
		const meta = await getMetaData($axios, i18n, i18n.t('panel.menu.bookings'), true);


		return {
			language, citiesFrom: fromCities, citiesTo: toCities, ...meta,
		}
	},


	watch: {
		started_at() {
			this.startDateFormatted = moment(this.started_at).format('DD/MM/YYYY')
			this.form.started_at = this.started_at;
		},
		ended_at() {
			this.endDateFormatted = moment(this.ended_at).format('DD/MM/YYYY')
			this.form.return_trip_started_at = this.ended_at;

		},
	},
	computed: {
		pageTitle() {
			return 'Rezervasyonu düzenle'
		},
		today() {
			return (new Date()).toISOString()
		}
	},


	async mounted() {
		if (this.id) {
			await this.$store.dispatch('panel/bookings/GET_BOOKING', {id: this.id})
			const item = await this.$store.getters['panel/bookings/GET_BOOKING']
			this.form = JSON.parse(JSON.stringify(item));
			if (this.form) {
				this.citiesFrom = [this.form.city_from]
				this.citiesTo = [this.form.city_end]
				this.startDateFormatted = moment(this.form.started_at).format('DD/MM/YYYY')
				this.endDateFormatted = moment(this.form.started_at).format('DD/MM/YYYY')
				this.form.started_at = moment(this.form.started_at, 'DD.MM.YYYY').format('YYYY-MM-DD')
				this.form.return_trip_started_at = moment(this.form.return_trip_started_at, 'DD.MM.YYYY').format('YYYY-MM-DD')
			}
		}

	},

	methods: {
		selectPayment(payload) {
			this.form = {...this.form, card: {...payload.data}, pay_type: payload.method}
		},

		setCar(car) {
			this.form.transfer_id = car.transfer_id;
			this.form.car = car
			this.active = car
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
		},

		allowedMinutes: v => v >= 10 && v <= 50,
		allowedStep: m => m % 10 === 0,
		async findCity(e) {
			const value = e.target.value.trim();

			if (e.target.attributes.hasOwnProperty('data-from') && value) {
				let to = this.cityTo ? 'except=' + this.cityTo : '';
				await this.$store.dispatch('panel/city/SEARCH_CITIES', '?search=' + value + '&limit=5&language=' + this.language + '&' + to);
				this.citiesFrom = await this.$store.getters['panel/city/GET_CITIES']
			}
			if (e.target.attributes.hasOwnProperty('data-to') && value) {
				let from = this.cityFrom ? 'except=' + this.cityFrom : '';
				await this.$store.dispatch('panel/city/SEARCH_CITIES', '?search=' + value + '&limit=5&language=' + this.language + '&' + from);
				this.citiesTo = await this.$store.getters['panel/city/GET_CITIES']
			}
		},
		async updateCity(item) {
			if (this.form.city_from && this.form.city_end) {
				await this.getData();
			}
		},
		async getData() {
			this.transfers = null;
			this.total = null;
			this.loading = true;
			await this.$store.dispatch('panel/bookings/GET_TRANSFERS', {
				page: '?page=' + this.page,
				city_from: this.form.city_from.id,
				city_end: this.form.city_end.id
			})
			const transfers = await this.$store.getters['panel/bookings/GET_TRANSFERS']
			const next = await this.$store.getters['panel/bookings/GET_NEXT_DATA']
			this.loading = false;
			const arr = []
			transfers.forEach(item => {
				if (item.cars) {
					arr.push(...item.cars)
				}
			});
			if (arr.length > 0)
				this.transfers = arr
			else
				this.transfers = null;
			const data = await this.$store.getters['panel/bookings/GET_TRANSFER_DATA']
			this.total = data.total;
			this.prev_page = data.prev;
			this.next_page = data.next;
			if (!next)
				this.last = data.last - 1;
			else
				this.last = data.last;


		},
		async next() {
			// if (this.next_page) {
			await this.getData();


			// }
		},
		async save() {
			if (this.$refs.form.validate()) {
				this.disabled = true
				await this.$store.dispatch('panel/bookings/CREATE_BOOKING', this.form);
				this.errors = await this.$store.getters['panel/bookings/GET_ERRORS'];
				const success = await this.$store.getters['panel/bookings/GET_SUCCESS'];
				this.disabled = false;

				if (success)
					await this.$router.push('/' + this.$i18n.locale + '/panel/bookings')
			}

		}


	}
}
</script>
<style scoped lang="scss">

$grey: #a6a9b6;
$main: #1976d2;
$main-hover: darken($main, 5%);
$white: #fff;

.grid {
	display: grid;
	gap: 20px;
}

@media screen and (max-width: 992px) {
	.grid-col-lg-2 {
		grid-template-columns: 1fr 1fr;
	}
	.grid-col-lg-3 {
		grid-template-columns: 1fr 1fr 1fr;
	}

}

@media screen and (min-width: 992px) {
	.grid-col-lg-2 {
		grid-template-columns: 1fr 1fr;
	}
	.grid-col-lg-3 {
		grid-template-columns: 1fr 1fr 1fr;
	}
	.grid-col-xl-3 {
		grid-template-columns: 1fr 1fr 1fr;
	}
}


@media screen and (max-width: 768px) {
	.grid-col-md-2 {
		grid-template-columns: 1fr 1fr;
	}
}

@media screen and (max-width: 576px) {
	.grid {
		display: grid;
		gap: 50px;
		grid-template-columns: 1fr;

	}
	.grid-col-1 {
		grid-template-columns: 1fr;
	}


}

.h-max-content {
	height: max-content !important;
}

.custom-checkbox {
	width: 100%;
	display: flex;
	justify-content: center;
	position: relative;
	margin-left: 516px;
	margin-top: 27px;
	color: $white;


	&.default-checkbox {
		margin: 0 0 10px;
	}


	&__input:checked + label:before {
		background: $main;
	}

	&__input.color-grey:checked + label:before {
		background: $main;
	}


	&__input:checked + label:after {
		left: 22px;
	}

	&__input:checked + label.invert:after {
		left: initial;
		right: -4px;
	}

	&__input + label {
		text-align: center;
		position: relative;
		padding: 0 0 0 57px;
		cursor: pointer;
		font-size: 18px;
		display: flex;
		width: 100%;
		align-items: center;


		&:before, &:after {
			position: absolute;
			transition: .2s;
			content: '';
		}

		span {
			display: block;
			margin-top: -2px;
		}

		&.color-grey {


			&:after {
				border: 1px solid $grey;
			}

			&:before {

				background: $grey;
			}
		}

		&:after {
			top: -2px;
			left: -4px;
			width: 26px;
			height: 26px;
			border-radius: 50%;
			background: #FFF;
		}

		&.invert:after {
			right: 22px;
			left: initial;
		}

		&:before {
			top: 0;
			left: 0;
			width: 43px;
			height: 22px;
			border-radius: 13px;
			background: rgba(255, 255, 255, .6);
		}

		&.invert:before {
			right: 0;
			left: initial;
		}
	}

	&__input {
		position: absolute;
		z-index: -1;
		opacity: 0;
	}
}

.border-transparent {
	border: 3px solid transparent;

}

.border-green {
	border: 3px solid #53ea53;
}

@media screen and (max-width: 576px) {
	.custom-checkbox {
		margin: 2rem auto 0;
		width: fit-content;
	}

}


.v-bookings-model {
	.label {
		margin-bottom: 1.5rem;
	}

	.label-title {
		font-weight: 800;
	}

	.label-subtitle {
		font-size: 14px;
	}
}

.v-incrementer {
	background: lighten($grey, 20%);
	display: flex;
	align-items: center;
	border-radius: 30px;
	justify-content: space-around;
	padding: 5px;
	width: 100px;

	.decrement, .increment {
		position: relative;
		display: inline-block;
		width: 24px;
		height: 24px;
		background: $main;
		border: 0;
		text-align: center;
		border-radius: 50%;
		cursor: pointer;
		color: $white;
		font-weight: 900;
	}

	.button-counter {

		&__disabled {
			background: $grey;
		}

		&::after, &::before {
			content: "";
			position: absolute;
			top: 50%;
			left: 50%;
			width: 12.3px;
			height: 2px;
			-webkit-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
			background: #fff;
			border-radius: 1px;
		}
	}

	.decrement::after {
		display: none;
	}

	.increment:after {
		-webkit-transform: translate(-50%, -50%) rotate(90deg);
		transform: translate(-50%, -50%) rotate(90deg);
	}
}

</style>
