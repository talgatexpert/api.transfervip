<template>
	<div>
		<header-admin update="" :breadcrumbs-list="breadcrumbs" :pageTitle="pageTitle"></header-admin>
		<v-skeleton-loader class="main-box-container mt-7" :loading="loading" type="table">
			<v-data-table
					:headers="headers"
					:items="cities"
					:server-items-length="totalNumberOfItems"
					@update:options="paginate"
					sort-by="turkish"

			>
				<template v-slot:top>
					<v-toolbar
							flat
							class="main-box-container mt-6 pt-5"

					>
						<v-toolbar-title>Şehirler listesi</v-toolbar-title>
						<v-spacer></v-spacer>
						<v-text-field
								v-model="search"
								append-icon="mdi-magnify"
								label="Search"
								single-line
								@input="searchCity"
								hide-details
						></v-text-field>
						<v-spacer></v-spacer>
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
													sm="6"
													md="4"
											>
												<v-text-field
														v-model="editedItem.turkish"
														placeholder="Antalya"
														label="Turkish"
												></v-text-field>
											</v-col>
											<v-col
													cols="12"
													sm="6"
													md="4"
											>
												<v-text-field
														v-model="editedItem.russian"
														label="Russian"
														placeholder="Анталья"

												></v-text-field>
											</v-col>
											<v-col
													cols="12"
													sm="6"
													md="4"
											>
												<v-text-field
														v-model="editedItem.english"
														label="English"
														placeholder="Antalya"

												></v-text-field>
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
						<v-dialog v-model="dialogDelete" max-width="500px">
							<v-card>
								<v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
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
import {getMetaData} from "../../hooks/meta";



export default {
	name: "cities",
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
					text: this.$t('panel.menu.cities'),
					disabled: true,
					href:'/' + this.$i18n.locale +   '/cities',
				},
			],
			pageTitle: 'Edit cities',
			dialog: false,
			dialogDelete: false,
			headers: [
				{
					text: '№',
					align: 'start',
					sortable: true,
					value: 'id',
				},
				{text: 'Turkish', value: 'turkish'},
				{text: 'Russian', value: 'russian'},
				{text: 'English', value: 'english'},
				{text: 'Actions', value: 'actions', sortable: false},
			],
			cities: [],
			editedIndex: -1,
			editedItem: {
				id: '',
				turkish: '',
				russian: '',
				english: '',
			},
			defaultItem: {
				id: false,
				turkish: '',
				russian: '',
				english: '',
			},
			search: '',
			pagination:
					{
						descending: !!this.$route.query.desc,
						sortBy: this.$route.query.orderby || 'turkish',
						rowsPerPage: +this.$route.query.limit || 10,
						page: +this.$route.query.page || 1,
						totalItems: 0,
						listSize: [10, 25, 50, 100],
					},
			totalNumberOfItems: 0,
			listSize: [10, 25, 50, 100],
		}
	},

	async asyncData({$axios, i18n}) {
		return getMetaData($axios, i18n, i18n.t('panel.menu.cities'), true)
	},
	head() {
		return {
			title: this.title,
			meta: this.meta
		}
	},


	computed: {
		formTitle() {
			return this.editedIndex === -1 ? 'Şehir ekle' : 'Şehri düzenle'
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
		await this.$store.dispatch('panel/city/GET_CITIES', '?page=1&limit=10&orderby=turkish');
		this.cities = await this.$store.getters['panel/city/GET_CITIES']
		this.totalNumberOfItems = await this.$store.getters['panel/city/GET_TOTAL']
		this.loading = false;
	},



	async beforeRouteUpdate(to, from, next) {
		await this.fetchData(to);
		next();
	},
	methods: {
		async initialize() {
			await this.$store.dispatch('panel/city/GET_CITIES', '?page=1&limit=10&orderby=turkish');
			this.cities = await this.$store.getters['panel/city/GET_CITIES']
		},

		async searchCity() {
			const value = this.search.trim();
			let params = this.buildQuery() + '&search=' + value;

			if (value) {
				await this.$store.dispatch('panel/city/SEARCH_CITIES', params);
				this.$data.cities = await this.$store.getters['panel/city/GET_CITIES']
				this.$data.totalNumberOfItems = await this.$store.getters['panel/city/GET_TOTAL']
			} else {
				await this.$store.dispatch('panel/city/GET_CITIES', '?page=1&limit=10&orderby=turkish');
				this.$data.cities = await this.$store.getters['panel/city/GET_CITIES']
				this.$data.totalNumberOfItems = await this.$store.getters['panel/city/GET_TOTAL']
			}
		},

		buildQuery(route) {
			const query = route ? route.query : this.$route.query;
			const paginate = this.pagination;
			const page = query.page ?? 1;
			const sortBy = query.sort ?? "ASC";
			let perPage = query.limit ?? 10;

			return '?limit=' + perPage + '&orderby=' + paginate.sortBy + '&page=' + page + '&sort=' + sortBy;
		},

		async fetchData(route = null) {
			await this.$store.dispatch('panel/city/GET_CITIES', this.buildQuery(route));
			this.$data.cities = await this.$store.getters['panel/city/GET_CITIES']
			this.$data.totalNumberOfItems = await this.$store.getters['panel/city/GET_TOTAL']
		},

		editItem(item) {
			this.editedIndex = this.cities.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.dialog = true
		},

		deleteItem(item) {
			this.editedIndex = this.cities.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.dialogDelete = true
		},

		async deleteItemConfirm() {
			await this.$store.dispatch('panel/city/DELETE_CITY', {id: this.editedItem.id});
			this.$data.cities = await this.$store.getters['panel/city/GET_CITIES']
			this.$data.totalNumberOfItems = await this.$store.getters['panel/city/GET_TOTAL']
			this.closeDelete()
		},

		close() {
			this.dialog = false
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
			if (this.editedItem.id) {

				await this.$store.dispatch('panel/city/UPDATE_CITY', {
					id: this.editedItem.id,
					name: this.editedItem.turkish,
					cities: {
						russian: this.editedItem.russian,
						english: this.editedItem.english,
						turkish: this.editedItem.turkish,
					}
				});
			} else {
				await this.$store.dispatch('panel/city/CREATE_CITY', {
					name: this.editedItem.english,
					cities: {
						russian: this.editedItem.russian,
						english: this.editedItem.english,
						turkish: this.editedItem.turkish,
					},
				});
			}
			this.search = '';
			this.cities = await this.$store.getters['panel/city/GET_CITIES'];
			this.close()
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
				console.log('CITIES ERROR')
			});
		},
	},
}
</script>

<style scoped>

</style>
