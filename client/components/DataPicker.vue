<template>
	<div class="main-form">
		<div class="container">
			<h1 v-html="$t('header_title')" class="home-title"></h1>
			<form @submit.prevent="getTransfers" class="from-picker">
				<div class="form-group main-form__box main-form__box-bg">

					<div class="form-group__prefix">{{ $t('from') }}:</div>
					<label for="from">
						<input type="text" @input="findDestinationFrom" v-model="inputValueFrom"
						       :placeholder="$t('datapicker_from')"
						       id="from"

						       class="form-control">
					</label>

					<ul class="main-form__result" v-if="citiesFrom.length">
						<li class="main-form__result-item" @click="setCity" data-direction="from" :data-value="city.name"
						    v-for="city in citiesFrom" :data-slug="city.slug">
							{{ city.name }}
						</li>
					</ul>
				</div>
				<div class="form-group  main-form__separator main-form__box-bg">
					<div class="input-separator" @click="changeDirection">
						<img src="~/assets/img/separate.svg" alt="">
					</div>
				</div>

				<div class="form-group main-form__box main-form__box-bg main-form__to ">

					<div class="form-group__prefix">{{ $t('to') }}:</div>
					<label for="to">
						<input type="text" @input="findDestinationTo" v-model="inputValueTo" :placeholder="$t('datapicker_to')"
						       id="to"
						       class="form-control">
					</label>

					<ul class="main-form__result" v-if="citiesTo.length">
						<li class="main-form__result-item" @click="setCity" data-direction="to" :data-value="city.name"
						    v-for="city in citiesTo" :data-slug="city.slug">
							{{ city.name }}
						</li>
					</ul>
				</div>

				<div class="form-group">
					<button class="btn main-form__btn">Search</button>
				</div>

				<div class="custom-checkbox">

					<input type="checkbox" class="custom-checkbox__input" id="return" @change="changeReturn">
					<label for="return"><span>{{ $t('return_back') }}</span></label>

				</div>
			</form>
		</div>
	</div>


</template>

<script>
import Cookies from "js-cookie";
export default {
	name: "DataPicker",

	data() {
		return {
			to: 'to',
			from: 'from',
			citiesFrom: [],
			citiesTo: [],
			cityFromSlug: '',
			cityToSlug: '',
			inputValueTo: '',
			inputValueFrom: ''

		}
	},
	mounted() {
		if (!this.$route.params.to && !this.$route.params.from) {
			this.$store.dispatch('city/CLEAR_CITY_DATA');
		} else {
			this.inputValueFrom = this.$store.getters['city/getCityFrom'].name
			this.inputValueTo = this.$store.getters['city/getCityTo'].name
		}


	},


	methods: {
		changeDirection(event) {
			const cityFrom = this.$store.getters['city/getCityFrom']
			const cityTo = this.$store.getters['city/getCityTo']
			this.$store.dispatch('city/CHANGE_DIRECTION', {
				cityFrom, cityTo
			})
			this.$data.inputValueFrom = this.$store.getters['city/getCityFrom'].name
			this.$data.inputValueTo = this.$store.getters['city/getCityTo'].name
		},
		findDestinationTo(event) {
			const value = this.inputValueTo;
			let payload = {
				city: this.$data.inputValueFrom,
				search: value
			}
			this.$store.dispatch('city/LOAD_CITY', payload)
			this.$data.inputValueTo = value;
			if (value === "") {
				this.$data.citiesTo = [];
			} else {
				this.$data.citiesTo = this.$store.getters['city/getCities'];
			}
		},
		findDestinationFrom(event) {

			const value = this.inputValueFrom;
			let payload = {
				city: this.$data.inputValueTo,
				search: value
			}
			this.$store.dispatch('city/LOAD_CITY', payload)
			this.$data.inputValueFrom = value;
			if (value === "") {
				this.$data.citiesFrom = [];
			} else {
				this.$data.citiesFrom = this.$store.getters['city/getCities'];
			}

		},
		setCity(event) {

			if (event.target.dataset.direction === "from") {
				this.$data.inputValueFrom = event.target.dataset.value;
				this.$data.cityFromSlug = event.target.dataset.slug;
			} else if (event.target.dataset.direction === "to") {
				this.$data.inputValueTo = event.target.dataset.value;
				this.$data.cityToSlug = event.target.dataset.slug;

			}

			const savedData = {
				cityFrom: {
					name: this.$data.inputValueFrom,
					slug: this.$data.cityFromSlug,
				},
				cityTo: {
					name: this.$data.inputValueTo,
					slug: this.$data.cityToSlug,
				},
			}

			this.$store.dispatch('city/SET_DIRECTION', savedData);


			this.$data.citiesTo = [];
			this.$data.citiesFrom = [];

		},
		changeReturn(event) {

		},
		getTransfers(event) {
			let path = `/transfer/${this.$data.cityFromSlug}/${this.$data.cityToSlug}` + '?currency=' + Cookies.get('currency')  + '#transfers';
			const p = this.$i18n.locale === 'tr' ? path : '/' + this.$i18n.locale + path + '?currency=' + Cookies.get('currency')  + '#transfers';
			this.$router.push(p);
		}
	}
}
</script>

<style scoped>

</style>
