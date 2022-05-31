<template>
	<div>
		<header-admin update="" :breadcrumbs-list="breadcrumbs" :pageTitle="pageTitle"></header-admin>
		<v-skeleton-loader class="main-box-container mt-7" :loading="loading" type="table">

			<v-data-table
					:headers="headers"
					:items="users"
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
						<v-toolbar-title>Kullanıcı listesi</v-toolbar-title>
						<v-divider
								class="mx-4"
								inset
								vertical
						></v-divider>
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
														placeholder="Kullanıcı Adı"
														label="Kullanıcı Adı"
														:error-messages="errors.name"

												/>

												<v-text-field
														v-model="editedItem.email"
														label="Kullanıcı eposta"
														placeholder="info@mail.com"
														:error-messages="errors.email"

												/>
												<v-select
														:items="roles"
														v-model="editedItem.role_info"
														label="Rol"
														item-text="name"
														item-value="id"
														:error-messages="errors.role"
												></v-select>
												<v-text-field v-if="editedItem.hasOwnProperty('password')"
												              v-model="editedItem.password"
												              label="Şifre"
												              :type="show1 ? 'text' : 'password'"
												              append-outer-icon="mdi-backup-restore"
												              :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
												              @click:append="show1 = !show1"
												              @click:append-outer="generatePassword"

												              hint="At least 8 characters"
												              placeholder="******"
												              :error-messages="errors.password"


												/>


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

export default {
	name: "users",
	components: {HeaderAdmin},
	layout: "admin",
	data() {
		return {
			loading: true,
			breadcrumbs: [
				{
					text: 'Kontrol paneli',
					disabled: false,
					href: '/panel',
				},
				{
					text: 'Kullanıcı',
					disabled: true,
					href: '/users',
				},
			],
			pageTitle: 'Kullanıcıları düzenle',
			dialog: false,
			dialogDelete: false,
			headers: [
				{
					text: '№',
					align: 'start',
					sortable: true,
					value: 'id',
				},
				{text: 'Ad Soyad', value: 'name'},
				{text: 'Email', value: 'email'},
				{text: 'Kullanıcı rolü', value: 'role'},
				{text: 'Aktif', value: 'active'},
				{text: 'Actions', value: 'actions', sortable: false},

			],
			users: [],
			editedIndex: -1,
			show1: false,
			editedItem: {
				id: '',
				name: '',
				email: '',
				role: '',
				active: false,
				role_info: '',
			},
			roles: [],
			defaultItem: {
				id: false,
				name: '',
				email: '',
				role: '',
				active: false,
				password: '',
				role_info: '',

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

			characters: 'a-z,A-Z,0-9',
			size: 10,
		}
	},


	computed: {
		formTitle() {
			return this.editedIndex === -1 ? 'Kullanıcıi ekle' : 'Kullanıcıi düzenle'
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
		await this.$store.dispatch('panel/user/GET_USERS', '?page=1&limit=10');
		this.users = await this.$store.getters['panel/user/GET_USERS']
		this.roles = await this.$store.getters['panel/user/GET_ROLES']
		this.totalNumberOfItems = await this.$store.getters['panel/user/GET_TOTAL']
		this.loading = false;
	},

	async beforeRouteUpdate(to, from, next) {
		await this.fetchData(to);
		next();
	},
	methods: {
		async initialize() {
			this.users = await this.$store.getters['panel/user/GET_USERS']
		},

		generatePassword() {
			let charactersArray = this.characters.split(',');
			let CharacterSet = '';
			let password = '';

			if (charactersArray.indexOf('a-z') >= 0) {
				CharacterSet += 'abcdefghijklmnopqrstuvwxyz';
			}
			if (charactersArray.indexOf('A-Z') >= 0) {
				CharacterSet += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			}
			if (charactersArray.indexOf('0-9') >= 0) {
				CharacterSet += '0123456789';
			}
			if (charactersArray.indexOf('#') >= 0) {
				CharacterSet += '![]{}()%&*$#^<>~@|';
			}

			for (let i = 0; i < this.size; i++) {
				password += CharacterSet.charAt(Math.floor(Math.random() * CharacterSet.length));
			}
			if (this.editedItem.hasOwnProperty('password')) {
				this.editedItem.password = password;
			}

		},
		async searchCompany() {
			const value = this.search.trim();
			let params = this.buildQuery() + '&search=' + value;

			if (value) {
				await this.$store.dispatch('panel/user/SEARCH_USER', params);
				this.$data.users = await this.$store.getters['panel/user/GET_USERS']
				this.$data.totalNumberOfItems = await this.$store.getters['panel/user/GET_TOTAL']
			} else {
				await this.$store.dispatch('panel/user/GET_USERS', '?page=1&limit=10&orderby=id');
				this.$data.users = await this.$store.getters['panel/user/GET_USERS']
				this.$data.totalNumberOfItems = await this.$store.getters['panel/user/GET_TOTAL']
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
			await this.$store.dispatch('panel/user/GET_USERS', this.buildQuery(route));
			this.$data.users = await this.$store.getters['panel/user/GET_USERS']
			this.$data.totalNumberOfItems = await this.$store.getters['panel/user/GET_TOTAL']
		},

		editItem(item) {
			this.editedIndex = this.users.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.dialog = true

			console.log(item)
		},

		deleteItem(item) {
			this.editedIndex = this.users.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.dialogDelete = true
		},

		async deleteItemConfirm() {
			await this.$store.dispatch('panel/user/DELETE_USER', {id: this.editedItem.id});
			this.$data.users = await this.$store.getters['panel/user/GET_USERS']
			this.$data.totalNumberOfItems = await this.$store.getters['panel/user/GET_TOTAL']
			this.closeDelete()
		},

		async close() {
			this.dialog = false
			await this.$store.dispatch('panel/user/CLEAR_ERROR')
			this.errors = await this.$store.getters['panel/user/GET_ERRORS'];

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
			await this.$store.dispatch('panel/user/CLEAR_ERROR')

			if (this.editedItem.id) {

				await this.$store.dispatch('panel/user/UPDATE_USER', {
					id: this.editedItem.id,
					name: this.editedItem.name,
					email: this.editedItem.email,
					role_id: this.editedItem.role_info.id,
					active: this.editedItem.active,

				});
			} else {
				await this.$store.dispatch('panel/user/CREATE_USER', {
					name: this.editedItem.name,
					email: this.editedItem.email,
					password: this.editedItem.password,
					role_id: this.editedItem.role_info.id,
					active: this.editedItem.active,

				});
			}
			this.search = '';
			this.users = await this.$store.getters['panel/user/GET_USERS'];
			let error = await this.$store.getters['panel/user/GET_ERRORS'];
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
				console.log('COMPANIES ERROR')
			});
		},
	},
}
</script>
