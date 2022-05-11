<template>
  <div class="indigo mb-5" >
    <v-container>

      <breadcrumbs color-white="color-white" :items="breadcrumbsList" class="pl-0 "></breadcrumbs>
      <v-row>
        <v-col cols="12">
          <div class="d-flex justify-content-between">
            <h3 class="text-light">{{ pageTitle }}</h3>
            <v-btn v-if="showUpdate" @click="update">Update settings</v-btn>
          </div>
        </v-col>
      </v-row>

    </v-container>

  </div>
</template>

<script>
import Breadcrumbs from "../Breadcrumbs";
export default {
  name: "HeaderAdmin",
  props: {
    update: Function | String | Boolean,
    breadcrumbsList: Array,
    pageTitle: String,
    showUpdate: Boolean | false
  },
	components: {Breadcrumbs},
}
</script>

<style scoped>

</style>
