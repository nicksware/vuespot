<template>
  <v-btn
    ref="button"
    aria-label="scroll to bottom"
    small
    absolute
    top
    right
    fab
    depressed
    @click="$vuetify.goTo(pageHeight)"
  >
    <v-icon>mdi-chevron-down</v-icon>
  </v-btn>
</template>

<script>
/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import store from "@/store";

export default {
  name: "ScrollToBottom",
  computed: {
    pageHeight() {
      if (store.state.api.debug)
        console.log("scrollHeight", document.body.scrollHeight);
      return document.body.scrollHeight;
    },
  },
};
</script>
