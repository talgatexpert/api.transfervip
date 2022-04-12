<template>
  <main>
        <data-picker></data-picker>

    <div class="container" id="transfers">
        <section class="content-info">
          <h2 class="content-info__title text-center" >{{$t('search_transfer', { from , to})}}</h2>
          <card car_id="648"></card>
          <card car_id="6561"></card>

        </section>
    </div>
  </main>
</template>

<script>
import DataPicker from "../components/DataPicker";
import Card from "../components/Card";
export default {
  name: "transfer",
  components: {DataPicker, Card},
  data(){
    return {
      from: "",
      to: "",
    }
  },
  head() {
    return {
      htmlAttrs: {
        lang: this.$i18n.locale
      }
    }
  },
  asyncData({params, store}) {
    store.dispatch('city/GET_CITY_BY_SLUG', params);
  },
  mounted() {
    this.$data.from = this.$store.getters['city/getCityFrom'].name
    this.$data.to = this.$store.getters['city/getCityTo'].name
  }


}

</script>

<style scoped>

</style>
