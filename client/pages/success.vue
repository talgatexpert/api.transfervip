<template>
	<section class="checkout">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="content-info">
						<div class="form form-container">
							<div class="padding-form">
								<h4 class="form-title text-center">{{ $t('success.title') }}</h4>
							</div>
							<div class="row m-0">
								<div class="success-data offset-lg-2 col-lg-8 col-md-12">
									<div class="success-data__row">
										<div class="data-name">{{ $t('success.name_surname') }}</div>
										<div class="data-value">{{ form.name }}</div>
									</div>
									<div class="success-data__row">
										<div class="data-name">{{ $t('success.address') }}</div>
										<div class="data-value">{{ form.address }}</div>
									</div>
									<div class="success-data__row">
										<div class="data-name">{{ $t('success.flight_number') }}</div>
										<div class="data-value">{{ form.flight_number }}</div>
									</div>
									<div class="success-data__row">
										<div class="data-name">{{ $t('success.email') }}</div>
										<div class="data-value">{{ form.email }}</div>
									</div>
									<div class="success-data__row">
										<div class="data-name">{{ $t('success.phone') }}</div>
										<div class="data-value">{{ form.phone }}</div>
									</div>
									<div class="success-data__row">
										<div class="data-name">{{ $t('success.passengers') }}</div>
										<div class="data-value">{{ form.passengers_number }}</div>
									</div>
									<div class="success-data__row" v-if="form.add_child_seat === 1">
										<div class="data-name">{{ $t('success.child_seat') }}</div>
										<div class="data-value">{{ $t('success.yes') }}</div>
									</div>
									<div class="success-data__row">
										<div class="data-name">{{ $t('success.payment_method') }}</div>
										<div class="data-value" v-if="'cash' === form.pay_type">{{ $t('success.cash') }}</div>
										<div class="data-value" v-else>{{ $t('success.card') }}</div>
									</div>
									<div class="success-data__row">
										<div class="data-name">{{ $t('success.return_trip') }}</div>
										<div class="data-value" v-if="form.return_trip">{{ $t('success.yes') }}</div>
										<div class="data-value" v-else>{{ $t('success.no') }}</div>
									</div>
									<div class="success-data__row">
										<div class="data-name">{{ $t('success.price') }}</div>
										<div class="data-value">{{ form.price_with_currency }}</div>
									</div>


									<div v-if="form.return_trip">
										<div class="success-data__row">
											<div class="data-name">{{ $t('success.return_trip_date') }}</div>
											<div class="data-value">{{ form.return_booking.started_at }}</div>
										</div>
										<div class="success-data__row">
											<div class="data-name">{{ $t('success.return_trip_time') }}</div>
											<div class="data-value">{{ form.return_booking.started_at_time }}</div>
										</div>

										<div class="success-data__row">
											<div class="data-name">{{ $t('success.return_trip_address') }}</div>
											<div class="data-value">{{ form.return_booking.address }}</div>
										</div>
										<div class="success-data__row">
											<div class="data-name">{{ $t('success.price') }}</div>
											<div class="data-value">{{ form.return_price_with_currency }}</div>
										</div>

									</div>
									<div class="success-data__row ">
										<div class="data-name font-weight-bold">{{ $t('success.total') }}</div>
										<div class="data-value font-weight-bold">{{ form.total_with_currency }}</div>
									</div>
								</div>

								<div class="col-lg-8 offset-lg-2 col-md-12">
									<button class="btn btn-green noprint" @click="print">{{ $t('success.print') }}</button>
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
export default {
	name: "success.vue",
	data() {
		return {
			form: {},
		}
	},
	methods: {
		print() {
			window.print();
		},
	},
	async asyncData({app, query}) {
		await app.store.dispatch('transfer/getConfirmBooking', {booking_token: query.booking_token,});
		const form = await app.store.getters['transfer/confirmBooking']

		return {form}
	},
	mounted() {
		window.addEventListener('popstate', function (event) {
			history.pushState(null, document.title, location.href);
		});
	}
}
</script>

<style scoped>

</style>
