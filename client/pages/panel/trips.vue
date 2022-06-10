<template>
  <h1>trips</h1>
</template>

<script>
import {getMetaData} from "../../hooks/meta";

export default {
  name: "trips",
  layout: 'admin',
  middleware: 'client',
	async asyncData({$axios, i18n}) {
		return getMetaData($axios, i18n, i18n.t('panel.menu.users'), true)
	},
	head() {
		return {
			title: this.title,
			meta: this.meta
		}
	},
}
</script>

<style scoped>

</style>
