import Vue from "vue";
import Router from "vue-router";
import Home from "./views/Home.vue";
import PageNotFound from "./views/PageNotFound.vue";

Vue.use(Router);

export default new Router({
  mode: "history",
  base: process.env.BASE_URL,
  routes: [
    {
      path: "/",
      name: "home",
      component: Home
    },
    // default route, when none of the above matches:
    { path: "*", component: PageNotFound }
  ]
});
