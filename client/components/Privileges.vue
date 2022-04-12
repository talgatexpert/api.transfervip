<template>
  <section class="privileges content-info">
    <div class="container">
      <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
          <div class="privileges-card">
            <i class="home-icon i-crown"></i>
            <h4 class="privileges-card__title">{{ $t('home.comfort') }}</h4>
            <div class="privileges-card__text">{{ $t('home.comfort_info') }}</div>
          </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
          <div class="privileges-card">
            <i class="home-icon i-search"></i>
            <h4 class="privileges-card__title">{{ $t('home.easy_search') }}</h4>
            <div class="privileges-card__text">{{ $t('home.easy_search_info') }}</div>
          </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
          <div class="privileges-card">
            <i class="home-icon i-clock"></i>
            <h4 class="privileges-card__title">{{ $t('home.support') }}</h4>
            <div class="privileges-card__text">{{ $t('home.support_info') }}</div>
          </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
          <div class="privileges-card">
            <i class="home-icon i-price"></i>
            <h4 class="privileges-card__title">{{ $t('home.fix_price') }}</h4>
            <div class="privileges-card__text">{{ $t('home.fix_price_info') }}</div>
          </div>
        </div>
      </div>
    </div>
  </section>

</template>

<script>
export default {
  name: "Privileges"
}
</script>

<style scoped>

</style>
