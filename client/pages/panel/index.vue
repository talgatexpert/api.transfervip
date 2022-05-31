<template>

	<div>
		<header-admin update="" :breadcrumbs-list="breadcrumbs"></header-admin>
		<v-container class="container-offset-left-right-top">
			<v-row>
				<v-col cols="12" lg="3" md="4" sm="6" class="bg-white">
					<v-skeleton-loader :loading="loading" type="card">

							<v-card-text>
							<div class="d-flex flex-column justify-center align-center">
								<v-sheet  class=" theme--light primary lighten-5 rounded-circle mb-5" >

									<v-icon color="primary notranslate">
										mdi-check
									</v-icon>

								</v-sheet>
								<h3 class="font-weight-bold ma-0 "> 9.2k </h3><h6 class="caption mb-8 font-weight-regular">
								Impressions </h6>
								<button type="button"
								        class="caption   hover:white--text  font-weight-bold py-2 px-4  d-inline-flex align-center v-btn v-btn--has-bg v-btn--rounded theme--light v-size--default primary--text primary lighten-5 hover:bg-primary"
								        control-id="ControlID-5"><span class="v-btn__content"><i aria-hidden="true"
								                                                                 class="v-icon notranslate  caption mr-1  mdi mdi-arrow-down-drop-circle-outline theme--light"></i><span>10.4%</span></span>
								</button>
							</div>
							</v-card-text>
					</v-skeleton-loader>
				</v-col>
			</v-row>
		</v-container>


	</div>

</template>

<script>
import HeaderAdmin from "../../components/admin/HeaderAdmin";

export default {
	name: "index",
	layout: 'admin',
	components: {HeaderAdmin},
	data() {
		return {
			loading: false,
			breadcrumbs: [
				{
					text: 'Dashboard',
					disabled: false,
					href: '/panel',
				},
			],
		}
	},

	async mounted() {

		await setTimeout(async () => {
		}, 2000)
		this.loading = false;
	}

}
</script>

<style scoped>

</style>
