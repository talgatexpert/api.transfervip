<template>
	<div class="car-card">
		<div class="car-card__picture border-card">
			<div class="car-card__picture--thumb">
				<img :src="car.image" :alt="car.type">
			</div>
			<div class="car-card__picture--name">
				{{ car.full_name }}
			</div>


		</div>
		<div class="car-card__description">
			<div class="car-card__description--class">
				{{ car.type }}
			</div>
			<div class="car-card__description--baggage">
				<span class="item-icon"><i class="icon i-person"></i><span>{{ car.person_quantity }}</span></span>
				<span class="item-icon ">     <i class="icon i-baggage"></i><span>{{ car.baggage_quantity }} </span></span>
			</div>
			<div class="car-card__description--services">

				<span class="item-icon"> <i class="icon i-close"></i> <span>Free cancellation</span></span>
				<span class="item-icon"> <i class="icon i-time"></i> <span>15 min of waiting included</span></span>
			</div>
		</div>
		<div class="car-card__offer">
			<div class="car-card__offer--price">
				{{ car.price }} {{ currency }}
			</div>
			<button class="btn btn-green" @click="setTransfer(car.id)">Select</button>
			<div class="car-card__offer--payments">
				<i class="icon i-cache"></i>
				<i class="icon i-mastercard"></i>
				<i class="icon i-visa"></i>
			</div>
		</div>
	</div>
</template>

<script>
import Cookies from "js-cookie";

export default {
	name: "Card",

	props: {
		car_id: String,
		name: String,
		type: String,
		price: String,
		car: Object,
		transferId: Number,

	},
	data() {
		return {currency: 'TRY'}
	},

	mounted() {
		this.currency = this.$route.query.currency
		this.$nuxt.$on('update-currency', (currencyCode) => {
			this.currency = currencyCode
		})

	},
	methods: {
		randomInteger(min, max) {
			return Math.floor(Math.random() * max) + min;
		},

		async setTransfer(car_id) {
			const currency = this.$route.query.currency;
			const transfer_data = await this.$store.getters['transfer/transfer_data'];

			await this.$store.dispatch('transfer/setBooking', {transfer_id: this.transferId, car_id, currency, return_trip: transfer_data.return_trip})
			const token = await this.$store.getters['transfer/bookingToken']

			await this.$router.push({
				path: this.$i18n.locale === 'tr' ? '/checkout' : '/' + this.$i18n.locale + '/checkout',
				query: {booking_token: token, car_id, transfer_id: this.transferId, currency}
			});
		}

	},
}
</script>

<style scoped>

</style>
