<template>
  <div>
    <Header />
    <img  :src="require(`~/assets/img/${logo}`)"class="only-print" alt=""/>

    <div class="main-layout">
      <Nuxt/>

    </div>
    <Footer class="noprint"/>
  </div>
</template>

<script>

export default {
  name: "default.vue",
  head () {
    return {
      htmlAttrs: {
        lang: this.$i18n.locale
      },
    }
  },
  computed: {
    logo(ctx){
      return ctx.$store.getters['getLogo'];
    },
  }
}
</script>

