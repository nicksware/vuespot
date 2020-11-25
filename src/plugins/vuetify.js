import Vue from "vue";
import Vuetify from "vuetify/lib";

Vue.use(Vuetify);

const darkMediaQuery = window.matchMedia("(prefers-color-scheme: dark)");

darkMediaQuery.addEventListener("change", () => {
  this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
});

export default new Vuetify({
  theme: { dark: darkMediaQuery.matches },
});
