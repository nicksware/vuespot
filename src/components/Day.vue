<template>
  <div>
    <v-skeleton-loader class="mt-5 mx-auto" type="card" :loading="loading">
      <v-card flat>
        <v-card-title> {{ today / 1000 }} kWh </v-card-title>
        <v-sparkline
          :value="power"
          color="primary"
          stroke-linecap="round"
          smooth
          fill
          auto-draw
        >
        </v-sparkline>
      </v-card>
    </v-skeleton-loader>

    <Date type="date" :loading="loading"></Date>
  </div>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import store from "@/store";

export default {
  name: "Day",
  components: {
    Date: () => import("@/components/Date"),
  },
  data: () => ({
    menu: false,
  }),
  computed: {
    date: {
      get() {
        return store.state.app.date;
      },
      set(n) {
        store.commit("date", n);
        store.commit("loadingByKey", "day");
        store.dispatch("get", { key: "day", arg: n });
      },
    },
    power() {
      return store.state.api.day.map((e) => {
        return parseInt(e.Power);
      });
    },
    today() {
      return store.state.api.month.length > 0 &&
        undefined !== store.state.api.month[0].DayYield
        ? store.state.api.month[0].DayYield
        : 0;
    },
    loading() {
      return store.getters.isLoadingByKey("day");
    },
  },
  watch: {
    power() {},
    today() {},
    loading() {},
  },
};
</script>
