<template>
  <div class="car-card">
    <div class="car-card__picture border-card">
      <div class="car-card__picture--thumb">
        <img src="https://fscdn.kiwitaxi.com/assets/images/vehicles/1.png" alt="Economy">
      </div>
      <div class="car-card__picture--name">
        Skoda Octavia
      </div>


    </div>
    <div class="car-card__description">
      <div class="car-card__description--class">
        Economy
      </div>
      <div class="car-card__description--baggage">
        <span class="item-icon"><i class="icon i-person"></i><span>5</span></span>
        <span class="item-icon ">     <i class="icon i-baggage"></i><span>3</span></span>
      </div>
      <div class="car-card__description--services">

        <span class="item-icon"> <i class="icon i-close"></i> <span>Free cancellation</span></span>
        <span class="item-icon"> <i class="icon i-time"></i> <span>15 min of waiting included</span></span>
      </div>
    </div>
    <div class="car-card__offer">
      <div class="car-card__offer--price">
        58919 RUB
      </div>
      <button class="btn btn-green" @click="setTransfer(car_id)">Select</button>
      <div class="car-card__offer--payments">
        <i class="icon i-cache"></i>
        <i class="icon i-mastercard"></i>
        <i class="icon i-visa"></i>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Card",

  props: {
    car_id: String,
  },
  methods: {
    randomInteger(min, max) {
      return Math.floor(Math.random() * max) + min;
    },

    setTransfer(car_id) {
      const rand = () => Math.random(0).toString(36).substr(2);
      let booking_token = this.randomInteger(1, 9999999999);
      this.$router.push({path: '/checkout', query: {booking_token, car_id}});
    }

  },
}
</script>

<style scoped>

</style>
