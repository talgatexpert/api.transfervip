<template>
	<main>
		<data-picker @update-cars="update"></data-picker>

		<div class="container" id="transfers">
			<section class="content-info">
				<h2 class="content-info__title text-center" v-if="itemsLength">{{ $t('search_transfer', {from, to}) }}</h2>

				<scale-loader :loading="loading"></scale-loader>

				<div v-for="(item, key) in items" v-if="items" :key="`bind_${key}`">
					<card v-for="(car, key) in item.cars" :car="car" :key="`car_${key}`" :transfer-id="item.id"></card>
				</div>
				<h2 class="content-info__title text-center" v-if="!itemsLength">{{ $t('not_found_transfers') }}</h2>


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
import {getMetaData} from "../hooks/meta";
import {useLocaleCode} from "../hooks/locale";

export default {
	name: "transfer",
	components: {DataPicker, Card, ScaleLoader},
	beforeCreate() {
		this.loading = true;
	},

	data() {
		return {
			title: 'Lux elit travel Transfer Company in Turkey',
			meta: [],
			from: "",
			to: "",
			cars: [],
			items: [],
			loading: true,
			itemsLength: 0,
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
	async asyncData({params, query, store, app, $axios, i18n}) {
		const language = useLocaleCode(i18n);
		await store.dispatch('transfer/get', {
			city_from: params.from,
			city_to: params.to,
			return_trip: query.return_trip,
			currency: query.currency,
			language
		});
		let data = await store.getters['transfer/transfers'];
		let from = data.from;
		let to = data.to;



		const meta = await getMetaData($axios, i18n)
		return {meta, items: data.items, from, to, loading: false, itemsLength: data.total }

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

		async update(payload) {
			this.loading = true;
			this.items = [];
			await this.$store.dispatch('transfer/get', {
				city_from: payload.cityFromSlug,
				city_to: payload.cityToSlug,
				return_trip: payload.return_trip,
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
