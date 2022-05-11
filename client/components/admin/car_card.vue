<template>
  <v-card
    :loading="loading"

  >
    <v-img
      height="200"

      :src="image" alt=""/>


    <v-card-text>
      <p class="my-2  text-subtitle-1">Araç ismi: <b>{{ name }}</b></p>
      <p class="my-2  text-subtitle-1">Araç tipi: <b>{{ type }}</b></p>
      <p class="my-2  text-subtitle-1">Kişi sayısı: <b>{{ person_quantity }}</b></p>
      <p class="my-2  text-subtitle-1">Bagaj sayısı: <b>{{ baggage_quantity }}</b></p>
      <v-text-field
        :value="price"
        @input="update"
        label="Fiyat TL"
        :rules="rules.price"
        hint="Sistem tarafından otomatik olarak USD ve eur'ye dönüştürülür"

      ></v-text-field>


      <v-btn
        color="red darken-3 lighten-2"
        class="px-2 py-3"
        text
        @click="remove(id)"
      >

        <v-icon>mdi-close-circle</v-icon>
        Sil
      </v-btn>
    </v-card-text>


  </v-card>
</template>

<script>
export default {
  name: "car_card",
  data() {
    return {
      error: '', rules: {
        price: [val => (val || '').length > 0 || 'Fiyat alanı gereklidir.'],
      }
    }
  },
  props: {
    id: Number,
    image: String,
    loading: Boolean,
    name: String,
    price: String,
    description: String,
    type: String,
    remove: Function,
    person_quantity: Number,
    baggage_quantity: Number,
  },


  methods: {
    update(value) {
      this.$emit('changePrice', value)
    },
  }
}
</script>

<style scoped>

</style>
