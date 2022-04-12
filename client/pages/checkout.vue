<template>
  <section class="checkout">
    <div class="container">
      <div class="content-info ">
        <div class="row">
          <div class="col-lg-12 col-md-12">

            <div class="progress">
              <div class="progress__content">
                <div v-bind:class="[step_1 ? 'progress-step _active' : 'progress-step']">
                  <div class="top-line">
                    <span class="progress-step__step">1.</span>
                    <span class="progress-step__label">Transfer booking</span>
                  </div>
                  <div class="bottom-line"><span class="progress-circle">

          </span>
                    <div class="progress-divider-holder">
                      <div class="progress-divider"></div>
                      <div v-bind:class="[step_1 ? 'progress-divider-active _completed' : 'progress-divider-active']" ></div>
                    </div>
                  </div>
                </div>
                <div v-bind:class="[step_2 ? 'progress-step _active step-2' : 'progress-step  step-2']">
                  <div class="top-line"><span class="progress-step__step">
      2.
    </span><span class="progress-step__label">
      Payment
    </span></div>
                  <div class="bottom-line"><span class="progress-circle"></span>
                    <div class="progress-divider-holder">
                      <div v-bind:class="[step_2 ? 'progress-divider-active _completed' : 'progress-divider']"></div><!----></div>
                  </div>
                </div>
                <div v-bind:class="[step_3 ? 'progress-step _active step-3' : 'progress-step step-3']">
                  <div class="top-line"><span class="progress-step__step">
      3.
    </span><span class="progress-step__label">
      Confirmation
    </span></div>
                  <div class="bottom-line"><span class="progress-circle"></span>
                    <div class="progress-divider-holder">
                      <div class="progress-divider"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-8 col-md-12">
            <transfer-details :edit="edit" :toggle="toggle" :component="component" :change="changeDetail" :changed="changed"></transfer-details>
            <payment :select_payment="selectPayment" :show_payment="payment_methods"></payment>

            <button class="btn btn-green w-100 mt-3" v-on:click="continue_payment">Continue</button>
          </div>
          <div class="col-lg-4 col-md-12">
            <div class="form-total-end">
            <div class="form form-container">
              <div class="form-result-title__box">

                <i class="i-car"></i>
                <div class="box__content">
                  <div class="box__content-title">Economy</div>
                  <div class="box__content-subtitle">up to 4 passengers, 3 pieces of baggage</div>
                </div>
              </div>
              <div class="padding-form ">

                  <return-amount ></return-amount>
                  <return-amount class="mt-3" v-bind:is="return_amount"></return-amount>



              </div>
            </div>
            <div class="form form-container form-amount">
              <div class="padding-form">
                <div class="box__amount">
                  <div class="total-text">Total:</div>
                  <div class="total">7620 RUB</div>
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>


import Vue from "vue";
import 'vue-datepicker-ui/lib/vuedatepickerui.css';
import 'vue2-timepicker/dist/VueTimepicker.css'
import Route from "../components/Route";
import ReturnAmount from "../components/ReturnAmount";
import TransferDetails from "../components/TransferDetails";
import Payment from "../components/Payment";

export default Vue.extend({

  name: "checkout",
  components: {Payment, TransferDetails, ReturnAmount},
  data(){
    return {
      edit: true,
      changed: false,
      return_amount: '',
      component: '',
      payment_methods: false,
      step_1: true,
      step_2: false,
      step_3: false,
      finish: false,
    }
  },
  methods: {
    continue_payment() {
      this.edit = false;
      this.changed = true;
      this.payment_methods = true;
      window.scrollTo(0,0);
      this.step_2 = true
      if (this.finish){
        this.$router.push('/success');
      }
    },
    selectPayment(){
      this.step_3 = true;
      this.finish = true;
    },
    changeDetail(){
      this.edit = true;
      this.changed = false;
      this.payment_methods = false;
      this.finish = false;
      this.step_2 = false;
      this.step_3 = false;
    },
    toggle(event) {
      event.target.checked ? this.component = Route : this.component = '';
      event.target.checked ? this.return_amount = ReturnAmount : this.return_amount = '';
    },
  }

})
</script>

