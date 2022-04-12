<template>
  <div>
    <header-admin update="" :breadcrumbs-list="breadcrumbsList" :pageTitle="pageTitle"></header-admin>
    <v-container>
      <v-row>
        <v-col cols="12" lg="6" md="6" sm="12">
          <v-autocomplete :value="cityFrom" :items="citiesFrom" data-from="" v-model="cityFrom" @keyup="findCity"
                          item-text="turkish"
                          item-value="turkish" label="Nereden" :error-messages="errors.city_from"></v-autocomplete>
        </v-col>
        <v-col cols="12" lg="6" md="6" sm="12">
          <v-autocomplete :items="citiesTo" data-to @keyup="findCity" :error-messages="errors.city_to" v-model="cityTo"
                          item-text="turkish" item-value="turkish"
                          label="Nereye"></v-autocomplete>
        </v-col>
        <v-col cols="12" lg="3" md="12">
          <v-text-field
            v-model="tax"
            label="KDV"
            prepend-icon="mdi-percent"
            :error-messages="errors.tax"
          ></v-text-field>
        </v-col>
        <v-col cols="12" lg="3" md="12">
          <v-text-field
            v-model="price"
            label="Fiyat TL"
            hint="Sistem tarafından otomatik olarak USD ve eur'ye dönüştürülür"
            :error-messages="errors.price"

          ></v-text-field>
        </v-col>
        <v-col cols="12" lg="3" md="12">
          <v-text-field
            v-model="cancel_time"
            prepend-icon="mdi-timer"
            label="Iptal suresi"
            :error-messages="errors.cancel_time"

          ></v-text-field>
        </v-col>
        <v-col cols="12" lg="3" md="12">
          <v-text-field
            v-model="penalty"
            prepend-icon="mdi-percent"
            :error-messages="errors.penalty"

            label="Ceza %"
          ></v-text-field>
        </v-col>

        <v-col cols="12" lg="4" md="12">
          <v-menu
            ref="menu1"
            v-model="menu1"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            max-width="290px"
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="startDateFormatted"
                label="Başlangıç tarihi"
                persistent-hint
                prepend-icon="mdi-calendar"
                v-bind="attrs"
                readonly
                v-on="on"
                :error-messages="errors.started_at"

              ></v-text-field>
            </template>
            <v-date-picker
              :value="started_at"
              reactive
              no-title
              :min="today"
              @input="menu1 = false"
            ></v-date-picker>
          </v-menu>
        </v-col>
        <v-col cols="12" lg="4" md="12">
          <v-menu
            ref="menu2"
            v-model="menu2"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            readonly
            max-width="290px"
            min-width="auto"

          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="endDateFormatted"
                label="Bitiş tarihi"
                persistent-hint
                readonly
                prepend-icon="mdi-calendar"
                v-bind="attrs"
                v-on="on"
                :error-messages="errors.ended_at"

              ></v-text-field>
            </template>
            <v-date-picker
              v-model="ended_at"
              no-title
              :min="started_at"
              @input="menu2 = false"
            ></v-date-picker>
          </v-menu>
        </v-col>
        <v-col cols="12" lg="12" md="12">
          <v-autocomplete
            v-model="selectedCars"
            :items="cars"
            @keyup="findCars"
            label="Select"
            item-text="full_name"
            item-value="id"
            multiple
            :error-messages="errors.selected_cars"

          >
            <template v-slot:selection="data">
              <v-chip
                v-bind="data.attrs"
                :input-value="data.selected"
                close
                @click="data.select"
                @click:close="remove(data.item)"
              >
                <v-avatar left>
                  <v-img :src="data.item.image"></v-img>
                </v-avatar>
                {{ data.item.name }}
              </v-chip>
            </template>
            <template v-slot:item="data">
              <template v-if="typeof data.item !== 'object'">
                <v-list-item-content v-text="data.item"></v-list-item-content>
              </template>
              <template v-else>
                <v-list-item-avatar>
                  <img :src="data.item.image" alt="">
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title v-html="data.item.full_name"></v-list-item-title>
                </v-list-item-content>
              </template>
            </template>
          </v-autocomplete>
        </v-col>

        <v-col cols="12" md="12">
          <v-textarea
            v-model="description"
            name="input-7-1"
            label="Transfer açıklaması"
            hint="Transferinizin açıklamasını burada yazabilirsiniz"
          ></v-textarea>

          <v-btn block depressed @click="save"
                 color="primary">Kaydetmek
          </v-btn>
        </v-col>

      </v-row>
    </v-container>
  </div>
</template>

<script>
import HeaderAdmin from "../../../components/admin/HeaderAdmin";
import Vue from 'vue'
import VueMoment from 'vue-moment'
import moment from 'moment-timezone'

if (!Vue.moment) {
  Vue.use(VueMoment, {
    moment,
  })
}

export default {
  name: "id",
  layout: 'admin',
  components: {HeaderAdmin},
  data() {
    return {
      breadcrumbsList: [
        {
          text: 'Dashboard',
          disabled: false,
          href: '/panel/',
        },
        {
          text: 'Transfers',
          disabled: false,
          href: '/panel/transfers',
        },
        {
          text: 'Düzenle',
          disabled: false,
          href: '/panel/transfers/' + this.$route.params.id,
        },
      ],
      citiesFrom: [],
      citiesTo: [],
      description: '',
      tax: '0%',
      price: '',
      penalty: '50%',
      cancel_time: '24',
      started_at: (new Date('2022-04-15')).toISOString(),
      ended_at: '',
      range_date: [],
      errors: [],
      startDateFormatted: '',
      endDateFormatted: '',
      menu1: false,
      menu2: false,
      selectedCars: [],
      cars: [],
      cityFrom: '',
      cityTo: '',
      id: this.$route.params.id !== "new" ? this.$route.params.id : false,

    }
  },

  async asyncData({app, route}) {
    await app.store.dispatch('panel/city/SEARCH_CITIES', '?search=' + '&limit=5&language=turkish&');
    const citiesFrom = await app.store.getters['panel/city/GET_CITIES']
    const citiesTo = await app.store.getters['panel/city/GET_CITIES']
    let data = {};
    if (route.params.id !== "new") {
      await app.store.dispatch('panel/transfer/GET_ONE', {id: route.params.id})
      const transfer = await app.store.getters['panel/transfer/GET_ONE'];


      data = {
        citiesFrom, citiesTo,

        cityFrom: {
          turkish: transfer.start_city.name,
          id: transfer.start_city.id
        },


        cityTo: {
          turkish: transfer.end_city.name,
          id: transfer.end_city.id
        },

        tax: transfer.tax,
        price: transfer.price,
        penalty: transfer.penalty,
        cancel_time: transfer.cancel_time,
        description: transfer.description,
        startDateFormatted: moment((new Date('2022-04-15')).toISOString()).format('DD/MM/YYYY'),
        endDateFormatted: moment(transfer.ended_at).format('DD/MM/YYYY'),
        // started_at: moment(transfer.started_at).toDate().toISOString()


      }

    }
    // if (this.id !== false){
    //
    // }
    console.log(data)

    return data
  },

  watch: {
    started_at() {
      this.startDateFormatted = moment(this.started_at).format('DD/MM/YYYY')
    },
    ended_at() {
      this.endDateFormatted = moment(this.ended_at).format('DD/MM/YYYY')
    },
  },
  computed: {
    pageTitle() {
      let title = '';
      if (this.$route.query.hasOwnProperty('from')) {
        return 'Transfer ' + this.$route.query.from + ' düzenle';
      }

      return 'Transfer düzenle'
    },
    today() {
      return (new Date()).toISOString()
    }
  },
  async created() {

    await this.$store.dispatch('panel/car/SEARCH_CAR', '?search=' + '&limit=5');
    this.cars = await this.$store.getters['panel/car/GET_CARS']
  },
  methods: {
    remove(item) {
      let index = this.cars.indexOf(item.id)
      this.selectedCars.splice(index, 1)
    },

    formatDate(date) {
      if (!date) return

      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },

    async findCity(e) {
      const value = e.target.value.trim();

      if (e.target.attributes.hasOwnProperty('data-from') && value) {
        let to = this.cityTo ? 'except=' + this.cityTo : '';
        await this.$store.dispatch('panel/city/SEARCH_CITIES', '?search=' + value + '&limit=5&language=turkish&' + to);
        this.citiesFrom = await this.$store.getters['panel/city/GET_CITIES']
      }
      if (e.target.attributes.hasOwnProperty('data-to') && value) {
        let from = this.cityFrom ? 'except=' + this.cityFrom : '';
        await this.$store.dispatch('panel/city/SEARCH_CITIES', '?search=' + value + '&limit=5&language=turkish&' + from);
        this.citiesTo = await this.$store.getters['panel/city/GET_CITIES']
      }
    },
    async findCars(e) {
      const value = e.target.value.trim();
      if (value) {
        await this.$store.dispatch('panel/car/SEARCH_CAR', '?search=' + value + '&limit=5');
        this.cars = await this.$store.getters['panel/car/GET_CARS']
      }
    },

    async save() {
      await this.$store.dispatch('panel/transfer/CLEAR_ERROR');

      const payload = {
        selected_cars: this.selectedCars,
        city_from: this.cityFrom,
        city_to: this.cityTo,
        tax: parseInt(this.tax.replace('/\D+/g', '')),
        price: this.price,
        started_at: this.started_at,
        ended_at: this.ended_at,
        description: this.description,
        cancel_time: parseInt(this.cancel_time.replace('/\D+/g', '')),
        penalty: parseInt(this.penalty.replace('/\D+/g', '')),
      };
      const checkOwnProperty = (obj, propertyName) => (obj && (typeof obj == "object" || typeof obj == "function") && Object.prototype.hasOwnProperty.call(obj, propertyName));

      if (this.id === false) {
        await this.$store.dispatch('panel/transfer/SAVE_TRANSFER', payload);
      } else {
        payload['id'] = this.id
        await this.$store.dispatch('panel/transfer/SAVE_TRANSFER', payload);
      }

      this.errors = await this.$store.getters['panel/transfer/GET_ERRORS'];

      if (checkOwnProperty(this.errors, 'length') && this.errors.length === 0) {
        await this.$router.replace('/panel/transfers')
      }
    }

  }
}
</script>

<style scoped>

</style>
