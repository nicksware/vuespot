<template>
  <v-skeleton-loader
    id="navigation"
    class="mx-auto"
    type="actions"
    :loading="loading"
  >
    <nav>
      <v-btn text v-for="(v, k) in favorites" :key="k" :href="v">{{ k }}</v-btn>
    </nav>
  </v-skeleton-loader>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import store from "@/store";

export default {
  name: "Favorites",
  computed: {
    favorites() {
      return store.state.api.favorites;
    },
    loading() {
      return store.getters.isLoadingByKey("favorites");
    },
  },
  watch: {
    favorites() {},
    loading() {},
  },
};
</script>
