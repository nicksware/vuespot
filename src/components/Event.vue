<template>
  <v-data-table
    id="event"
    :headers="headers"
    :items="event"
    :page.sync="page"
    :rows-per-page="max"
    :items-per-page="max"
    :loading="loading"
    loading-text="Loading... Please wait"
    class="elevation"
  >
    <template v-slot:footer>
      <v-pagination
        block
        v-model="page"
        total-visible="7"
        :length="
          Math.max(more ? page + 1 : page, [].concat(event).length / max)
        "
        next="false"
      ></v-pagination>
    </template>
  </v-data-table>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import axios from "axios";
import store from "@/store";

export default {
  name: "Event",
  data: () => ({
    page: 1,
    pageCounter: 2,
    more: true,
    headers: [
      { value: "Category", text: "Category" },
      { value: "EventCode", text: "EventCode" },
      { value: "EventGroup", text: "EventGroup" },
      { value: "EventType", text: "EventType" },
      { value: "NewValue", text: "NewValue" },
      { value: "OldValue", text: "OldValue" },
      { value: "Tag", text: "Tag" },
      { value: "TimeStamp", text: "TimeStamp" },
    ],
  }),
  computed: {
    event() {
      return store.state.api.event;
    },
    max() {
      return store.state.api.max;
    },
    loading() {
      return store.getters.isLoadingByKey("event");
    },
  },
  watch: {
    page(n) {
      console.log("new page", n);
      if (!this.loading && n >= this.pageCounter) this.add();
      else if (this.loading && n < this.pageCounter)
        store.commit("cancelByKey", "event");
    },
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
          this.more =
            res.data.events &&
            res.data.events.length &&
            res.data.events.length > 0;
          if (res.status == 200 && res.data.events) {
            this.page++;
            this.pageCounter++;
            store.commit("add", {
              key: "event",
              value: res.data.events,
            });
          } else store.commit("cancelByKey", "event");
        })
        .catch(() => store.commit("show_dialog"));
    },
  },
};
</script>
