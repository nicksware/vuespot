<template>
  <v-skeleton-loader class="mt-5 mx-auto" type="card" :loading="loading">
    <Scroll></Scroll>

    <Plotly
      id="years"
      :data="years"
      :layout="{
        xaxis: { fixedrange: true },
        yaxis: { fixedrange: true },
      }"
      :display-mode-bar="false"
      :autoResize="true"
    ></Plotly>
  </v-skeleton-loader>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import store from "@/store";
import { Plotly } from "vue-plotly";

export default {
  name: "Years",
  components: {
    Plotly,
    Scroll: () => import("@/components/ScrollToBottom"),
  },
  computed: {
    years() {
      return [
        {
          x:
            store.state.api.year.length > 0
              ? store.state.api.year.map(function (o) {
                  return undefined !== o.Year
                    ? o.Year
                    : new Date().getFullYear();
                })
              : [new Date().getFullYear()],
          y:
            store.state.api.year.length > 0
              ? store.state.api.year.map(function (o) {
                  return undefined !== o.DayYield ? o.DayYield : 0;
                })
              : [0],
          type: "bar",
          marker: {
            color: "#1976d2",
          },
        },
      ];
    },
    loading() {
      return store.getters.isLoadingByKey("year");
    },
  },
  watch: {
    loading() {},
  },
};
</script>
