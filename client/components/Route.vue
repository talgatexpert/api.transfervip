<template>
	<div class="route-component padding-form  ">
		<h4 class="form-title" v-if="title">{{ title }}</h4>
		<div class="form-title__detail">
			<i class="i-time"></i> <span>Travel time: ~ 1 h</span>
		</div>
		<div class="route-list">
			<div class="route-list__start">{{ returnTrip ? form.return_booking.city_from : form.city_from }}</div>
			<span class="separator">-</span>
			<div class="route-list__end">{{ returnTrip ? form.return_booking.city_end : form.city_end }}</div>
		</div>
		<div class="route-details form-group">
			<label for="hotel">Departure: hotel name or address *</label>
			<input type="text" v-if="returnTrip" v-model="form.return_booking.address" class="form-control form-input" id="hotel">
			<input type="text" v-else v-model="form.address" class="form-control form-input" id="hotel">
			<span class="error-form d-block">Enter the address or hotel name.</span>

			<label for="flight" class="mt-2 d-block">Flight Number *</label>
			<input type="text" v-if="returnTrip" v-model="form.return_booking.flight_number" placeholder="TCR3835" class="form-control form-input" id="flight">
			<input type="text" v-else  v-model="form.flight_number" placeholder="TCR3835" class="form-control form-input" id="flight">
			<span class="error-form">Enter the flight number</span>

			<div class="route-details__date">
				<datepicker class="v-datepicker" :lang="$i18n.locale" v-if="returnTrip" v-model=" form.return_booking.started_at"/>
				<datepicker class="v-datepicker" :lang="$i18n.locale" v-if="!returnTrip" v-model="form.started_at"/>

				<vue-timepicker class="timepicker"  v-if="returnTrip" format="HH:mm" :minute-interval="5" v-model="form.return_booking.started_at_time"></vue-timepicker>
				<vue-timepicker class="timepicker"  v-else format="HH:mm" :minute-interval="5" v-model="form.started_at_time"></vue-timepicker>
			</div>


		</div>
	</div>
</template>

<script>
import VueTimepicker from "vue2-timepicker";
import VueDatepickerUi from "vue-datepicker-ui";
import VueMoment from 'vue-moment'
import moment from 'moment-timezone'
import Vue from 'vue'

if (!Vue.moment) {
	Vue.use(VueMoment, {
		moment,
	})
}


export default {
	name: "Route",
	components: {
		VueTimepicker,
		Datepicker: VueDatepickerUi,
	},
	props: {
		selectedValue: Object,
		returnTrip: {
			type: Boolean,
			default: false,
		},
		title: String,

	},

	data() {
		return {
			form: {
				city_from: '',
				city_end: '',
				email: '',
				address: '',
				name: '',
				flight_number: '',
				return_trip: '',
				passengers_number: '',
				add_child_seat: '',
				started_at: null,
				started_at_time: null,
				currency: '',
				return_booking: {
					address: '',
					city_from: '',
					city_end: '',
					flight_number: '',
					started_at: '',
					started_at_time: {
						HH: '',
						mm: ''
					},
				}
			},
		}
	},
	mounted() {
		this.form = Object.assign(this.form, this.selectedValue)
		this.form = {
			...this.form,
			return_booking: {...this.return_booking, city_end: this.form.city_from, city_from: this.form.city_end}
		}

	},
	watch: {
		form: {
			handler(val) {
				this.$emit('update-booking', this.form);

			},
			deep: true,
		},
	},

	methods: {
		// async getFormInfo() {
		// 	this.form = Object.assign(this.form, this.selectedValue)
		// },

	},
}
</script>

<style scoped>

</style>
