<template>
  <div class="error__page-404 text-center mt-auto">
    <h1 class="page__title">Oops!</h1>
    <p class="page__subtitle">404 PAGE NOT FOUND</p>
    <p class="page__description">
      The page you are searching might have been removed
      <br>or is temporarily unavailable
    </p>
    <router-link to="/">
      <b-button variant="primary">Go To Home Page</b-button>
    </router-link>
  </div>
</template>

<script>
export default {
  name: "PageNotFound"
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
@import "../styles/views/page-not-found.scss";
</style>