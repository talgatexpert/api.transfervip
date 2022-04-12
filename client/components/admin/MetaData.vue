<template>
    <div>
      <v-text-field
        :value="meta_title"
        label="Meta title"
        required
      ></v-text-field>
      <v-text-field
        :value="meta_description"
        label="Meta description"
        required
      ></v-text-field>
      <v-text-field
        :value="meta_keyword"
        label="Meta keyword"
        required
      ></v-text-field>
    </div>
</template>

<script>
export default {
  name: "MetaData",
  props: {
    meta_title: String,
    meta_keyword: String,
    meta_description: String,
  }
}
</script>

<style scoped>

</style>
