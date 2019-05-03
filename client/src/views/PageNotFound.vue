<template>
  <div class="error__page-404">
    <b-jumbotron class="text-center mb-0" header="Oops" lead="404 PAGE NOT FOUND">
      <p>
        The page you are searching might have been removed
        <br>or is temporarily unavailable
      </p>

      <router-link to="/">
        <b-button variant="primary">Go To Home Page</b-button>
      </router-link>
    </b-jumbotron>
  </div>
</template>

<script>
export default {
  name: "PageNotFound"
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
@import "../styles/views/error/404-page.scss";
</style>