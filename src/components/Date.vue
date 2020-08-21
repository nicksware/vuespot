<template>
  <v-menu
    v-model="menu"
    :close-on-content-click="false"
    :nudge-right="40"
    transition="scale-transition"
    offset-y
    min-width="290px"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-text-field
        v-model="date"
        v-bind="attrs"
        v-on="on"
        :loading="loading"
        label="Sync data from"
        prepend-icon="mdi-calendar-sync"
        readonly
      ></v-text-field>
    </template>
    <v-date-picker
      v-model="date"
      :type="type"
      :max="new Date().toISOString().substr(0, 10)"
      @input="menu = false"
    ></v-date-picker>
  </v-menu>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import store from "@/store";

export default {
  name: "Date",
  props: {
    loading: Boolean,
    type: String,
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
        store.commit("loadingByKey", this.type == "month" ? "month" : "day");
        store.dispatch("get", {
          key: this.type == "month" ? "month" : "day",
          arg: n,
        });
      },
    },
  },
};
</script>

<style scoped>
.v-input {
  padding-bottom: 0;
  padding-top: 3em;
  padding-left: 1em;
  padding-right: 1em;
}
</style>
