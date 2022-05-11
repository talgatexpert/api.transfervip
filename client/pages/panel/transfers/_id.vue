<template>
  <div>
    <header-admin update="" :breadcrumbs-list="breadcrumbsList" :pageTitle="pageTitle"></header-admin>
    <v-container>
      <v-row>
        <v-col cols="12" lg="6" md="6" sm="12">
          <v-autocomplete :value="cityFrom" :items="citiesFrom" return-object data-from="" v-model="cityFrom"
                          @keyup="findCity"
                          item-text="turkish"
                          item-value="id" label="Nereden" :error-messages="errors.city_from"></v-autocomplete>
        </v-col>
        <v-col cols="12" lg="6" md="6" sm="12">
          <v-autocomplete :items="citiesTo" data-to @keyup="findCity" return-object :error-messages="errors.city_to"
                          v-model="cityTo"
                          item-text="turkish" item-value="id"
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
              no-title
              :value="started_at"
              locale="tr"
              :min="today"

              first-day-of-week="1"
              v-model="started_at"
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
              no-title
              :value="ended_at"
              locale="tr"
              :min="today"
              first-day-of-week="1"
              v-model="ended_at"
              @input="menu2 = false"
            ></v-date-picker>
          </v-menu>
        </v-col>
        <v-col cols="12" lg="12" md="12">
          <v-autocomplete
            :value="selected_cars"
            v-model="selected_cars"
            :items="cars"
            @keyup="findCars"

            label="Select"
            item-text="full_name"
            item-value="id"
            return-object
            multiple
            :error-messages="errors.selected_cars"

          >
            <template v-slot:selection="data">
              <v-chip
                v-bind="data.attrs"
                :input-value="data.selected"
                close
                @click="data.select"
                @click:close="remove(data.item.id)"
              >
                <v-avatar left>
                  <v-img :src="data.item.image"></v-img>
                </v-avatar>
                {{ data.item.full_name }}
              </v-chip>
            </template>
            <template v-slot:item="data">
              <template v-if="typeof data.item !== 'object'">
                <v-list-item-content v-text="data.item"></v-list-item-content>
              </template>
              <template v-else>
                <div class="d-flex">
                  <v-list-item-avatar>
                    <img :src="data.item.image" alt="">
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-list-item-title v-html="data.item.full_name"></v-list-item-title>
                  </v-list-item-content>
                </div>
              </template>
            </template>
          </v-autocomplete>
          <v-row class="mb-5">
            <v-col cols="12" md="3" v-for="(car, key) in selected_cars" :key="key">
              <car_card :loading="false"
                        :id="car.id"
                        :name="car.full_name"
                        :baggage_quantity="car.baggage_quantity"
                        :person_quantity="car.person_quantity"
                        :type="car.type"
                        :image="car.image"
                        :car="car"
                        :price="car.price"
                        :remove="remove"
                        @changePrice="editPrice($event, car.id)"

              />
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12" md="12">
          <v-divider></v-divider>
          <v-textarea
            v-model="description"
            name="input-7-1"
            label="Transfer açıklaması"
            hint="Transferinizin açıklamasını burada yazabilirsiniz"
          ></v-textarea>

          <v-btn block depressed class="mt-5" @click="save"
                 color="primary">Kaydetmek
          </v-btn>
        </v-col>

      </v-row>
    </v-container>
  </div>
</template>

<script>
import HeaderAdmin from "../../../components/admin/HeaderAdmin";
import car_card from "../../../components/admin/car_card";
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
  components: {HeaderAdmin, car_card},
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
      select: [],
      citiesTo: [],
      description: '',
      tax: '0%',
      price: '',
      penalty: '50%',
      cancel_time: '24',
      started_at: '',
      ended_at: '',
      range_date: [],
      errors: [],
      startDateFormatted: '',
      endDateFormatted: '',
      menu1: false,
      menu2: false,
      selected_cars: [],
      cars: [],
      cityFrom: '',
      cityTo: '',
      id: this.$route.params.id !== "new" ? this.$route.params.id : false,

    }
  },

  async asyncData({app, route}) {
    await app.store.dispatch('panel/city/SEARCH_CITIES', '?search=' + '&limit=5&language=turkish&');
    let fromCities = await app.store.getters['panel/city/GET_CITIES']
    let toCities = await app.store.getters['panel/city/GET_CITIES']
    await app.store.dispatch('panel/car/SEARCH_CAR', '?search=' + '&limit=5');
    let allCars = await app.store.getters['panel/car/GET_CARS']
    let cars = [];
    let citiesFrom = [];
    let citiesTo = [];

    function removeDuplicates(originalArray, prop) {
      var newArray = [];
      var lookupObject = {};

      for (var i in originalArray) {
        lookupObject[originalArray[i][prop]] = originalArray[i];
      }

      for (i in lookupObject) {
        newArray.push(lookupObject[i]);
      }
      return newArray;
    }

    cars.push(...allCars);
    citiesFrom.push(...fromCities);
    citiesTo.push(...toCities)
    let data = {};
    if (route.params.id !== "new") {
      await app.store.dispatch('panel/transfer/GET_ONE', {id: route.params.id})
      const transfer = await app.store.getters['panel/transfer/GET_ONE'];
      let selected_cars = [...transfer.cars]
      citiesFrom.push({
        turkish: transfer.start_city.name,
        id: transfer.start_city.id
      });
      citiesTo.push({
        turkish: transfer.end_city.name,
        id: transfer.end_city.id
      });


      cars.push(...transfer.cars)
      cars = removeDuplicates(cars, 'id')
      citiesFrom = removeDuplicates(citiesFrom, 'id')
      citiesTo = removeDuplicates(citiesTo, 'id')
      data = {
        selected_cars,
        citiesFrom, citiesTo,
        cars,


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
        startDateFormatted: moment(transfer.started_at, 'DD.MM.YYYY').format('DD/MM/YYYY'),
        endDateFormatted: moment(transfer.ended_at, 'DD.MM.YYYY').format('DD/MM/YYYY'),
        started_at: moment(transfer.started_at, 'DD.MM.YYYY').format('YYYY-MM-DD'),
        ended_at: moment(transfer.ended_at, 'DD.MM.YYYY').format('YYYY-MM-DD'),

      }


    } else {
      data = {
        cars: [...allCars],
        citiesFrom, citiesTo
      }
    }

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

  methods: {
    editPrice(e, id) {
      const items = JSON.parse(JSON.stringify(this.selected_cars));

      this.selected_cars = items.map(item => {
        if (item.id === id) {
          item.price = e;
        }
        return item
      })
    },
    remove(id) {
      let index = this.selected_cars.findIndex(item => item.id === id)
      if (index > -1)
        this.selected_cars.splice(index, 1)
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

      if (this.cityTo instanceof Object) {

      }


      const payload = {
        selected_cars: this.selected_cars,
        city_from: this.cityFrom.id,
        city_to: this.cityTo.id,
        tax: parseInt(this.tax.replace('/\D+/g', '')),
        price: this.price,
        started_at: this.started_at,
        ended_at: this.ended_at,
        description: this.description,
        cancel_time: parseInt(this.cancel_time),
        penalty: parseInt(this.penalty),
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
