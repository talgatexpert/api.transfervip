<template>
  <div class="container">


  <h1>Information Page {{ this.slug }}</h1>
  </div>
</template>

<script>
export default {
  name: "slug",
  async asyncData({ params }) {
    const slug = params.slug // When calling /abc the slug will be "abc"
    return { slug }
  }
}
</script>

<style scoped>

</style>
