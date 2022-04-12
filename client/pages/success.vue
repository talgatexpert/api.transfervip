<template>
  <section class="checkout">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="content-info">
            <div class="form form-container">
              <div class="padding-form">
                <h4 class="form-title text-center">Successfully Reserve Finished</h4>
              </div>
              <div class="row m-0">
                <div class="success-data offset-lg-2 col-lg-8 col-md-12">
                  <div class="success-data__row">
                    <div class="data-name">Name Surname:</div>
                    <div class="data-value">Mehemet CanOglu</div>
                  </div>
                  <div class="success-data__row">
                    <div class="data-name">Hotel:</div>
                    <div class="data-value">SunRays</div>
                  </div>
                  <div class="success-data__row">
                    <div class="data-name">Flight Number:</div>
                    <div class="data-value">JHD5155</div>
                  </div>
                  <div class="success-data__row">
                    <div class="data-name">Email:</div>
                    <div class="data-value">mehmet@mail.com</div>
                  </div>
                  <div class="success-data__row">
                    <div class="data-name">Phone:</div>
                    <div class="data-value">+90 555 555 55 55</div>
                  </div>
                  <div class="success-data__row">
                    <div class="data-name">Passengers:</div>
                    <div class="data-value">2</div>
                  </div>
                  <div class="success-data__row">
                    <div class="data-name">Child Seat:</div>
                    <div class="data-value">Yes</div>
                  </div>
                  <div class="success-data__row">
                    <div class="data-name">Payment Method:</div>
                    <div class="data-value">With card on website</div>
                  </div>
                  <div class="success-data__row">
                    <div class="data-name">Return trip:</div>
                    <div class="data-value">No</div>
                  </div>
                </div>

                <div class="col-lg-8 offset-lg-2 col-md-12">
                  <button class="btn btn-green noprint" @click="print">Print</button>
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
export default {
  name: "success.vue",
  methods: {
    print () {
      window.print();
    },
  }
}
</script>

<style scoped>

</style>
