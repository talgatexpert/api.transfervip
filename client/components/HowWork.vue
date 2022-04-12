<template>
  <section class="how-work content-info">
    <div class="container">
      <h2 class="content-info__title text-center">{{ $t('how_work.title') }}</h2>

      <div class="how-work__content">
        <div class="row">
          <div class="col-lg-4  offset-lg-1 col-md-12">
            <div class="roadmap">
              <div class="roadmap-item">
                <div class="roadmap-item__prefix roadmap-item__prefix-start"></div>
                <div class="roadmap-item__text">{{ $t('how_work.route') }}</div>
              </div>
              <div class="roadmap-item">
                <div class="roadmap-item__prefix  color-green "></div>
                <div class="roadmap-item__text">{{ $t('how_work.price') }}</div>
              </div>
              <div class="roadmap-item">
                <div class="roadmap-item__prefix color-orange"></div>
                <div class="roadmap-item__text">{{ $t('how_work.option') }}</div>
              </div>
              <div class="roadmap-item">
                <div class="roadmap-item__prefix color-green"></div>
                <div class="roadmap-item__text">{{ $t('how_work.meet') }}</div>
              </div>
              <div class="roadmap-item">
                <div class="roadmap-item__prefix roadmap-item__prefix-finish"></div>
                <div class="roadmap-item__text">{{ $t('how_work.final') }}</div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 offset-lg-1 col-md-12">
            <div class="how-work__content-text" v-html="howWorkText">

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</template>

<script>
export default {
  name: "HowWork",
  props: {
    howWorkText: String
  }
}
</script>

