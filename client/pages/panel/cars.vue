<template>
	<div>
		<header-admin update="" :breadcrumbs-list="breadcrumbs" :pageTitle="pageTitle"></header-admin>
		<v-skeleton-loader class="main-box-container mt-7" :loading="loading" type="table">

			<v-data-table
					:headers="headers"
					:items="cars"
					:server-items-length="totalNumberOfItems"
					@update:options="paginate"
					sort-by="id"
					class="main-box-container"
			>
				<template v-slot:item.image="{ item }">
					<div class="p-2">
						<v-img :src="item.image" :alt="item.name" width="100px" height="100px"></v-img>
					</div>
				</template>
				<template v-slot:top>
					<v-toolbar
							flat
							class="main-box-container mt-6"
							extension-height="auto"
					>
						<v-row class="mt-3">
							<v-col cols="12" lg="4" md="8" sm="12">
								<v-toolbar-title>Araç listesi</v-toolbar-title>

								<v-divider
										class="mx-4"
										inset
										vertical
								></v-divider>
							</v-col>
							<v-col cols="12" lg="6" md="8" sm="8">
								<v-spacer></v-spacer>

								<v-text-field
										v-model="search"
										append-icon="mdi-magnify"
										label="Search"
										single-line
										@input="searchCompany"
										hide-details
								></v-text-field>
								<v-spacer></v-spacer>
							</v-col>
							<v-col cols="12" lg="2" md="4" sm="4">

								<v-dialog
										v-model="dialog"
										max-width="500px"
								>
									<template v-slot:activator="{ on, attrs }">
										<v-btn
												color="primary"
												dark
												class="mb-2"
												v-bind="attrs"
												v-on="on"
												@click="editItem(defaultItem)"
										>
											New Item
										</v-btn>
									</template>
									<v-card>
										<v-card-title>
											<span class="text-h5">{{ formTitle }}</span>
										</v-card-title>

										<v-card-text>
											<v-container>
												<v-row>

													<v-col
															cols="12"
															sm="12"
															md="12"
													>
														<v-text-field
																v-model="editedItem.name"
																placeholder="BMW"
																label="Araç ismi"
																:error-messages="errors.name"

														/>

														<v-text-field
																v-model="editedItem.model"
																label="Araç modeli"
																placeholder="X5"
																:error-messages="errors.model"

														/>
														<v-select
																:items="types"
																v-model="editedItem.car_type"
																item-value="name"
																return-object
																item-text="name"
																label="Araç Tipi"
																:error-messages="errors.type"
														></v-select>
														<v-text-field
																v-model="editedItem.person_quantity"
																label="Kişi sayısı"
																placeholder="4"
																:error-messages="errors.person_quantity"

														/>
														<v-text-field
																v-model="editedItem.baggage_quantity"
																label="Bagaj sayısı"
																placeholder="1"
																:error-messages="errors.baggage_quantity"

														/>
														<v-file-input
																label="Resim"
																accept="image/*"
																small-chips
																v-model="editedItem.image_url"
																@change="previewImage"
														></v-file-input>
														<v-img v-if="editedItem.image" :src="editedItem.image" :alt="editedItem.name" width="200px"
														       height="200px"></v-img>


														<v-checkbox
																class="label-0 mt-1"
																v-model="editedItem.active"
																label="Aktif"
														/>
													</v-col>

												</v-row>
											</v-container>
										</v-card-text>

										<v-card-actions>
											<v-spacer></v-spacer>
											<v-btn
													color="blue darken-1"
													text
													@click="close"
											>
												Cancel
											</v-btn>
											<v-btn
													color="blue darken-1"
													text
													@click="save"
											>
												Save
											</v-btn>
										</v-card-actions>
									</v-card>
								</v-dialog>
							</v-col>

						</v-row>
						<v-dialog v-model="dialogDelete" max-width="500px">
							<v-card>
								<v-card-title class="text-h5">Bu öğeyi silmek istediğinizden emin misiniz?</v-card-title>
								<v-card-actions>
									<v-spacer></v-spacer>
									<v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
									<v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
									<v-spacer></v-spacer>
								</v-card-actions>
							</v-card>
						</v-dialog>
					</v-toolbar>
				</template>
				<template v-slot:item.actions="{ item }">
					<v-icon
							small
							class="mr-2"
							@click="editItem(item)"
					>
						mdi-pencil
					</v-icon>
					<v-icon
							small
							@click="deleteItem(item)"
					>
						mdi-delete
					</v-icon>
				</template>
				<template v-slot:no-data>
					<v-btn
							color="primary"
							@click="initialize"
					>
						Reset
					</v-btn>
				</template>

			</v-data-table>
		</v-skeleton-loader>
	</div>
</template>

<script>
import HeaderAdmin from "../../components/admin/HeaderAdmin";
import login from "../login";
import {getMetaData} from "../../hooks/meta";

export default {
	name: "cars",
	components: {HeaderAdmin},
	layout: "admin",
	data() {
		return {
			loading: true,
			breadcrumbs: [
				{
					text: this.$t('panel.dashboard'),
					disabled: false,
					href: '/' + this.$i18n.locale + '/panel',
				},
				{
					text: this.$t('panel.menu.cars'),
					disabled: true,
					href:'/' + this.$i18n.locale +   '/cars',
				},
			],
			pageTitle: 'Araçlari  düzenle',
			dialog: false,
			dialogDelete: false,
			types: [
				{
					name: 'Comfort'
				},
				{
					name: 'Economy'
				},
				{
					name: 'Business'
				},
				{
					name: 'Premium Minibus'
				},
				{
					name: 'Minivan'
				},
			],
			headers: [
				{
					text: '№',
					align: 'start',
					sortable: true,
					value: 'id',
				},
				{text: 'Araç ismi', value: 'name'},
				{text: 'Araç modeli', value: 'model'},
				{text: 'Araç tipi', value: 'type'},
				{text: 'kişi sayısı', value: 'person_quantity'},
				{text: 'Bagaj sayısı', value: 'baggage_quantity'},
				{text: 'Resim', value: 'image'},
				{text: 'Aktif', value: 'active'},
				{text: 'Actions', value: 'actions', sortable: false},

			],
			cars: [
				{
					id: 1,
					name: "Mazda",
					model: 'RX 7',
					type: 'mini bus',
					person_quantity: 7,
					baggage_quantity: 8,

				}
			],
			editedIndex: -1,
			show1: false,
			editedItem: {
				id: '',
				name: '',
				model: '',
				type: '',
				person_quantity: '',
				baggage_quantity: '',
				company_id: '',
				user_id: '',
				car_type: '',
				image: '',
				image_url: [],
				active: false,
			},
			roles: [],
			defaultItem: {
				id: false,
				name: '',
				model: '',
				type: '',
				image: '',
				car_type: '',
				image_url: [],
				person_quantity: '',
				baggage_quantity: '',
				company_id: '',
				user_id: '',
				active: false,

			},

			search: '',
			pagination:
					{
						descending: !!this.$route.query.desc,
						sortBy: this.$route.query.orderby || 'id',
						rowsPerPage: +this.$route.query.limit || 10,
						page: +this.$route.query.page || 1,
						totalItems: 0,
						listSize: [10, 25, 50, 100],
					},
			totalNumberOfItems: 0,
			listSize: [10, 25, 50, 100],
			errors: [],


		}
	},

	async asyncData({$axios, i18n}) {
		return getMetaData($axios, i18n, i18n.t('panel.menu.cars'), true)
	},

	head() {
		return {
			title: this.title,
			meta: this.meta
		}
	},


	computed: {
		formTitle() {
			return this.editedIndex === -1 ? 'Aracları  ekle' : 'Aracı  düzenle'
		},
	},

	watch: {
		dialog(val) {
			val || this.close()
		},
		dialogDelete(val) {
			val || this.closeDelete()
		},
	},


	async mounted() {
		await this.$store.dispatch('panel/car/GET_CARS', '?page=1&limit=10');
		this.cars = await this.$store.getters['panel/car/GET_CARS']
		this.totalNumberOfItems = await this.$store.getters['panel/car/GET_TOTAL']
		this.loading = false;
	},

	async beforeRouteUpdate(to, from, next) {
		await this.fetchData(to);
		next();
	},
	methods: {
		async initialize() {
			await this.$store.dispatch('panel/car/GET_CARS', '?page=1&limit=10')
			this.cars = await this.$store.getters['panel/car/GET_CARS']
		},

		async searchCompany() {
			const value = this.search.trim();
			let params = this.buildQuery() + '&search=' + value;

			if (value) {
				await this.$store.dispatch('panel/car/SEARCH_CAR', params);
				this.$data.cars = await this.$store.getters['panel/car/GET_CARS']
				this.$data.totalNumberOfItems = await this.$store.getters['panel/car/GET_TOTAL']
			} else {
				await this.$store.dispatch('panel/car/GET_CARS', '?page=1&limit=10&orderby=id');
				this.$data.cars = await this.$store.getters['panel/car/GET_CARS']
				this.$data.totalNumberOfItems = await this.$store.getters['panel/car/GET_TOTAL']
			}
		},

		buildQuery(route) {
			const query = route ? route.query : this.$route.query;
			const paginate = this.pagination;
			const page = query.page ?? 1;
			const sortBy = query.sort ?? "ASC";
			let perPage = query.limit ?? 10;
			const orderBy = query.orderby ?? 'id';

			return '?limit=' + perPage + '&orderby=' + orderBy + '&page=' + page + '&sort=' + sortBy;
		},

		async fetchData(route = null) {
			await this.$store.dispatch('panel/car/GET_CARS', this.buildQuery(route));
			this.$data.cars = await this.$store.getters['panel/car/GET_CARS']
			this.$data.totalNumberOfItems = await this.$store.getters['panel/car/GET_TOTAL']
		},

		editItem(item) {
			this.editedIndex = this.cars.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.dialog = true

			if (this.editedItem.active === false || this.editedItem.active == "Yok") {
				this.editedItem.active = false
			}

			console.log(item)
		},

		deleteItem(item) {
			this.editedIndex = this.cars.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.dialogDelete = true
		},

		async deleteItemConfirm() {
			await this.$store.dispatch('panel/car/DELETE_CAR', {id: this.editedItem.id});
			this.$data.cars = await this.$store.getters['panel/car/GET_CARS']
			this.$data.totalNumberOfItems = await this.$store.getters['panel/car/GET_TOTAL']
			this.closeDelete()
		},

		async close() {
			this.dialog = false
			await this.$store.dispatch('panel/car/CLEAR_ERROR')
			this.errors = await this.$store.getters['panel/car/GET_ERRORS'];

			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem)
				this.editedIndex = -1
			})
		},

		closeDelete() {
			this.dialogDelete = false
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem)
				this.editedIndex = -1
			})
		},

		async save() {
			await this.$store.dispatch('panel/car/CLEAR_ERROR')
			let active = this.editedItem.active;
			if (this.editedItem.id) {


				await this.$store.dispatch('panel/car/UPDATE_CAR', {
					id: this.editedItem.id,
					name: this.editedItem.name,
					type: this.editedItem.car_type.name,
					model: this.editedItem.model,
					person_quantity: this.editedItem.person_quantity,
					baggage_quantity: this.editedItem.baggage_quantity,
					company_id: this.editedItem.company_id,
					user_id: this.editedItem.user_id,
					image: this.editedItem.image,
					active: active,
					new_image: this.editedItem.image_url,

				});
			} else {
				await this.$store.dispatch('panel/car/CREATE_CAR', {
					name: this.editedItem.name,
					type: this.editedItem.car_type.name,
					model: this.editedItem.model,
					person_quantity: this.editedItem.person_quantity,
					baggage_quantity: this.editedItem.baggage_quantity,
					company_id: this.editedItem.company_id,
					user_id: this.editedItem.user_id,
					image: this.editedItem.image,
					active: active,
					new_image: this.editedItem.image_url,

				});
			}
			this.search = '';
			this.cars = await this.$store.getters['panel/car/GET_CARS'];
			let error = await this.$store.getters['panel/car/GET_ERRORS'];
			const checkOwnProperty = (obj, propertyName) => (obj && (typeof obj == "object" || typeof obj == "function") && Object.prototype.hasOwnProperty.call(obj, propertyName));
			if (error === undefined) {
				error = [];
			}
			this.errors = error;
			if (checkOwnProperty(error, 'length') && error.length === 0) {
				await this.close();
			}
		},
		async paginate(val) {

			// emitted by the data-table when changing page, rows per page, or the sorted column/direction - will be also immediately emitted after the component was created
			const query = this.$route.query;
			const obj = Object.assign({}, query);
			obj.limit = val.itemsPerPage === -1 ? 'all' : val.itemsPerPage;
			if (val.descending) obj.desc = 'true';
			else delete obj.desc;
			obj.orderby = val.sortBy;


			obj.page = val.page;
			if (val.sortDesc[0] === false) {
				obj.sort = "ASC"
			} else {
				obj.sort = "DESC"
			}


			// check if old and new query are the same - VueRouter will not change the route if they are, so probably this is the first page loading

			await this.$router.replace({...this.$router.currentRoute, query: obj}).catch((error) => {
				console.log('CARS ERROR')
			});
		},

		previewImage() {
			this.editedItem.image = URL.createObjectURL(this.editedItem.image_url)
		},
	},
}
</script>
