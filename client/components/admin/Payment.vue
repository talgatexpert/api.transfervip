<template>
	<div class="form form-container mt-5">
		<div class="padding-form">
			<h5 class="form-title">
				{{ $t('checkout.paymentMethods.title') }}
			</h5>
			<v-radio-group class="payments" @change="changePayment" v-model="paymentMethod">
				<div class="radio-custom">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<v-radio name="payment" class="w-100"
							         :label="$t('checkout.paymentMethods.by_card')" value="card"></v-radio>
						</div>
						<div class="icons d-flex">
							<i class="i-mastercard mr-3"></i>
							<i class="i-visa"></i>
						</div>
					</div>
				</div>
				<div class="radio-custom">
					<div>
						<v-radio  name="payment" :label="$t('checkout.paymentMethods.by_cash')"
						         value="cash"></v-radio>
					</div>
				</div>
			</v-radio-group>

			<div>
				<VCreditCard v-if="paymentMethod === 'card'"  @change="creditInfoChanged" :trans="$t('checkout.paymentMethods.card')"
				             direction="row"/>
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
		changePayment(method) {
			if (method === "cash")
				this.card = {
					name: '',
					cardNumber: '',
					expiration: '',
					security: ''
				}
			this.$emit('select-payment', {method: method, data: this.card})
		},
		creditInfoChanged(values) {
			for (const key in values) {
				this.card[key] = values[key];
			}
			this.$emit('select-payment', {method: 'card', data: this.card})
		}
	}

}
</script>


<style>
label {
	margin: 0;
}
</style>