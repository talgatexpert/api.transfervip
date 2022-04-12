<template>

  <section>
    <div class="main-wrapper">
      <div class="container">
        <h1 v-html="$t('about.title')" class="home-title pt-lg-5"></h1>


        <div class="content-text">
          <p v-html="$t('about.content')"></p>
        </div>

        <how-work :howWorkText="howWorkText"></how-work>
      </div>
    </div>
  </section>

</template>

<script>
import HowWork from "~/components/HowWork";

export default {
  name: "about",
  layout: "default",
  components: {
    HowWork
  },

  async asyncData({app}) {
    await app.store.dispatch('howWorkText', {})
    const howWorkText = await app.store.getters['getHowWorkText'];

    return {howWorkText}

  }

}
</script>


