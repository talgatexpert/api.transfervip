<template>
  <div>
    <header-admin update="" :breadcrumbs-list="breadcrumbsList" :pageTitle="pageTitle"></header-admin>

    <v-data-table
      :headers="headers"
      :items="companies"
      :server-items-length="totalNumberOfItems"
      @update:options="paginate"
      sort-by="id"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar
          flat
        >
          <v-toolbar-title>Şirketler listesi</v-toolbar-title>
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
                        placeholder="Şirket Adı"
                        label="Şirket Adı"
                        :error-messages="errors.name"

                      />

                      <v-text-field
                        v-model="editedItem.owner"
                        label="Şirket sahibi"
                        placeholder="Esma Yeşilkaya"
                        :error-messages="errors.owner"

                      />
                      <v-text-field
                        v-model="editedItem.tax"
                        label="KDV"
                        placeholder="10%"
                        :error-messages="errors.tax"


                      />
                      <v-text-field
                        v-model="editedItem.phone"
                        label="Telefon numarası"
                        placeholder="+90 000 000 00 00"
                        :error-messages="errors.phone"


                      />
                      <v-autocomplete
                        dense
                        :items="owners"
                        class="mt-4"
                        item-text="text"
                        v-model="editedItem.email"

                        label="Kullanıcı"
                        :error-messages="errors.email"
                        @keyup="findUsers"
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
  </div>
</template>

<script>
import HeaderAdmin from "../../components/admin/HeaderAdmin";

export default {
  name: "companies",
  components: {HeaderAdmin},
  layout: "admin",
  data() {
    return {
      breadcrumbsList: [
        {
          text: 'Dashboard',
          disabled: false,
          href: '/panel',
        },
        {
          text: 'companies',
          disabled: true,
          href: '/companies',
        },
      ],
      pageTitle: 'Şirketleri düzenle',
      dialog: false,
      dialogDelete: false,
      headers: [
        {
          text: '№',
          align: 'start',
          sortable: true,
          value: 'id',
        },
        {text: 'Şirket Adı', value: 'name'},
        {text: 'Email', value: 'email'},
        {text: 'KDV', value: 'tax'},
        {text: 'Telefon numarası', value: 'phone'},
        {text: 'Şirket sahibi', value: 'owner'},
        {text: 'Aktif', value: 'active'},
        {text: 'Actions', value: 'actions', sortable: false},

      ],
      companies: [],
      editedIndex: -1,
      editedItem: {
        id: '',
        name: '',
        email: '',
        phone: '',
        owner: '',
        tax: '',
        active: '',
        user_id: '',
      },
      defaultItem: {
        id: false,
        name: '',
        email: '',
        phone: '',
        owner: '',
        tax: '',
        active: '',
        user_id: '',
      },
      owners: [],
      selected_owner: '',
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


  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Şirketi ekle' : 'Şirketi düzenle'
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

  created() {
  },

  async asyncData({app}) {
    await app.store.dispatch('panel/company/GET_COMPANY', '?page=1&limit=10');
    const companies = await app.store.getters['panel/company/GET_COMPANY']
    let totalNumberOfItems = await app.store.getters['panel/company/GET_TOTAL']

    return {companies, totalNumberOfItems}
  },

  async beforeRouteUpdate(to, from, next) {
    await this.fetchData(to);
    next();
  },
  methods: {
    async initialize() {
      await this.$store.dispatch('panel/company/GET_COMPANY', '?page=1&limit=10');
      this.companies = await this.$store.getters['panel/company/GET_COMPANY']
    },

    async findUsers(event) {
      const value = event.target.value.trim()
      if (value)
        await this.$store.dispatch('panel/company/GET_USERS', {email: value});{
        let users = await this.$store.getters['panel/company/GET_USERS'];
        this.owners = users.length > 0 ? users.map(item => {
          return {
            text: item.email,
            value: item.email,
          }
        }) : [];
      }

    },

    async searchCompany() {
      const value = this.search.trim();
      let params = this.buildQuery() + '&search=' + value;

      if (value) {
        await this.$store.dispatch('panel/company/SEARCH_COMPANY', params);
        this.$data.companies = await this.$store.getters['panel/company/GET_COMPANY']
        this.$data.totalNumberOfItems = await this.$store.getters['panel/company/GET_TOTAL']
      } else {
        await this.$store.dispatch('panel/company/GET_COMPANY', '?page=1&limit=10&orderby=id');
        this.$data.companies = await this.$store.getters['panel/company/GET_COMPANY']
        this.$data.totalNumberOfItems = await this.$store.getters['panel/company/GET_TOTAL']
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
      await this.$store.dispatch('panel/company/GET_COMPANY', this.buildQuery(route));
      this.$data.companies = await this.$store.getters['panel/company/GET_COMPANY']
      this.$data.totalNumberOfItems = await this.$store.getters['panel/company/GET_TOTAL']
    },

    editItem(item) {
      this.editedIndex = this.companies.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.owners = [{
        text: this.editedItem.email,
        value: this.editedItem.email,

      }]
      if (this.editedItem.active.toUpperCase() === "YOK") {
        this.editedItem.active = 0;
      } else {
        this.editedItem.active = 1;
      }
      this.dialog = true
    },

    deleteItem(item) {
      this.editedIndex = this.companies.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    async deleteItemConfirm() {
      await this.$store.dispatch('panel/company/DELETE_COMPANY', {id: this.editedItem.id});
      this.$data.companies = await this.$store.getters['panel/company/GET_COMPANY']
      this.$data.totalNumberOfItems = await this.$store.getters['panel/company/GET_TOTAL']
      this.closeDelete()
    },

    async close() {
      this.dialog = false
      await this.$store.dispatch('panel/company/CLEAR_ERROR')
      this.errors = await this.$store.getters['panel/company/GET_ERRORS'];

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
      await this.$store.dispatch('panel/company/CLEAR_ERROR')

      if (this.editedItem.id) {

        await this.$store.dispatch('panel/company/UPDATE_COMPANY', {
          id: this.editedItem.id,
          name: this.editedItem.name,
          owner: this.editedItem.owner,
          email: this.editedItem.email,
          tax: this.editedItem.tax,
          agree_terms: 1,
          active: this.editedItem.active ? 1 : 0,
          phone: this.editedItem.phone,
        });
      } else {
        await this.$store.dispatch('panel/company/CREATE_COMPANY', {
          name: this.editedItem.name,
          owner: this.editedItem.owner,
          email: this.editedItem.email,
          tax: this.editedItem.tax,
          agree_terms: 1,
          active: this.editedItem.active ? 1 : 0,
          phone: this.editedItem.phone,

        });
      }
      this.search = '';
      this.companies = await this.$store.getters['panel/company/GET_COMPANY'];
      let error = await this.$store.getters['panel/company/GET_ERRORS'];
      const checkOwnProperty = (obj, propertyName) => (obj && (typeof obj == "object" || typeof obj == "function") && Object.prototype.hasOwnProperty.call(obj, propertyName));
      if (error === undefined){
        error = [];
      }
      this.errors = error;
      if (checkOwnProperty( error, 'length') && error.length === 0) {
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

<style scoped>

</style>
