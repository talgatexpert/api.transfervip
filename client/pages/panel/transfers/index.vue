<template>
  <div>
    <header-admin update="" :breadcrumbs-list="breadcrumbsList" :pageTitle="pageTitle"></header-admin>

    <v-data-table
      :headers="headers"
      :items="transfers"
      :server-items-length="totalNumberOfItems"
      @update:options="paginate"
      sort-by="id"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar
          flat
        >
          <v-toolbar-title>Transfer listesi</v-toolbar-title>
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
                @click="editItem()"
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
                        v-model="editedItem.id"
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
      breadcrumbsList: [
        {
          text: 'Dashboard',
          disabled: false,
          href: '/panel/dashboard',
        },
        {
          text: 'Transfers',
          disabled: true,
          href: '/transfers',
        },
      ],
      pageTitle: 'Edit transfers',
      dialog: false,
      dialogDelete: false,
      headers: [
        {
          text: '№',
          align: 'start',
          sortable: true,
          value: 'id',
        },
        {text: 'Yön', value: 'direction'},
        {text: 'Araçlar', value: 'cars.full_name'},
        {text: 'Fiyat TL', value: 'price'},
        {text: 'Komisyon', value: 'tax'},
        {text: 'İptal Süresi', value: 'cancel_time'},
        {text: 'Ceza %', value: 'penalty'},
        {text: 'Başlangıç tarihi', value: 'started_at'},
        {text: 'Bitiş tarihi', value: 'ended_at'},
        {text: 'Actions', value: 'actions', sortable: false},
      ],
      transfers: [],
      editedIndex: -1,
      editedItem: {
        id: '',
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
    }
  },


  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Transfer ekle' : 'Transfer düzenle'
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
    await app.store.dispatch('panel/transfer/GET_TRANSFERS', '?page=1&limit=10&orderby=id');
    const transfers = await app.store.getters['panel/transfer/GET_TRANSFERS']
    let totalNumberOfItems = await app.store.getters['panel/transfer/GET_TOTAL']

    return {transfers, totalNumberOfItems}
  },

  async beforeRouteUpdate(to, from, next) {
    await this.fetchData(to);
    next();
  },
  methods: {
    async initialize() {
      await this.$store.dispatch('panel/transfer/GET_TRANSFERS', '?page=1&limit=10&orderby=id');
      this.transfers = await this.$store.getters['panel/transfer/GET_TRANSFERS']
    },

    async searchCity() {
      const value = this.search.trim();
      let params = this.buildQuery() + '&search=' + value;

      if (value) {
        await this.$store.dispatch('panel/transfer/SEARCH_TRANSFERS', params);
        this.$data.transfers = await this.$store.getters['panel/transfer/GET_TRANSFERS']
        this.$data.totalNumberOfItems = await this.$store.getters['panel/transfer/GET_TOTAL']
      }else{
        await this.$store.dispatch('panel/transfer/GET_TRANSFERS' , '?page=1&limit=10&orderby=id');
        this.$data.transfers = await this.$store.getters['panel/transfer/GET_TRANSFERS']
        this.$data.totalNumberOfItems = await this.$store.getters['panel/transfer/GET_TOTAL']
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
      await this.$store.dispatch('panel/transfer/GET_TRANSFERS', this.buildQuery(route));
      this.$data.transfers = await this.$store.getters['panel/transfer/GET_TRANSFERS']
      this.$data.totalNumberOfItems = await this.$store.getters['panel/transfer/GET_TOTAL']
    },

    editItem(item) {
      this.editedIndex = this.transfers.indexOf(item)
      this.editedItem = Object.assign({}, item)
      if (this.editedItem.id){
        this.$router.push('/panel/transfers/' + this.editedItem.id + '?from=' + this.editedItem.direction)
      } else{
        this.$router.push('/panel/transfers/new')
      }
    },

    deleteItem(item) {
      this.editedIndex = this.transfers.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    async deleteItemConfirm() {
      await this.$store.dispatch('panel/transfer/DELETE_CITY', {id: this.editedItem.id});
      this.$data.transfers = await this.$store.getters['panel/transfer/GET_TRANSFERS']
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

        await this.$store.dispatch('panel/transfer/UPDATE_CITY', {
          id: this.editedItem.id,
          name: this.editedItem.id,
          transfers: {
            russian: this.editedItem.russian,
            english: this.editedItem.english,
            id: this.editedItem.id,
          }
        });
      } else {
        await this.$store.dispatch('panel/transfer/CREATE_CITY', {
          name: this.editedItem.english,
          transfers: {
            russian: this.editedItem.russian,
            english: this.editedItem.english,
            id: this.editedItem.id,
          },
        });
      }
      this.search = '';
      this.transfers = await this.$store.getters['panel/transfer/GET_TRANSFERS'];
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
