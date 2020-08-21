<template>
  <v-skeleton-loader class="mt-5 mx-auto" type="card" :loading="loading">
    <Scroll></Scroll>

    <v-card flat>
      <v-sparkline
        :value="power"
        :gradient="['#1976d2']"
        type="bar"
        smooth
        fill
        auto-line-width
        auto-draw
      >
      </v-sparkline>
    </v-card>
  </v-skeleton-loader>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import store from "@/store";

export default {
  name: "Years",
  components: {
    Scroll: () => import("@/components/ScrollToBottom"),
  },
  computed: {
    power() {
      return store.state.api.year.map((e) => {
        return parseInt(e.DayYield);
      });
    },
    loading() {
      return store.getters.isLoadingByKey("year");
    },
  },
  watch: {
    power() {},
    loading() {},
  },
};
</script>
