<template>
	<div class="form form-container">
		<div class="padding-form">
			<h5 class="form-title">
				{{ $t('checkout.paymentMethods.title') }}
			</h5>
			<div class="payments">
				<div class="radio-custom">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<input type="radio" @input="changePayment" v-model="paymentMethod" value="card" id="card_website"
							       name="radio-group">
							<label for="card_website">{{ $t('checkout.paymentMethods.by_card') }}</label>
						</div>
						<div class="icons d-flex">
							<i class="i-mastercard mr-3"></i>
							<i class="i-visa"></i>
						</div>
					</div>
				</div>
				<div class="radio-custom">
					<input type="radio" @input="changePayment" v-model="paymentMethod" value=cash id="cash" name="radio-group">
					<label for="cash"> {{ $t('checkout.paymentMethods.by_cash') }}
					</label>
				</div>
			</div>

			<div>
				<VCreditCard v-if="showCreditCard" @change="creditInfoChanged" :trans="$t('checkout.paymentMethods.card')" direction="row"/>
			</div>
		</div>
	</div>
</template>

<script>
import VCreditCard from 'v-credit-card';
import 'v-credit-card/dist/VCreditCard.css'

export default {
	name: "Payment",
	components: {
		VCreditCard
	},
	data() {
		return {
			showCreditCard: false,
			paymentMethod: '',
			translations: {},
			card: {
				name: '',
				cardNumber: '',
				expiration: '',
				security: ''
			}
		}
	},
	mounted() {

	},
	methods: {
		changePayment(event) {
			this.paymentMethod = event.target.value
			this.showCreditCard = this.paymentMethod === 'card';
			this.$emit('select-payment', {method: event.target.value, data: this.card})


		},
		creditInfoChanged(values) {
			for (const key in values) {
				this.card[key] = values[key];
				console.log(this.card[key])
			}
			this.$emit('select-payment', {method: 'card', data: this.card})

		}
	}

}
</script>


