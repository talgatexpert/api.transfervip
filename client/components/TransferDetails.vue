<template>
  <div class="form form-container " v-if="changed">
      <div class="padding-form">
        <div class="d-flex align-items-center justify-content-between">
        <h4 class="form-title m-0">Transfer details</h4>

        <div class="form-action" @click="change">Change</div>
      </div>
      </div>
  </div>
  <div v-else-if="edit">
    <div class="form form-container">

      <route title="Transfer details"></route>

      <div class="form-label border-form">
        <div class="padding-form">
          <div class="align-items-center row">
            <div class="col-lg-8 col-8">
              <div class="return-content ">
                <p>Return trip</p>
                <span class="return-content__price">4358 RUB</span>
              </div>
            </div>
            <div class="col-lg-4 col-4">
              <div class="custom-checkbox default-checkbox">
                <input type="checkbox" v-on:change="toggle" id="return"
                       class="custom-checkbox__input color-grey">
                <label for="return" class="color-grey invert"></label>
              </div>
            </div>

          </div>
        </div>
      </div>
      <route v-bind:is="component"></route>

    </div>
    <div class="form form-container">
      <div class="padding-form">
        <h5 class="form-title">Passengers</h5>
        <div class="label d-flex align-items-center justify-content-between">
          <div class="label-container">
            <div class="label-title">Indicate the number of passengers</div>
            <div class="label-subtitle">Including children and infants</div>
          </div>
          <div class="label-counter">
            <div class="incrementer">
              <div @click="decrement"
                   v-bind:class="[disabled_decrement ? 'button-counter decrement button-counter__disabled' : 'button-counter decrement']"></div>
              <span class="incrementer-value">{{ passengers }}</span>
              <div @click="increment"
                   v-bind:class="[disabled_increment ? 'button-counter increment  button-counter__disabled' : 'button-counter increment']"></div>
            </div>
          </div>
        </div>
        <div class="label d-flex align-items-center justify-content-between">
          <div class="label-container">
            <div class="label-title">Add child seats</div>
            <div class="label-subtitle">This is required if children are traveling with you.</div>
          </div>
          <div class="label-counter">
            <div class="custom-checkbox default-checkbox">
              <input type="checkbox" id="child_seat"
                     class="custom-checkbox__input color-grey">
              <label for="child_seat" class="color-grey invert"></label>
            </div>
          </div>
        </div>

      </div>

    </div>
    <div class="form form-container">
      <div class="padding-form">
        <h5 class="form-title">Passenger's details</h5>

        <div class="form-group">
          <label for="name_surname">Name and surname *</label>
          <input type="text" value="" class="form-control form-input" id="name_surname">
          <span class="error-form">Enter your first and last name.</span>
        </div>

        <div class="form-group">
          <label for="passenger_email">E-mail *</label>
          <div class="form-group-with__description">
            <input type="text" value="" class="form-control form-input" id="passenger_email">
            <div class="form-group__description">
              We will send a booking confirmation, voucher, and reminder to this email address.
            </div>
          </div>
          <span class="error-form">Enter your email.</span>
        </div>
        <div class="form-group">
          <label for="passenger_email">Phone number with country code *</label>
          <div class="form-group-with__description">
            <input type="text" value="" placeholder="+90 000 000 00 00" class="form-control form-input"
                   id="passenger_email">
            <div class="form-group__description">

              We need it for urgent communication with you. It must be available on the day of transfer.
            </div>
          </div>
          <span class="error-form">Enter phone number.</span>
        </div>

        <div class="form-privacy">By entering your contact information, you agree to our
          <nuxt-link to="/information/privacy-policy">Privacy Policy.</nuxt-link>

        </div>
      </div>

    </div>
  </div>
</template>

<script>
import ReturnAmount from "./ReturnAmount";
import Route from "./Route";
import VueTimepicker from "vue2-timepicker";
import VueDatepickerUi from "vue-datepicker-ui";

export default {
  name: "TransferDetails",
  props: {
    changed: Boolean,
    edit: Boolean,
    change: Function,
    toggle: Function,
    component: String | Object,
  },
  components: {
    ReturnAmount,
    Route,
    VueTimepicker,
    Datepicker: VueDatepickerUi,
  },
  data() {
    return {
      picker: '',
      return_back_form: "",
      max_passengers: 5,
      min_passengers: 1,
      passengers: 1,
      disabled_increment: false,
      disabled_decrement: true,
      return_amount: '',


    }
  },

  methods: {

    continue_payment(){

    },

    decrement(event) {
      this.passengers -= 1;
      this.disabled_decrement = false;
      this.disabled_increment = false;

      if (this.passengers < this.min_passengers) {
        this.passengers = this.min_passengers;
        this.disabled_decrement = true
      }
    },
    increment(event) {
      const element = event.target;
      this.passengers += 1;
      this.disabled_increment = false;
      this.disabled_decrement = false;
      if (this.passengers > this.max_passengers) {
        this.passengers = this.max_passengers;
        this.disabled_increment = true;
      }
    }
  },
}
</script>

<style scoped>

</style>
