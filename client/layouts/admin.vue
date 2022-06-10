<template>
  <v-app >
<!--		<div class="background-loader" :class="loading ? 'active' : ''"></div>-->
<!--	  <scale-loader :loading="loading" margin="5px" width="10px" color="#032b80" height="100px" class="position-center"></scale-loader>-->
    <sidebar @logout="logout"/>
    <v-main style="background: #F4F4F8" >
      <Nuxt></Nuxt>
    </v-main>

  </v-app>
</template>


<script>
import Sidebar from "../components/admin/Sidebar";
import ScaleLoader from "vue-spinner/src/ScaleLoader.vue";
export default {
  name: "admin",
  components: {Sidebar, ScaleLoader},
  middleware: 'notauthenticated',
	data(){
		return {
			loading: false
		}
	},
	methods: {
		async logout() {

			this.loading = true
			return await this.$auth.logout({
				url: 'logout'
			}).then(async result => {
				await this.$store.dispatch('CLEAR_ROLE')
				// this.$data.loading = false

			}).catch(e => {

				console.log(e)
			}).finally(()=> {
				this.$data.loading = false
			})
		},
	}
}
</script>
<style>

.background-loader{
	display: none;
	position: absolute;
	top: 50%;
	left: 50%;
	background: rgba(69, 68, 68, 0.31);
	z-index: 999;
	width: 100%;
	transform: translate(-50%, -50%);
	height: 100%;

}
.background-loader.active{display: block}
.position-center{
	position: absolute;
	top: 50%;
	left: 50%;
	z-index: 1000;
	/*background: #0756ff;*/
	transform: translate(-50%, -50%);
}
</style>


