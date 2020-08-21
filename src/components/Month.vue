<template>
  <v-skeleton-loader class="mt-5 mx-auto" type="card" :loading="loading">
    <Scroll />

    <Plotly
      id="month"
      :data="months"
      :layout="{
        title: begin.TotalYield + ' Wh in ' + begin.TimeStamp,
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
  name: "Month",
  components: {
    Plotly,
    Scroll: () => import("@/components/ScrollToBottom"),
  },
  data: () => ({
    page: new Date(),
  }),
  created() {
    this.page.setMonth(this.page.getMonth() - 1);
  },
  computed: {
    loading() {
      return store.getters.isLoadingByKey("month");
    },
    begin() {
      return store.state.api.month[0]
        ? store.state.api.month[0]
        : {
            TotalYield: 0,
            TimeStamp: new Date(),
          };
    },
    months() {
      return [
        {
          x:
            store.state.api.month.length > 0
              ? store.state.api.month.map(function (o) {
                  return undefined !== o.TimeStamp ? o.TimeStamp : new Date();
                })
              : [new Date()],
          y:
            store.state.api.month.length > 0
              ? store.state.api.month.map(function (o) {
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
  },
  watch: {
    loading() {},
    begin() {},
  },
  methods: {
    add() {
      store.commit("loadingByKey", "month");
      const url =
        (store.state.app.url
          ? (store.state.app.secure ? "https://" : "http://") +
            store.state.app.url
          : "") +
        "/api/?r=month/" +
        this.page.toISOString().substring(0, 10);
      if (store.state.api.debug) console.log("GET", url);

      if (store.state.api.month.length == 0) this.page = new Date();

      axios
        .get(url, {
          crossDomain: store.state.app.url ? true : false,
        })
        .then((res) => {
          if (res.status == 200 && res.data.error)
            console.error(res.data.error.message, res.data.error);
          if (res.status == 200 && res.data.month) {
            this.page.setMonth(this.page.getMonth() - 1);
            store.commit("add", {
              key: "month",
              value: res.data.month,
            });
          }
        })
        .catch(() => store.commit("show_dialog"));
    },
  },
};
</script>
