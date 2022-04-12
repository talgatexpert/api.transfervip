<template>
  <v-breadcrumbs :items="items">
    <template v-slot:item="{ item }">
      <v-breadcrumbs-item
        :class="colorWhite"
        :to="item.href"
        :disabled="item.disabled"
      >
        {{ item.text.toUpperCase() }}
      </v-breadcrumbs-item>
    </template>
  </v-breadcrumbs>
</template>

<script>
export default {
  name: "Breadcrumbs",
  props: {
    items: Array,
    colorWhite: 'color-dark' | String,
  }
}
</script>

<style >
.color-white a{
  color: #fff;
}
.color-white .v-breadcrumbs__item--disabled{
  color: #d2d2d2 !important;
}
</style>
