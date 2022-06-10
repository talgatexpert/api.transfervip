<template>

	<b-modal :hide-footer="true" :title="title" :class="classesModal" :id="id">

		<div class="modal-container">
			<div class="modal-content">
				<div class="form-group" v-for="(input, key) in items" :key="key">
					<label :for="input.id">{{ input.translatedName }}</label>
					<input :type="input.type" v-model="input.value" :placeholder="input.translatedName" :id="input.id" class="form-control">
					<span  class="text-danger">{{input.error}}</span>
				</div>

				<button @click="action(items, { modalId: id , id: '#btnShow'}, $event )" class="btn btn-green btn-send">Send
				</button>
			</div>
		</div>

	</b-modal>
</template>

<script>

export default {
	name: "Modal",
	props: {
		classesModal: Array,
		showModal: Boolean,
		inputs: Array,
		id: String,
		title: String,
		action: Function,
		errors: [{}],
	},
	components: {},

	data() {
		return {items: []}
	},
	mounted() {
	this.items = JSON.parse(JSON.stringify(this.inputs))
	},



	methods: {
		hideModal() {
			this.$root.$emit('bv::hide::modal', this.id, '#btnShow')
		},
	}
}
</script>

<style scoped>

</style>
