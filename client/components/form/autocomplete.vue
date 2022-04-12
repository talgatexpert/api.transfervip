<template>

</template>

<script>
export default {
  name: "autocomplete",

  props: {
    field_name: String,
    placeholder: String,
    id: String,

  },

  data() {
    return {
      cities: [],
      inputValue: '',
    }
  },

  methods: {
    findDestination(event) {
      const value = event.target.value.trim();
      this.$store.dispatch('city/LOAD_CITY', value)
      this.$data.inputValue = value;


      if (value === "") {
        this.$data.cities = [];
      } else {
        this.$data.cities = this.$store.getters['city/getCities'];
      }

    },

    setCity(event) {

      this.$data.inputValue = event.target.dataset.value;
      this.$data.cities = [];
      if (event.target.dataset.direction === "from") {
         this.$store.dispatch('city/SET_DIRECTION', {
          cityFrom: this.$data.inputValue,
          cityTo: this.$store.getters['city/getCityTo']
        })
      } else if (event.target.dataset.direction === "to") {
         this.$store.dispatch('city/SET_DIRECTION', {
          cityFrom: this.$store.getters['city/getCityFrom'],
          cityTo: this.$data.inputValue
        })

      }
    }
  }
}
</script>

<style scoped>

</style>
