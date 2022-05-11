<template>
	<main>
		<data-picker @update-cars="update"></data-picker>

		<div class="container" id="transfers">
			<section class="content-info">
				<h2 class="content-info__title text-center" v-if="itemsLength.length">{{ $t('search_transfer', {from, to}) }}</h2>

				<scale-loader :loading="loading"></scale-loader>

				<div v-for="(item, key) in items" v-if="items" :key="`bind_${key}`">
					<card v-for="(car, key) in item.cars" :car="car" :key="`car_${key}`" :transfer-id="item.id"></card>
				</div>
				<h2 class="content-info__title text-center" v-if="!itemsLength.length">{{ $t('not_found_transfers') }}</h2>


			</section>
		</div>
	</main>
</template>

<script>
import DataPicker from "../components/DataPicker";
import Card from "../components/Card";
import ScaleLoader from 'vue-spinner/src/ScaleLoader.vue'
import Cookies from "js-cookie";
import login from "./login";

export default {
	name: "transfer",
	components: {DataPicker, Card, ScaleLoader},
	beforeCreate() {
		this.loading = true;
	},
	data() {
		return {
			from: "",
			to: "",
			cars: [],
			items: [],
			loading: true,
			itemsLength: [],
			color: 'red',
		}
	},
	head() {
		return {
			htmlAttrs: {
				lang: this.$i18n.locale
			}
		}
	},
	async asyncData({params, query, store, app}) {

		await store.dispatch('transfer/get', {
			city_from: params.from,
			city_to: params.to,
			return_trip: query.return_trip,
			currency: query.currency
		});
		const data = await store.getters['transfer/transfers'];
		let from = '';
		let to = '';
		if (data.items?.length > 0) {
			if (app.i18n.locale === "tr") {
				from = data?.items[0]['start_city']['translations']['turkish']
				to = data.items[0]['end_city']['translations']['turkish']
			} else if (app.i18n.locale === "ru") {
				from = data.items[0]['start_city']['translations']['russian']
				to = data.items[0]['end_city']['translations']['russian']
			} else if (app.i18n.locale === "en") {
				from = data.items[0]['start_city']['translations']['english']
				to = data.items[0]['end_city']['translations']['english']
			}
		}
		return {items: data.items, from, to, loading: false, itemsLength: JSON.parse(JSON.stringify(data.items))}

	},

	mounted() {
		this.$nuxt.$on('update-currency', async (currencyCode) => {
			this.loading = true;
			this.items = [];
			await this.$store.dispatch('transfer/get', {
				city_from: this.$route.params.from,
				city_to: this.$route.params.to,
				currency: currencyCode
			});
			const data = await this.$store.getters['transfer/transfers'];
			this.loading = false;
			this.items = await data.items
		})
		this.loading = false;

	},

	methods: {

		async update() {
			this.loading = true;
			this.items = [];
			await this.$store.dispatch('transfer/get', {
				city_from: this.$route.params.from,
				city_to: this.$route.params.to,
				return_trip: this.$route.query.return_trip,
				currency: this.$route.query.currency
			});
			const data = await this.$store.getters['transfer/transfers'];
			this.loading = false;
			this.items = await data.items

			console.log('sass')
		}
	}


}

</script>

<style scoped>

</style>
