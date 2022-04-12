<template>

    <b-modal :hide-footer="true" :title="title" :class="classesModal" :id="id">

      <div class="modal-container">
        <div class="modal-content">
          <div class="form-group" v-for="(input, key) in inputs" :key="key">
            <label :for="input.id">{{input.translatedName}}</label>
            <input :type="input.type" :placeholder="input.translatedName" :id="input.id" class="form-control">
          </div>

          <button @click="hideModal" class="btn btn-green btn-send">Send</button>
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
  },
  components: {

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
