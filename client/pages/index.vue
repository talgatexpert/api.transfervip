<template>

	<main>
		<data-picker></data-picker>

		<privileges></privileges>
		<how-work :howWorkText="howWorkText"></how-work>
		<section class="reviews bg-dark-blue content-info">
			<div class="container">
				<h2 class="text-center">Customers experience</h2>
				<div class="reviews__subtitle">Transfer has an average rating 4.82 of 5 based on 1171 reviews from our
					customers
				</div>
				<div class="reviews-slider">
					<slider :swiper-options="swiperOptions" :reviews="reviews"></slider>

				</div>
			</div>
		</section>
	</main>
</template>

<script>
import Vue from 'vue'
import Privileges from "~/components/Privileges.vue";
import HowWork from "~/components/HowWork";
import {getMetaData, useMeta, useTitle} from "../hooks/meta";
import 'swiper/css/swiper.css'
import Slider from "../components/Slider";
import DataPicker from "../components/DataPicker";

export default Vue.extend({
	name: 'IndexPage',
	components: {
		DataPicker,
		Slider, HowWork, Privileges,

	},

	async asyncData({$axios, i18n}) {
		return getMetaData($axios, i18n)
	},

	head() {
		return {
			title: this.title,
			meta: this.meta
		}
	},


	data() {
		return {
			title: 'Lux elit travel Transfer Company in Turkey',
			meta: [],
			swiperOptions: {
				slidesPerView: 3,
				spaceBetween: 20,

				breakpoints: {
					576: {
						slidesPerView: 1,
					},
					320: {
						slidesPerView: 1,
					},
					768: {
						slidesPerView: 2,
					}
				}
			},
		}
	},

	methods: {},
	computed: {
		howWorkText: (context) => {
			return context.$store.getters['getHowWorkText'];
		},

		reviews(context) {
			return context.$store.getters['review/getReviews'];
		},


	},

	created() {
		this.$store.dispatch('howWorkText', {})
		this.$store.dispatch('review/LOAD_REVIEWS', {})
	},
})
</script>
