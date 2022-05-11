<template>
	<div class="box__content-dest">
		<div class="d-flex justify-content-between align-items-center">
			<div class="box">
				<span class="start-city">{{ cityFrom }}</span>
				<span class="main-form__separator">-</span>
				<span class="end-city">{{ cityEnd }}</span>
			</div>
			<div class="price">{{ price }}</div>
		</div>
	</div>

</template>

<script>
export default {

	props: {
		cityFrom: String,
		cityEnd: String,
		price: String,
	},
	name: "ReturnAmount"
}
</script>

<style scoped>

</style>
