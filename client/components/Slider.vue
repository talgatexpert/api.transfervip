<template>
  <swiper ref="mySwiper" :options="swiperOptions">
    <swiper-slide v-for="(review,key ) in reviews" :key="key">
      <div class="slider-card">
        <div class="slider-card__title">
          <div class="slider-card__title-name">{{ review.name }}</div>
          <div class="stars">
            <i v-for="i in 5"
               v-bind:class="[ i === review.rating || i <= review.rating ? 'icon i-star' : 'icon i-star-empty']"></i>
          </div>

        </div>
        <div class="slider-card__text">
          {{ review.body }}
        </div>
        <div class="slider-card__date">
          {{ review.date }}
        </div>
      </div>
    </swiper-slide>

  </swiper>

</template>

<script>

import {Swiper, SwiperSlide} from "vue-awesome-swiper";

export default {
  name: "Slider",
  components: {
    Swiper,
    SwiperSlide,
  },
  computed: {
    swiper(context) {
      return context.$refs.mySwiper.$swiper
    },
  },
  mounted() {
    this.swiper.slideTo(3, 1000, false)
  },
  props: {
    swiperOptions: Object,
    reviews: Array

  }
}
</script>

<style scoped>

</style>
