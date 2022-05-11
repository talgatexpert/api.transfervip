<template>
	<div class="container">



		<div class="flex-center position-ref full-height" v-if="error.statusCode === 404">
			<div class="d-flex align-items-center">
			<div class="code">
				404
			</div>
			<div class="message" style="padding: 10px;">
				{{ $t('404')}}
			</div>
			</div>
			<NuxtLink class="d-block text-center text-dark " :to="`/${$i18n.locale === 'tr' ? '' : $i18n.locale}`">{{ $t('home_page')}}</NuxtLink>

		</div>
		<h1 v-else class="text-center">An error occurred</h1>
		<NuxtLink class="d-block text-center text-dark " :to="`/${$i18n.locale === 'tr' ? '' : $i18n.locale}`">{{ $t('home_page')}}</NuxtLink>


	</div>
</template>

<script>
export default {
	props: ['error'],
	layout: 'error'
}
</script>

<style scoped>


.full-height{
	height: 100vh;
}
.flex-center {
	align-items: center;
	display: flex;
	justify-content: center;
	flex-direction: column;
}

.position-ref {
	position: relative;
}

.code {
	border-right: 2px solid;
	font-size: 26px;
	padding: 0 15px 0 15px;
	text-align: center;
	display: flex;
	flex-direction: column;
}

.message {
	font-size: 18px;
	text-align: center;
}
</style>