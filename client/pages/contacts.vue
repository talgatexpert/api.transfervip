<template>
  <div class="main-layout">
    <div class="maps">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12770.305088710937!2d30.6189308!3d36.8526229!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x7c1e1929663c7e7a!2zVmlsbGEgRG9tIEVtbGFrIFJlYWwgRXN0YXRlINCd0LXQtNCy0LjQttC40LzQvtGB0YLRjCBMVEQu!5e0!3m2!1sru!2str!4v1649252403262!5m2!1sru!2str"
        width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h3 v-html="$t('contacts.subtitle')" class="home-subtitle mt-4"></h3>
        </div>
        <div class="col-12">
          <div class="contacts-info">
            <div v-for="(item, key) in informations" class="row" :key="key">
              <div class="col-4">
                <div class="contacts-info__label">{{ item.name }}:</div>
              </div>
              <div class="col-6">
                <div class="contacts-info__value">{{ item.value }}</div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <v-form class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <v-text-field
            label="Name and surname"
          ></v-text-field>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <v-text-field
            v-model="email"
            :rules="emailRules"
            label="Email address"
          ></v-text-field>
        </div>
        <div class="col-lg-6 col-md-12">
          <v-textarea
            counter
            label="Message"
          ></v-textarea>
        </div>
        <div class="col-lg-6 col-md-12 d-flex  justify-content-center flex-column">

          <recaptcha/>
          <span class="text-danger" v-if="captcha_error">Failed to execute</span>
          <v-btn
            depressed
            @click="onSubmit()"
            class="btn-green btn-send"
          >
            Submit
          </v-btn>
        </div>
      </v-form>

    </div>
  </div>
</template>

<script>
import login from "./login";

export default {
  name: "contacts",
  async asyncData({app}) {
    const informations = [
      {
        name: app.i18n.t('contacts.company_name'),
        value: 'Lux Elit Travel'
      },
      {
        name: app.i18n.t('contacts.address'),
        value: 'Liman Mah. 7.Sok.Kösem Apt. №22/3'
      },
      {
        name: app.i18n.t('contacts.city'),
        value: 'Antalya'
      },
      {
        name: app.i18n.t('contacts.country'),
        value: 'Turkey'
      },
      {
        name: app.i18n.t('contacts.email'),
        value: 'villadom1@gmail.com'
      },
    ];

    return {informations}
  },

  data() {
    return {
      informations: [],
      valid: false,
      email: '',
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+/.test(v) || 'E-mail must be valid',
      ],
      errors: [],
      captcha_error: false,
    }
  },

  async mounted() {
    try {
      if (localStorage.getItem('recaptcha') !== "") {
        this.$recaptcha.destroy();

      } else {
      }
    } catch (e) {
      console.error(e);
    }
  },
  methods: {

    async captcha() {
      await this.$recaptcha.getResponse()
        .then(result => {
          localStorage.setItem('recaptcha', result);
        })
        .catch(error => this.captcha_error = true)
    },

    async onSubmit() {
      this.captcha_error = false;
      await this.captcha().then(

      )


    },
  }
}
</script>

<style scoped>

</style>
