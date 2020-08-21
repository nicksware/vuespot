<template>
  <v-skeleton-loader class="mt-5 mx-auto" type="table" :loading="loading">
    <Scroll></Scroll>

    <v-data-table
      id="event"
      :headers="headers"
      :items="event"
      class="elevation"
    ></v-data-table>
    <v-btn pill block color="primary" v-on:click="add()">
      <v-icon>mdi-plus</v-icon>More events
    </v-btn>
  </v-skeleton-loader>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import axios from "axios";
import store from "@/store";

export default {
  name: "Event",
  components: {
    Scroll: () => import("@/components/ScrollToBottom"),
  },
  data: () => ({
    page: 1,
    headers: [
      { value: "Category" },
      { value: "EventCode" },
      { value: "EventGroup" },
      { value: "EventType" },
      { value: "NewValue" },
      { value: "OldValue" },
      { value: "Tag" },
      { value: "TimeStamp" },
    ],
  }),
  computed: {
    event() {
      return store.state.api.event;
    },
    loading() {
      return store.getters.isLoadingByKey("event");
    },
  },
  watch: {
    event() {},
    loading() {},
  },
  methods: {
    add() {
      store.commit("loadingByKey", "event");
      const url =
        (store.state.app.url
          ? (store.state.app.secure ? "https://" : "http://") +
            store.state.app.url
          : "") +
        "/api/?r=event/" +
        this.page;
      if (store.state.api.debug) console.log("GET", url);

      axios
        .get(url, {
          crossDomain: store.state.app.url ? true : false,
        })
        .then((res) => {
          if (res.status == 200 && res.data.error)
            console.error(res.data.error.message, res.data.error);
          if (res.status == 200 && res.data.events) {
            this.page++;
            store.commit("add", {
              key: "event",
              value: res.data.events,
            });
          }
        })
        .catch(() => store.commit("show_dialog"));
    },
  },
};
</script>
