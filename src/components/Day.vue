<template>
  <v-skeleton-loader class="mt-5 mx-auto" type="card" :loading="loading">
    <Scroll />

    <Plotly
      id="day"
      :data="power"
      :layout="{
        title: day.TotalYield + ' Wh in ' + day.TimeStamp,
        xaxis: { fixedrange: true },
        yaxis: { fixedrange: true },
      }"
      :display-mode-bar="false"
      :autoResize="true"
    ></Plotly>
    <v-btn pill block color="primary" v-on:click="add()">
      <v-icon>mdi-plus</v-icon>Add
    </v-btn>
  </v-skeleton-loader>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import axios from "axios";
import store from "@/store";
import { Plotly } from "vue-plotly";

export default {
  name: "Day",
  components: {
    Plotly,
    Scroll: () => import("@/components/ScrollToBottom"),
  },
  data: () => ({
    page: new Date(),
  }),
  created() {
    this.page.setDate(this.page.getDate() - 1);
  },
  computed: {
    loading() {
      return store.getters.isLoadingByKey("day");
    },
    day() {
      return store.state.api.day.length > 0 &&
        undefined !== store.state.api.day[0].TimeStamp &&
        undefined !== store.state.api.day[0].TotalYield
        ? store.state.api.day[0]
        : {
            TimeStamp: new Date(),
            TotalYield: 0,
          };
    },
    power() {
      return [
        {
          x:
            store.state.api.day.length > 0
              ? store.state.api.day.map(function (o) {
                  return undefined !== o.TimeStamp
                    ? o.TimeStamp
                    : new Date().getTime();
                })
              : [new Date().getTime()],
          y:
            store.state.api.day.length > 0
              ? store.state.api.day.map(function (o) {
                  return undefined !== o.Power ? o.Power : 0;
                })
              : [0],
          type: "scatter",
          line: {
            color: "#1976d2",
          },
        },
      ];
    },
  },
  watch: {
    loading() {},
  },
  methods: {
    add() {
      store.commit("loadingByKey", "day");
      const url =
        (store.state.app.url
          ? (store.state.app.secure ? "https://" : "http://") +
            store.state.app.url
          : "") +
        "/api/?r=day/" +
        this.page.toISOString().substring(0, 10);
      if (store.state.api.debug) console.log("GET", url);

      if (store.state.api.day.length == 0) this.page = new Date();

      axios
        .get(url, {
          crossDomain: store.state.app.url ? true : false,
        })
        .then((res) => {
          if (res.status == 200 && res.data.error)
            console.error(res.data.error.message, res.data.error);
          if (res.status == 200 && res.data.day) {
            this.page.setDate(this.page.getDate() - 1);
            store.commit("add", {
              key: "day",
              value: res.data.day,
            });
          }
        })
        .catch(() => store.commit("show_dialog"));
    },
  },
};
</script>
