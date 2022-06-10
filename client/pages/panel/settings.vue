<template>
	<div>
		<header-admin :breadcrumbs-list="breadcrumbs" :show-update="true" :page-title="pageTitle"
		              :update="update"></header-admin>
		<v-skeleton-loader class="main-box-container mt-7" :loading="loading" type="table">

			<v-form v-model="valid">

				<v-card class="shadow-none">
					<v-tabs
							v-model="tab"
							background-color="transparent"
							color="basil"
							grow
					>
						<v-tab

								v-for="(item, key) in items"
								:key="key"
						>
							{{ item.name }}
						</v-tab>
					</v-tabs>

					<v-tabs-items v-model="tab" class="mt-5">
						<v-tab-item
								v-for="(item, key) in items"
								:key="key"
						>
							<v-container>
								<v-card
										class="meta-information"
										color="basil"
										flat>
									<v-text-field
											v-model="item.meta_title"
											label="Meta title"
											required>

									</v-text-field>
									<v-text-field
											v-model="item.meta_description"
											label="Meta description"
											required
									></v-text-field>
									<v-text-field
											v-model="item.meta_keyword"
											label="Meta keyword"
											required
									></v-text-field>

								</v-card>
							</v-container>
						</v-tab-item>
					</v-tabs-items>

					<v-container>
						<div class="d-lg-flex align-items-center">

							<v-file-input
									accept="image/*"
									small-chips
									label="Site logo"
									v-model="logo_image"
									class="pr-2"

									@change="previewImage"
							></v-file-input>
							<v-img
									class="mx-auto"
									max-height="150"
									max-width="250"
									:src="logo_url"></v-img>

						</div>

					</v-container>
					<v-container>
						<v-row>
							<v-col cols="12" md="12">
								<v-text-field
										v-model="name"
										label="Şirket Adı"
										required
								></v-text-field>
								<v-text-field
										v-model="info.address"
										label="Adres"
										required
								></v-text-field>
								<v-text-field
										v-model="info.city"
										label="Sehir"
										required
								></v-text-field>
								<v-text-field
										v-model="info.country"
										label="Ulke"
										required
								></v-text-field>
								<v-text-field
										v-model="info.email"
										label="Email"
										required
								></v-text-field>
								<v-text-field
										v-model="info.maps"
										label="Maps"
										required
								></v-text-field>
								<v-text-field
										v-model="info.date"
										label="Şirketin kuruluş tarihi"
										required
								></v-text-field>
								<v-text-field
										v-model="info.instagram"
										label="İnstagram"
										required
								></v-text-field>
								<v-text-field
										v-model="info.facebook"
										label="Facebook"
										required
								></v-text-field>
								<button class="btn btn-primary" @click.prevent="update">Kaydet</button>
							</v-col>
						</v-row>
					</v-container>
				</v-card>
			</v-form>
		</v-skeleton-loader>
	</div>
</template>

<script>
import Breadcrumbs from "../../components/Breadcrumbs";
import MetaData from "../../components/admin/MetaData";
import HeaderAdmin from "../../components/admin/HeaderAdmin";
import {getMetaData} from "../../hooks/meta";
import {settingsProp} from "../../props/seetings.prop";

export default {
	name: "settings",
	layout: "admin",
	components: {HeaderAdmin, Breadcrumbs, MetaData},

	data() {

		return {
			valid: false,
			tab: null,
			title: '',
			meta: this.meta,
			name: "Lux Elit Travel",
			info: {...settingsProp},

			items: [
				{
					name: 'Turkish',
					meta_description: '',
					meta_title: '',
					meta_keyword: '',
				},
				{
					name: 'Russian',
					meta_description: '',
					meta_title: '',
					meta_keyword: '',
				},
				{
					name: 'English',
					meta_description: '',
					meta_title: '',
					meta_keyword: '',
				},
			],

			loading: true,

			breadcrumbs: [
				{
					text: this.$t('panel.dashboard'),
					disabled: false,
					href: '/' + this.$i18n.locale + '/panel',
				},
				{
					text: this.$t('panel.menu.settings'),
					disabled: true,
					href: '/' + this.$i18n.locale + '/settings',
				},
			],
			logo_url: null,
			logo_image: [],
			pageTitle: 'Edit system settings',
		}
	},

	middleware: ['admin', 'super_admin'],

	async asyncData({$axios, i18n}) {
		return getMetaData($axios, i18n, i18n.t('panel.menu.settings'), true)
	},

	head() {
		return {
			title: this.title,
			meta: this.meta
		}
	},
	async mounted() {
		await this.$store.dispatch('setting/GET_DATA', {key: 'meta', action: 'SET_DATA'})
		await this.$store.dispatch('setting/GET_DATA', {key: 'site_info', action: 'SET_DATA'})
		await this.$store.dispatch('setting/GET_DATA', {key: 'logo', action: 'SET_DATA'})
		const meta = await this.$store.getters['setting/GET_META'];
		this.name = await this.$store.getters['setting/GET_NAME'];
		this.info = await this.$store.getters['setting/GET_SITE_INFO'];
		if (this.info) {
			this.info = JSON.parse(JSON.stringify(this.info))
			this.name = this.info.name
		}
		if (meta['length'])
			this.items = JSON.parse(JSON.stringify(meta));
		this.logo_url = await this.$store.getters['setting/GET_LOGO']

		this.loading = false;

	},

	methods: {
		update() {
			this.$store.dispatch('setting/SET_DATA', {key: "meta", 'value': this.items})
			this.$store.dispatch('setting/SET_DATA', {key: "name", 'value': this.name})
			this.$store.dispatch('setting/SET_DATA', {key: "site_info", 'value': {...this.info, name: this.name}})
		},

		previewImage() {
			this.logo_url = URL.createObjectURL(this.logo_image)
			this.$store.dispatch('setting/UPLOAD_IMAGE', {
				key: 'logo',
				image: this.logo_image,
				action: 'SET_DATA'
			})
		}
		,
	}
}
</script>


