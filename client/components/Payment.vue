<template>
  <div class="form form-container" v-if="show_payment">
    <div class="padding-form">
      <h5 class="form-title">Payment methods</h5>
      <div class="payments">
        <div class="radio-custom">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <input type="radio" @change="select_payment" id="card_website" name="radio-group">
              <label for="card_website">By card on the website</label>
            </div>
           <div class="icons d-flex">
             <i class="i-mastercard mr-3"></i>
             <i class="i-visa"></i>
           </div>
          </div>
        </div>
        <div class="radio-custom">
          <input type="radio"  @change="select_payment" id="cache" name="radio-group">
          <label for="cache">Cash to the driver</label>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Payment",
  props: {
    show_payment: Boolean,
    select_payment: Function,
  }
}
</script>

<style scoped>

</style>
