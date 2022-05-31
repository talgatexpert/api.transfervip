<template>
	<div>
		<header-admin update="" :breadcrumbs-list="breadcrumbs" :pageTitle="pageTitle"></header-admin>
		<div class="main-box-container mt-7">
			<v-container>
				<v-row>
					<v-col cols="12" lg="6" md="6" sm="12">
						<v-autocomplete :value="form.city_from" :items="citiesFrom" return-object data-from="" v-model="form.city_from"
						                @keyup="findCity"
						                item-text="turkish"
						                prepend-icon="mdi-crosshairs-gps"
						               label="Nereden" :error-messages="errors.city_from"></v-autocomplete>
					</v-col>
					<v-col cols="12" lg="6" md="6" sm="12">
						<v-autocomplete :items="citiesTo" data-to @keyup="findCity" return-object :error-messages="errors.city_end"
						                v-model="form.city_end"
						                prepend-icon="mdi-crosshairs-gps"
						                item-text="turkish" item-value="id"
						                label="Nereye"></v-autocomplete>
					</v-col>
					<v-col cols="12" lg="3" md="12">
						<v-text-field
								v-model="form.address"
								label="Adres"
								placeholder="Otel ismi"
								prepend-icon="mdi-city-variant"
								:error-messages="errors.address"
						></v-text-field>
					</v-col>

					<v-col cols="12" lg="3" md="12">
						<v-text-field
								v-model="form.flight_number"
								label="Uçuş Numarası"
								placeholder="TCK6526"
								prepend-icon="mdi-airplane-takeoff"
								:error-messages="errors.flight_number"
						></v-text-field>
					</v-col>
					<v-col cols="12" lg="4" md="12">
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
										readonly
										v-on="on"
										:error-messages="errors.started_at"

								></v-text-field>
							</template>
							<v-date-picker
									no-title
									:value="form.started_at"
									locale="tr"

									first-day-of-week="1"
									v-model="form.started_at"
									@input="menu1 = false"
							></v-date-picker>
						</v-menu>
					</v-col>


					<v-col cols="12" lg="4" md="12">
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

					<v-col cols="12">
						<v-row>
							<v-col cols="12" lg="3" md="4" v-for="car in form.cars" :key="car.id">
								<v-card>
									<div class="img">
										<v-img :src="car.image" max-height="200" :alt="car.name"/>
									</div>
									<v-card-title>{{ car.full_name }}</v-card-title>
								</v-card>
							</v-col>
						</v-row>
					</v-col>

					<v-col cols="12" md="12">
						<v-divider></v-divider>
<!--						<v-textarea-->
<!--								v-model="description"-->
<!--								name="input-7-1"-->
<!--								label="Transfer açıklaması"-->
<!--								hint="Transferinizin açıklamasını burada yazabilirsiniz"-->
<!--						></v-textarea>-->

						<v-btn block depressed class="mt-5" @click="save"
						       color="primary">Kaydetmek
						</v-btn>
					</v-col>

				</v-row>
			</v-container>
		</div>
	</div>
</template>

<script>
import HeaderAdmin from "../../../components/admin/HeaderAdmin";
import car_card from "../../../components/admin/car_card";
import Vue from 'vue'
import VueMoment from 'vue-moment'
import moment from 'moment-timezone'

if (!Vue.moment) {
	Vue.use(VueMoment, {
		moment,
	})
}


export default {
	name: "id",
	layout: 'admin',
	components: {HeaderAdmin, car_card},
	data() {
		return {
			breadcrumbs: [
				{
					text: 'Kontrol paneli',
					disabled: false,
					href: '/panel/',
				},
				{
					text: 'Rezervasyonlar',
					disabled: false,
					href: '/panel/bookings',
				},
				{
					text: 'Ekle',
					disabled: false,
					href: '/panel/bookings/' + this.$route.params.id,
				},
			],
			citiesFrom: [],
			// select: [],
			citiesTo: [],
			// description: '',
			// price: '',
			started_at: '',
			// time: '',
			errors: [],
			startDateFormatted: '',
			endDateFormatted: '',
			menu1: false,
			menu2: false,
			// selected_cars: [],
			// cars: [],
			// cityFrom: '',
			// cityTo: '',
			form: {
				city_end: null,
				city_from: null,
				flight_number: null,
				started_at_time: null,
				started_at: null,
				address: null,
				cars: [],
				car: null,
				child_seat: null,
				return_trip: false,
				return_trip_address: null,
				return_trip_flight_number: null,
				return_trip_started_at: null,
				return_trip_time: null,
			},
			id: this.$route.params.id !== "new" ? this.$route.params.id : false,

		}
	},



	watch: {
		started_at() {
			this.startDateFormatted = moment(this.form.started_at).format('DD/MM/YYYY')
		},
		ended_at() {
			this.endDateFormatted = moment(this.form.ended_at).format('DD/MM/YYYY')
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
		await this.$store.dispatch('panel/bookings/GET_BOOKING', {id: this.id})
		const item = await  this.$store.getters['panel/bookings/GET_BOOKING']
		this.form = JSON.parse(JSON.stringify(item));
		this.citiesFrom = [this.form.city_from]
		this.citiesTo = [this.form.city_end]
		this.startDateFormatted = moment(this.form.started_at).format('DD/MM/YYYY')
		this.form.started_at = moment(this.form.started_at, 'DD.MM.YYYY').format('YYYY-MM-DD')
	},

	methods: {

		allowedMinutes: v => v >= 10 && v <= 50,
		allowedStep: m => m % 10 === 0,
		findCity(){

		},
		save(){

		}


	}
}
</script>

