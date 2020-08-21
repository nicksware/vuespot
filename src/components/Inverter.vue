<template>
  <v-skeleton-loader class="mt-5 mx-auto" type="card" :loading="loading">
    <Scroll></Scroll>

    <table class="table" id="inverter">
      <tr v-for="(v, k) in inverter" :key="k">
        <th scope="row">{{ k }}</th>
        <td>{{ v }}</td>
      </tr>
    </table>
  </v-skeleton-loader>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import store from "@/store";

export default {
  name: "Inverter",
  components: {
    Scroll: () => import("@/components/ScrollToBottom"),
  },
  computed: {
    inverter() {
      return store.state.api.inverter;
    },
    loading() {
      return store.getters.isLoadingByKey("inverter");
    },
  },
  watch: {
    inverter() {},
    loading() {},
  },
};
</script>
