<template>
  <v-app>
    <sidebar/>
    <v-main >
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


