<template>
	<div class="v-booking-card" @click="setTransfer(car)">
		<div class="v-booking-card__picture border-card">
			<div class="v-booking-card__picture--thumb">
				<img :src="car.image" :alt="car.type">
			</div>
			<div class="v-booking-card__picture--name">
				{{ car.full_name }}
			</div>


		</div>
		<div class="v-booking-card__description">
			<div class="v-booking-card__description--class">
				{{ car.type }}
			</div>
			<div class="v-booking-card__description--baggage">
				<span class="item-icon"><i class="icon i-person"></i><span>{{ car.person_quantity }}</span></span>
				<span class="item-icon"><i class="icon i-baggage"></i><span>{{ car.baggage_quantity }} </span></span>
			</div>
			<div class="v-booking-card__description--services">

				<span class="item-icon"> <i class="icon i-close"></i> <span>Free cancellation</span></span>
				<span class="item-icon"> <i class="icon i-time"></i> <span>15 min of waiting included</span></span>
				<span class="item-icon"> <i class="icon i-time"></i> <span>15 min of waiting included</span></span>
				<span class="item-icon" v-if="$auth.user.hidden_role === 'travel'  || $auth.user.hidden_role === 'admin' || $auth.user.hidden_role === 'super_admin' "><v-icon class="icon" color="#3AC559">mdi-ticket-percent</v-icon> <span>Agency tax {{car.agency_tax}} {{car.currency}} </span></span>
				<span class="item-icon"  v-if="$auth.user.hidden_role === 'travel' || $auth.user.hidden_role === 'admin' || $auth.user.hidden_role === 'super_admin' || $auth.user.hidden_role === 'company'"><v-icon class="icon" color="#3AC559">mdi-percent</v-icon> <span>Company tax {{car.company_tax}} {{car.currency}}</span></span>
			</div>
		</div>
		<div class="v-booking-card__offer w-100 pt-5 pb-5">
			<div class="d-flex align-items-center mb-5 ">
				<div class="v-booking-card__offer--price">
					{{ car.price }} {{ car.currency }}
				</div>
				<div class="v-booking-card__offer--payments">
					<i class="icon i-cache"></i>
					<i class="icon i-mastercard"></i>
					<i class="icon i-visa"></i>
				</div>
			</div>

			<button type="button" class="btn btn-green" @click="setTransfer(car)">Select</button>
		</div>
	</div>

</template>

<script>
export default {
	name: "bookingCar",
	props: {
		car: Object,
		currency: {
			type: String,
			default: 'TRY'
		}
	},
	methods: {
		setTransfer(car) {
			this.$emit('change', car)
		}
	}
}
</script>

<style scoped lang="scss">
@mixin mobile {
	@media screen and (max-width: 576px) {
		@content;
	}
}

@mixin tablet {
	@media screen and (max-width: 768px) {
		@content;
	}
}

@mixin netbook {
	@media screen and (max-width: 992px) {
		@content;
	}
}

@mixin laptop {
	@media screen and (max-width: 1200px) {
		@content;
	}
}

$dark: #000;

.v-booking-card {
	display: flex;
	min-width: 288px;
	cursor: pointer;
	//box-shadow: 0 1px 20px rgb(136 136 136 / 30%);
	border-radius: 8px;
	background-color: #fff;
	overflow: hidden;
	flex-wrap: wrap;
	flex-direction: column;
	width: 100%;


	.icon {
		margin-right: 0.5rem;
	}

	.border-card {
		position: relative;

		&:after {
			display: none;

			content: '';
			position: absolute;
			z-index: 2;
			top: 24px;
			bottom: 20px;
			right: 0;
			width: 1.4px;
			background-color: #f2f2f2;
		}
	}

	.item-icon {
		display: flex;
		align-items: center;
		padding: 0 5px 10px;
	}

	&__picture, &__description, &__offer {
		//width: 33.33%;
		width: 100%;

	}

	&__picture {
		display: flex;
		flex-direction: column;
		justify-content: center;
		padding: 50px 50px 0;
		position: relative;

		@include mobile {
			padding: 20px;
		}

		&--name {
			text-align: center;
			font-weight: 600;
		}


		&--thumb {
			display: flex;
			justify-content: center;

			img {
				max-width: 200px;
			}
		}

	}

	&__description {
		padding: 10px 50px 10px;
		text-align: center;
		@include mobile {
			padding: 20px;
			text-align: center;
		}


		&--class {
			margin-bottom: 14px;
			font-size: 25px;
			font-weight: 700;
			color: $dark;
			width: 100%;
		}

		&--baggage {
			display: flex;
			justify-content: center;
			align-items: center;
			margin: 0 -5px;
			padding-bottom: 0.7rem;
			width: 100%;

		}

		&--services {
			margin: 0 -5px;
			padding-bottom: 1rem;
			width: 100%;
			display: flex;
			flex-direction: column;

		}
	}

	&__offer {
		background: #d2dae2;
		display: flex;
		flex-grow: 1 !important;
		justify-content: center;
		align-items: center;
		flex-direction: column;

		@include mobile {
			padding: 50px 0;
		}

		&--price {
			font-weight: 700;
			font-size: 25px;
			margin-right: 1rem;
		}

		&--payments {
			display: flex;
			align-items: center;
		}
	}
}

</style>