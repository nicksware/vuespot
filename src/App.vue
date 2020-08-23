<template>
  <v-app>
    <v-container fluid>
      <v-tabs centered center-active show-arrows>
        <v-tab> <v-icon>mdi-calendar-today</v-icon>Day </v-tab>
        <v-tab> <v-icon>mdi-calendar-month</v-icon>Month </v-tab>
        <v-tab> <v-icon>mdi-calendar-range</v-icon>Years </v-tab>
        <v-tab> <v-icon>mdi-bell</v-icon>Event </v-tab>
        <v-tab> <v-icon>mdi-flash</v-icon>Inverter </v-tab>

        <v-tab-item>
          <Day></Day>
        </v-tab-item>
        <v-tab-item>
          <Month></Month>
        </v-tab-item>
        <v-tab-item>
          <Years></Years>
        </v-tab-item>
        <v-tab-item>
          <Event></Event>
        </v-tab-item>
        <v-tab-item>
          <Inverter></Inverter>
        </v-tab-item>
      </v-tabs>
    </v-container>

    <Footer></Footer>
    <Dialog></Dialog>
  </v-app>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import store from "@/store";

export default {
  name: "App",
  components: {
    Day: () => import("@/components/Day"),
    Event: () => import("@/components/Event"),
    Inverter: () => import("@/components/Inverter"),
    Month: () => import("@/components/Month"),
    Years: () => import("@/components/Years"),
    Footer: () => import("@/components/Footer"),
    Dialog: () => import("@/components/Dialog"),
  },
  created: () => {
    setTimeout(() => {
      if (store.state.api.debug) console.log("initialize application...");
      store.dispatch("get_all");
    }, 2500);
  },
};
</script>
