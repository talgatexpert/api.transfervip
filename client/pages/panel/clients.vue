<template>
	<div>
	<header-admin update="" :breadcrumbs-list="breadcrumbs" ></header-admin>
	</div>
</template>

<script>

import HeaderAdmin from "../../components/admin/HeaderAdmin";
import {getMetaData} from "../../hooks/meta";
export default {
	name: "clients",
	layout: 'admin',
	components: {HeaderAdmin},
	async asyncData({$axios, i18n}) {
		return getMetaData($axios, i18n, i18n.t('panel.menu.clients'), true)
	},
	head() {
		return {
			title: this.title,
			meta: this.meta
		}
	},
	data(){
		return {
			breadcrumbs: [
				{
					text: this.$t('panel.dashboard'),
					disabled: false,
					href: '/' + this.$i18n.locale + '/panel',
				},
				{
					text: this.$t('panel.menu.clients'),
					disabled: true,
					href:'/' + this.$i18n.locale +   '/clients',
				},
			],
		}
	}
}
</script>

<style scoped>

</style>