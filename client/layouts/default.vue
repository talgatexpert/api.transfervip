<template>
	<div>
		<Header/>
		<img :src="logo" class="only-print" width="100px" height="100px" alt=""/>

		<div class="main-layout">
			<Nuxt/>

		</div>
		<h1 class="text-right only-print" v-if="site_info">{{ site_info.name }}</h1>
		<Footer :site_info="site_info" class="noprint"/>
	</div>
</template>

<script>

import Header from "../components/Header";
import Footer from "../components/Footer";
import {settingsProp} from "../props/seetings.prop";

export default {
	fetchOnServer: true,

	name: "default.vue",
	head() {
		return {
			htmlAttrs: {
				lang: this.$i18n.locale
			},
		}
	},
	components: {
		Header,
		Footer,
	},

	data() {
		return {
			logo: require('~/assets/img/logo.png'),
			site_info: null,
		}
	},
	async beforeCreate() {
		this.logo = await this.$store.getters.getLogo
		this.site_info = await this.$store.getters.GET_SITE_INFO;
		if (!this.site_info) {
			this.site_info = settingsProp;
		}
	},
}
</script>

