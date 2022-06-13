<template>
	<div class="main-form">
		<div class="container">
			<h1 v-html="$t('header_title')" class="home-title"></h1>
			<form @submit.prevent="getTransfers" class="from-picker">
				<div class="form-group main-form__box main-form__box-bg">

					<div class="form-group__prefix">{{ $t('from') }}:</div>
					<label for="from">
						<input type="text" @input="findDestinationFrom" @focus="clear" v-model="inputValueFrom"
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
						<input type="text" @input="findDestinationTo" @focus="clear" v-model="inputValueTo"
						       :placeholder="$t('datapicker_to')"
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

					<input type="checkbox" class="custom-checkbox__input" id="return" v-model="return_trip">
					<label for="return"><span>{{ $t('return_back') }}</span></label>

				</div>
			</form>
		</div>
	</div>


</template>

<script>
import Cookies from "js-cookie";
import login from "../pages/login";
import {useLocale, useLocaleCode} from "../hooks/locale";

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
			inputValueFrom: '',
			return_trip: false,

		}
	},
	async mounted() {
		if (!this.$route.params.to && !this.$route.params.from) {
			this.$store.dispatch('city/CLEAR_CITY_DATA');
		} else {
			this.inputValueFrom = this.$store.getters['city/getCityFrom'].name
			this.inputValueTo = this.$store.getters['city/getCityTo'].name
		}

		const data = await this.$store.getters['transfer/transfer_data']
		if (data) {
			this.return_trip = data.return_trip
			this.inputValueTo = data.inputValueTo
			this.inputValueFrom = data.inputValueFrom
		}
	},
	async asyncData({store, i18n}) {
		const language = useLocaleCode(i18n);
		await store.dispatch('city/LOAD_CITY', {search: null, language, city: null})
	},

	methods: {
		async changeDirection(event) {
			const cityFrom = this.$store.getters['city/getCityFrom']
			const cityTo = this.$store.getters['city/getCityTo']
			await this.$store.dispatch('city/CHANGE_DIRECTION', {
				cityFrom, cityTo
			})
			this.inputValueFrom = this.$store.getters['city/getCityFrom'].name
			this.inputValueTo = this.$store.getters['city/getCityTo'].name
		},
		async clear() {
			await this.$store.dispatch('city/CLEAR_CITIES')
		},

		async findDestinationTo(event) {
			const value = this.inputValueTo;
			let payload = {
				city: this.inputValueFrom,
				search: value,
				language: useLocaleCode(this.$i18n)
			}
			await this.$store.dispatch('city/LOAD_CITY', payload)
			this.inputValueTo = value;
			if (value === "") {
				this.citiesTo = [];
			} else {
				this.citiesTo = await this.$store.getters['city/getCities'];
			}
		},
		findDestinationFrom(event) {

			const value = this.inputValueFrom;
			let payload = {
				city: this.inputValueTo,
				search: value,
				language: useLocaleCode(this.$i18n)
			}
			this.$store.dispatch('city/LOAD_CITY', payload)
			this.inputValueFrom = value;
			if (value === "") {
				this.citiesFrom = [];
			} else {
				this.citiesFrom = this.$store.getters['city/getCities'];
			}

		},
		setCity(event) {

			if (event.target.dataset.direction === "from") {
				this.inputValueFrom = event.target.dataset.value;
				this.cityFromSlug = event.target.dataset.slug;
			} else if (event.target.dataset.direction === "to") {
				this.inputValueTo = event.target.dataset.value;
				this.cityToSlug = event.target.dataset.slug;

			}

			const savedData = {
				cityFrom: {
					name: this.inputValueFrom,
					slug: this.cityFromSlug,
				},
				cityTo: {
					name: this.inputValueTo,
					slug: this.cityToSlug,
				},
			}

			this.$store.dispatch('city/SET_DIRECTION', savedData);


			this.citiesTo = [];
			this.citiesFrom = [];

		},

		async getTransfers() {

			await this.$store.dispatch('city/GET_CITY', {city: this.inputValueFrom, start: true, language: useLocaleCode(this.$i18n)})
			await this.$store.dispatch('city/GET_CITY', {city: this.inputValueTo, start: false,  language: useLocaleCode(this.$i18n)})
			const startCity = await this.$store.getters["city/getStartCity"];
			const endCity = await this.$store.getters["city/getEndCity"];
			const payload = {
				cityFromSlug: startCity.slug,
				cityToSlug: endCity.slug,
				inputValueFrom: this.inputValueFrom,
				inputValueTo: this.inputValueTo,
				return_trip: this.return_trip,
			};
			await this.$store.dispatch('transfer/setTransferData', payload);
			await this.$router.push({
				path: `/${this.$i18n.locale}/transfer/${startCity.slug}/${endCity.slug}`,
				query: {
					currency: this.$cookies.get('currency'),
					return_trip: this.return_trip,
				},
				hash: 'transfers'
			});
			await this.$emit('update-cars', payload)
		},


	},
}
</script>

<style scoped>

</style>
