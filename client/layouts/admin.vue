<template>
  <v-app>
    <sidebar/>
    <v-main style="background: #F4F4F8" >
      <Nuxt></Nuxt>
    </v-main>
  </v-app>
</template>


<script>
import Sidebar from "../components/admin/Sidebar";
export default {
  name: "admin",
  components: {Sidebar},
  middleware: 'notauthenticated',
}
</script>


