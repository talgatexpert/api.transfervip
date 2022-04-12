<template>
  <div class="route-component padding-form  ">
    <h4 class="form-title" v-if="title">{{ title }}</h4>
    <div class="form-title__detail">
      <i class="i-time"></i> <span>Travel time: ~ 1 h</span>
    </div>
    <div class="route-list">
      <div class="route-list__start">Antalya</div>
      <span class="separator">-</span>
      <div class="route-list__end">Kemer</div>
    </div>
    <div class="route-details form-group">
      <label for="hotel">Departure: hotel name or address *</label>
      <input type="text" class="form-control form-input" id="hotel">
      <span class="error-form d-block">Enter the address or hotel name.</span>

      <label for="flight" class="mt-2 d-block">Flight Number *</label>
      <input type="text" placeholder="TCR3835" class="form-control form-input" id="flight">
      <span class="error-form">Enter the flight number</span>

      <div class="route-details__date">
        <datepicker class="v-datepicker" :lang="$i18n.locale" v-model="picker"/>
        <vue-timepicker class="timepicker"></vue-timepicker>
      </div>


    </div>
  </div>
</template>

<script>
import VueTimepicker from "vue2-timepicker";
import VueDatepickerUi from "vue-datepicker-ui";

export default {
  name: "Route",
  components: {
    VueTimepicker,
    Datepicker: VueDatepickerUi,
  },
  props:{
    title: String,

  },
  data() {
    return {
      picker: '',
    }
  }
}
</script>

<style scoped>

</style>
