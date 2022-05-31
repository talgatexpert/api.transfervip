<template>
	<div>
	<header-admin update="" :breadcrumbs-list="breadcrumbs" ></header-admin>
	</div>
</template>

<script>

import HeaderAdmin from "../../components/admin/HeaderAdmin";
export default {
	name: "clients",
	layout: 'admin',
	components: {HeaderAdmin},
	data(){
		return {
			breadcrumbs: [
				{
					text: 'Kontrol paneli',
					disabled: false,
					href: '/panel',
				},
				{
					text: 'Müşteriler',
					disabled: true,
					href: '/clients',
				},
			],
		}
	}
}
</script>

<style scoped>

</style>