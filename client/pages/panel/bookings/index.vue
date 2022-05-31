<template>
	<div>
		<header-admin update="" :breadcrumbs-list="breadcrumbs" :count="count" :pageTitle="pageTitle"></header-admin>
		<v-skeleton-loader class="main-box-container mt-7" :loading="loading" type="table">
			<v-data-table
					:headers="headers"
					:items="bookings"
					:server-items-length="totalNumberOfItems"
					@update:options="paginate"
					sort-by="id"
					class="main-box-container"
			>
				<template v-slot:top>
					<v-toolbar
							flat
							class="main-box-container mt-6"

					>
						<v-row class="mt-3">
							<v-col cols="12" lg="4" md="5" sm="12">
								<v-toolbar-title>Rezervasyon listesi</v-toolbar-title>
							</v-col>
							<v-col cols="12" lg="6" md="8" sm="8">

								<v-spacer></v-spacer>


								<v-text-field
										v-model="search"
										append-icon="mdi-magnify"
										label="Search"
										single-line
										class="col-sm-12"
										@input="searchTransfer"
										hide-details
								></v-text-field>
							</v-col>
							<v-col cols="12" lg="2" md="4" sm="4">
								<v-spacer></v-spacer>
								<v-btn
										color="primary"
										dark
										class="mb-2 col-sm-12"
										to="/panel/bookings/new"
								>
									Yeni rezervasyon
								</v-btn>
							</v-col>
						</v-row>

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
					<!--				<v-icon-->
					<!--						small-->
					<!--						class="mr-2"-->
					<!--						@click="confirmItem(item)"-->
					<!--				>-->
					<!--					mdi-check-bold-->
					<!--				</v-icon>-->
					<v-tooltip bottom color="success">
						<template v-slot:activator="{ on, attrs }">

							<v-icon
									small
									class="mr-2"
									v-bind="attrs"
									v-on="on"
									@click="confirmItem(item)"
							>
								{{ item.booking_accepted !== 0 ? 'mdi-close' : '	mdi-check-bold' }}

							</v-icon>
						</template>

						<span>Rezervasyonu onayla</span>
					</v-tooltip>
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
import HeaderAdmin from "../../../components/admin/HeaderAdmin";

export default {
	name: "index",
	components: {HeaderAdmin},
	layout: "admin",
	data() {
		return {
			loading: true,
			breadcrumbs: [
				{
					text: 'Kontrol paneli',
					disabled: true,
					href: '/panel/dashboard',
				},
				{
					text: 'Transfers',
					disabled: true,
					href: '/bookings',
				},
			],
			pageTitle: 'Rezervasyon listesi',
			dialog: false,
			dialogDelete: false,
			headers: [
				{
					text: '№',
					align: 'start',
					sortable: true,
					value: 'id',
				},
				{align: 'center', width: 200, text: 'Address', value: 'address'},
				{align: 'center', width: 100, text: 'Araç', value: 'car'},
				// {align: 'center', width: 100, text: 'Fiyat TL', value: 'price'},
				// {text: 'Komisyon', va100 'tax'},
				{align: 'center', width: 200, text: 'Döviz kuru ile fiyat', value: 'price_with_currency'},
				// {align: 'center', wid100200, text: 'Döviz kuru', value: 'currency'},
				{align: 'center', width: 100, text: 'Uçuş numarası', value: 'flight_number'},
				{align: 'center', width: 100, text: 'Dönüş yolculuğu', value: 'return_trip'},
				{align: 'center', width: 100, text: 'Yolcu sayısı', value: 'passengers_number'},
				{align: 'center', width: 150, text: 'Çocuk koltuğu', value: 'add_child_seat'},
				{align: 'center', width: 200, text: 'Tarih saat', value: 'started_at'},
				{align: 'center', width: 200, text: 'dönüş yolculuğu tarih saat ', value: 'return_trip_started_at'},
				{align: 'center', width: 200, text: 'Dönüş yolculuğu uçuş numarası', value: 'return_trip_flight_number'},
				{align: 'center', width: 200, text: 'Dönüş yolculuğu adresi', value: 'return_trip_address'},
				{align: 'center', width: 200, text: 'döviz kuru ile toplam', value: 'total_with_currency'},
				{align: 'center', width: 200, text: 'Процент с ТС компании', value: 'company_tax_with_currency'},
				{align: 'center', width: 200, text: 'Процент с Агентства', value: 'agency_tax_with_currency'},
				{align: 'center', width: 100, text: 'Actions', value: 'actions', sortable: false},
			],
			bookings: [],
			editedIndex: -1,
			editedItem: {
				id: '',
				city_from: '',
				city_end: {},
				car_id: '',
				tax: '',
				full_name: '',

				price: '',
				cancel_time: '',
				penalty: '',
				company_id: '',
				user_id: '',
				started_at: '',
				ended_at: '',
				created_at: '',
				updated_at: '',
			},
			defaultItem: {
				id: false,
				direction: '',
				cars: {},
				car_id: '',
				tax: '',
				full_name: '',
				price: '',
				cancel_time: '',
				penalty: '',
				company_id: '',
				user_id: '',
				started_at: '',
				ended_at: '',
				created_at: '',
				updated_at: '',
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
			count: 0,
		}
	},


	computed: {
		formTitle() {
			return this.editedIndex === -1 ? 'Rezervasyonu ekle' : 'Rezervasyonu düzenle'
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
		await this.$store.dispatch('panel/bookings/GET_BOOKINGS', '?page=1&limit=10&orderby=id&language=turkish');
		this.bookings = await this.$store.getters['panel/bookings/GET_BOOKINGS']
		this.totalNumberOfItems = await this.$store.getters['panel/bookings/GET_TOTAL'];
		this.loading = false;
	},

	async beforeRouteUpdate(to, from, next) {
		await this.fetchData(to);
		next();
	},
	methods: {

		async confirmItem(item) {
			await this.$store.dispatch('panel/bookings/ACCEPT_BOOKING', item);
			await this.$store.dispatch('panel/bookings/GET_NOT_ACCEPTED_BOOKINGS');
			this.count = await this.$store.getters['panel/bookings/GET_COUNT']
			this.bookings = await this.$store.getters['panel/bookings/GET_BOOKINGS']
			this.totalNumberOfItems = await this.$store.getters['panel/bookings/GET_TOTAL']
		},
		async initialize() {
			await this.$store.dispatch('panel/bookings/GET_BOOKINGS', '?page=1&limit=10&orderby=id&language=turkish');
			this.bookings = await this.$store.getters['panel/bookings/GET_BOOKINGS']
			this.totalNumberOfItems = await this.$store.getters['panel/bookings/GET_TOTAL']
		},

		async searchTransfer() {
			const value = this.search.trim();
			let params = this.buildQuery() + '&search=' + value;

			if (value) {
				await this.$store.dispatch('panel/bookings/SEARCH_BOOKING', params);
				this.$data.bookings = await this.$store.getters['panel/bookings/GET_BOOKINGS']
				this.$data.totalNumberOfItems = await this.$store.getters['panel/bookings/GET_TOTAL']
			} else {
				await this.$store.dispatch('panel/bookings/GET_BOOKINGS', '?page=1&limit=10&orderby=id&language=turkish');
				this.$data.bookings = await this.$store.getters['panel/bookings/GET_TRANSFERS']
				this.$data.totalNumberOfItems = await this.$store.getters['panel/bookings/GET_TOTAL']
			}
		},

		buildQuery(route) {
			const query = route ? route.query : this.$route.query;
			const paginate = this.pagination;
			const page = query.page ?? 1;
			const sortBy = query.sort ?? "ASC";
			let perPage = query.limit ?? 10;

			return '?limit=' + perPage + '&orderby=' + paginate.sortBy + '&page=' + page + '&sort=' + sortBy + '&language=turkish';
		},

		async fetchData(route = null) {
			await this.$store.dispatch('panel/bookings/GET_BOOKINGS', this.buildQuery(route));
			this.$data.bookings = await this.$store.getters['panel/bookings/GET_BOOKINGS']
			this.$data.totalNumberOfItems = await this.$store.getters['panel/bookings/GET_TOTAL']
		},

		editItem(item) {
			this.editedIndex = this.bookings.indexOf(item)
			this.editedItem = Object.assign({}, item)
			if (this.editedItem.id) {
				this.$router.push('/panel/bookings/' + this.editedItem.id + '?from=' + this.editedItem.direction)
			} else {
				this.$router.push('/panel/bookings/new')
			}
		},

		deleteItem(item) {
			this.editedIndex = this.bookings.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.dialogDelete = true
		},

		async deleteItemConfirm() {
			await this.$store.dispatch('panel/bookings/DELETE_BOOKING', {id: this.editedItem.id});
			this.$data.bookings = await this.$store.getters['panel/bookings/GET_BOOKINGS']
			this.$data.totalNumberOfItems = await this.$store.getters['panel/transfer/GET_TOTAL']
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

				await this.$store.dispatch('panel/bookings/UPDATE_BOOKING', {
					id: this.editedItem.id,
					name: this.editedItem.id,
					bookings: {
						russian: this.editedItem.russian,
						english: this.editedItem.english,
						id: this.editedItem.id,
					}
				});
			} else {
				await this.$store.dispatch('panel/bookings/CREATE_BOOKING', {
					name: this.editedItem.english,
					bookings: {
						russian: this.editedItem.russian,
						english: this.editedItem.english,
						id: this.editedItem.id,
					},
				});
			}
			this.search = '';
			this.bookings = await this.$store.getters['panel/bookings/GET_BOOKINGS'];
			this.totalNumberOfItems = await this.$store.getters['panel/bookings/GET_TOTAL']

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
				obj.sort = "DESC"
			} else {
				obj.sort = "ASC"

			}
			obj.language = "turkish"


			// check if old and new query are the same - VueRouter will not change the route if they are, so probably this is the first page loading

			await this.$router.replace({...this.$router.currentRoute, query: obj}).catch((error) => {
				console.log('TRANSFER ERROR')
			});
		},
	},
}
</script>

<style scoped>

</style>
