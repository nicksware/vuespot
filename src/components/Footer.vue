<template>
  <v-footer>
    <v-btn
      aria-label="Reload"
      :loading="loading"
      :disabled="loading"
      small
      fab
      depressed
      @click="reload()"
    >
      <v-icon dark>mdi-reload</v-icon>
    </v-btn>

    <v-spacer></v-spacer>

    <v-btn
      text
      rel="license"
      href="http://creativecommons.org/licenses/by-nc-sa/3.0/"
    >
      <v-img
        height="15"
        width="80"
        alt="Creative Commons License"
        src="https://i.creativecommons.org/l/by-nc-sa/3.0/80x15.png"
      >
      </v-img>
    </v-btn>

    <span class="d-none d-lg-flex">
      This work is licensed under a
      <a
        rel="license"
        class="ml-1"
        href="http://creativecommons.org/licenses/by-nc-sa/3.0/"
      >
        Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported
        License </a
      >.
    </span>

    <v-spacer></v-spacer>

    <v-btn
      ref="button"
      aria-label="scroll to top"
      small
      fab
      depressed
      append
      @click="$vuetify.goTo(0)"
    >
      <v-icon>mdi-chevron-up</v-icon>
    </v-btn>
  </v-footer>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import store from "@/store";

export default {
  name: "Footer",
  computed: {
    loading() {
      return store.getters.isLoading;
    },
  },
  watch: {
    loading() {},
  },
  methods: {
    reload() {
      store.commit("show_dialog");
    },
  },
};
</script>
