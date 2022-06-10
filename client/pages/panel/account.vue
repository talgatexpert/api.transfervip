<template>
	<div>
		<header-admin update="" :breadcrumbs-list="breadcrumbs"></header-admin>
		<v-skeleton-loader class="main-box-container mt-7" :loading="loading" type="table">
			<v-container>
				<v-col cols="12" md="6" sm="12">
					<v-card class="shadow-none">
						<v-text-field
								label="İsim"
								v-model="name"
								hide-details="auto"
								:error-messages="errors.name"
						></v-text-field>
						<v-text-field
								label="E-posta"
								disabled
								v-model="email"
								hide-details="auto"
						></v-text-field>
						<v-text-field
								label="Şifre"
								v-model="password"
								hide-details="auto"
								:append-icon="value  ? 'mdi-eye' : 'mdi-eye-off'"
								:type="value ? 'password' : 'text'"
								@click:append="() => (value = !value)"

								:error-messages="errors.password"

						></v-text-field>
						<v-text-field
								v-model="confirm_password"
								label="Şifreyi onayla"
								:error-messages="errors.confirm_password"
								hide-details="auto"
								:append-icon="value2  ? 'mdi-eye' : 'mdi-eye-off'"
								:type="value2 ? 'password' : 'text'"
								@click:append="() => (value2 = !value2)"
						></v-text-field>
						<v-btn class="mt-5  white--text" @click="update" color="blue-grey">Onayla</v-btn>
					</v-card>
				</v-col>
			</v-container>
		</v-skeleton-loader>
	</div>
</template>

<script>
import HeaderAdmin from "../../components/admin/HeaderAdmin";
import {getMetaData} from "../../hooks/meta";

export default {
	name: "Account",
	layout: "admin",
	components: {HeaderAdmin},
	async asyncData({$axios, i18n}) {
		return getMetaData($axios, i18n, i18n.t('panel.menu.account'), true)
	},
	head() {
		return {
			title: this.title,
			meta: this.meta
		}
	},
	data() {
		return {
			loading: true,
			user: '',
			breadcrumbs: [
				{
					text: this.$t('panel.dashboard'),
					disabled: false,
					href: '/' + this.$i18n.locale + '/panel',
				},
				{
					text:  this.$t('panel.menu.account'),
					disabled: false,
					href: '/' + this.$i18n.locale +  '/account',
				},
			],
			password: '',
			confirm_password: '',
			name: '',
			email: '',
			errors: '',
			value: '',
			value2: '',
		}
	},

	mounted() {
		this.loading = false
		const user = this.$auth.user;
		this.email = user.email;
		this.name = user.name;
	},
	methods: {
		async update() {
			// await this.$store.dispatch('panel/user/CLEAR_ERROR')
			await this.$store.dispatch('panel/user/UPDATE_LOGGED_USER', {
				name: this.name,
				password: this.password,
				confirm_password: this.confirm_password
			})
			this.errors = await this.$store.getters['panel/user/GET_ERRORS']
		},
	}
}
</script>

<style scoped>

</style>
