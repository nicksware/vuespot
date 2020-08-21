<template>
  <v-dialog v-model="dialog" max-width="290">
    <v-card>
      <v-card-title class="headline" center>
        <v-icon>mdi-database</v-icon> Data source
      </v-card-title>

      <v-card-text>
        If the default data source cannot be found. Then, provide the address to
        your local local data source.
      </v-card-text>

      <v-card-text>
        <v-text-field
          v-model="apiurl"
          label="IP or FQDN"
          hint="e.g. 192.168.2.1, example.com or empty for the default data source"
        ></v-text-field>
        <v-checkbox
          v-model="secure"
          label="This is a secure connection."
        ></v-checkbox>
      </v-card-text>

      <v-card-actions>
        <v-btn color="primary" text block @click="reload()">
          <v-icon>mdi-database-refresh</v-icon>
          Save and reload
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import store from "@/store";

export default {
  name: "Dialog",
  computed: {
    dialog: {
      get() {
        return store.state.app.dialog;
      },
      set(n) {
        store.commit("dialog", n);
      },
    },
    apiurl: {
      get() {
        return store.state.app.url;
      },
      set(n) {
        store.commit("apiurl", n);
      },
    },
    secure: {
      get() {
        return store.state.app.secure;
      },
      set(n) {
        store.commit("secureapi", n);
      },
    },
  },
  methods: {
    reload() {
      if (store.state.api.debug) console.log("reload application...");
      this.dialog = false;
      store.dispatch("get_all");
    },
  },
};
</script>
