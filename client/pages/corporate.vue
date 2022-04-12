<template>
  <section>
  <div class="main-form bg-corp">
    <div class="container">
      <h1 v-html="$t('corp.title')" class="home-title m-0"></h1>
      <p v-html="$t('corp.subtitle')" class="home-subtitle"></p>


      <button class="btn btn-green d-flex mt-5 mx-auto"  v-b-modal.business-corp>Sign up</button>
    </div>
  </div>

    <div class="container">
      <div class="row content-info">
        <div class="col-lg-3 col-md-6">
          <div class="privileges-card">
            <div class="privileges-card__title">+5000</div>
            <div class="privileges-card__text">Worldwide destinations</div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="privileges-card">
            <div class="privileges-card__title">More than 1000
            </div>
            <div class="privileges-card__text">Happy customers every day
            </div>
          </div>

        </div>
        <div class="col-lg-3 col-md-6">
          <div class="privileges-card">
            <div class="privileges-card__title">24/7</div>
            <div class="privileges-card__text">Customer support services</div>
          </div>

        </div>
        <div class="col-lg-3 col-md-6">
          <div class="privileges-card">
            <div class="privileges-card__title">100%</div>
            <div class="privileges-card__text">Quality of transportation services</div>
          </div>

        </div>
      </div>

      <div class="row content-info corp-clients-block">
        <div class="col-12">
          <h2 class="content-info__title text-center">Our clients</h2>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <img src="~/assets/img/client.png"  alt="">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <img src="~/assets/img/client.png" alt="">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <img src="~/assets/img/client.png" alt="">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <img src="~/assets/img/client.png" alt="">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <img src="~/assets/img/client.png" alt="">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <img src="~/assets/img/client.png" alt="">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <img src="~/assets/img/client.png" alt="">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <img src="~/assets/img/client.png" alt="">
        </div>
      </div>
    </div>

    <div class="container-fluid bg-dark-blue corp-clients-prefooter">
      <button class="btn btn-green" v-b-modal.business-corp>Sign Up</button>
    </div>

    <modal :classes-modal="['modal', 'modal-form']" :title="$t('menu.corporate')" id="business-corp" :inputs="inputs" :show-modal="true"></modal>
  </section>

</template>


<script>

import Modal  from "../components/Modal";
export default {
  name: "corporate",
  components: {
    Modal,
  },
  data(){
    return {
      inputs: [
        {
          name: "Name",
          id: "name",
          translatedName: 'Name',
          type: "text",
        },
        {
          name: "company_name",
          id: "company_name",
          translatedName: 'Company name',
          type: "text",
        },
        {
          name: "Phone",
          id: "Phone",
          translatedName: 'Phone',
          type: "text",
        },
      ]
    }
  }

}
</script>
