<template>
	<div class="main-box-container mt-6">
		<v-container>
			<v-row>
				<v-col cols="12" md="8" sm="8">
					<breadcrumbs color-white="color-white" :items="breadcrumbsList" class="pl-0 "></breadcrumbs>
				</v-col>
				<v-col cols="12" md="4" sm="4" class="d-flex align-items-center justify-content-end">
					<language-switcher></language-switcher>
					<v-badge
							bordered
							color="error"
							:content="bookings"
							overlap
							:value="show"
					>
						<v-btn-toggle>
							<v-icon>
								mdi-bell
							</v-icon>
						</v-btn-toggle>
					</v-badge>
					<v-chip
							class="transparent ml-3 rounded-pill py-6  v-chip--clickable"
							pill

					>

						{{ username }}
						<v-avatar right>
							<v-img src="https://cdn.vuetifyjs.com/images/john.png"></v-img>
						</v-avatar>
					</v-chip>
				</v-col>

			</v-row>
		</v-container>

	</div>
</template>

<script>
import Breadcrumbs from "../Breadcrumbs";
import {BOOKINGS_DETAIL_URL} from "../../routes/panel";
import LanguageSwitcher from "./languageSwitcher";

export default {
	name: "HeaderAdmin",
	props: {
		update: Function | String | Boolean,
		breadcrumbsList: Array,
		pageTitle: String,
		showUpdate: Boolean | false,
		count: Number,
	},

	data() {
		return {
			bookings: '',
			show: false,
		}
	},
	components: {LanguageSwitcher, Breadcrumbs},

	watch: {
		count(val){

			this.bookings = val
			if (val === 0){
				this.show = false
			}else{
				this.show = true
			}
		}
	},



	computed: {
		username() {
			return  this.$t('panel.hello') + " " + this.$auth.user.name
		},

	},
	async mounted() {

		await this.$store.dispatch('panel/bookings/GET_NOT_ACCEPTED_BOOKINGS');
		this.bookings = this.$store.getters['panel/bookings/GET_COUNT']
		if (this.bookings > 0){
			this.show = true
		}else{
			this.show = false
		}
	},

}
</script>

<style scoped>

</style>
