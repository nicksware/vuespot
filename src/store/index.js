/*! This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA. */

import Vue from "vue";
import Vuex from "vuex";
import VuexPersistence from "vuex-persist";
import axios from "axios";

Vue.use(Vuex);

function getRandomArbitrary(min, max) {
  return Math.random() * (max - min) + min;
}

export default new Vuex.Store({
  state: {
    app: {
      date: new Date().toISOString().substr(0, 10),
      dialog: false,
      url: "",
      secure: false,
    },
    loading: {
      day: false,
      event: false,
      inverter: false,
      month: false,
      year: false,
    },
    api: {
      debug: false,
      day: [],
      event: [],
      inverter: [],
      month: [],
      year: [],
    },
  },
  plugins: [new VuexPersistence().plugin],
  getters: {
    isLoading: (state) => {
      for (const k of Object.keys(state.api)) {
        if (Array.isArray(state.api[k]) && state.api[k].length == 0)
          return true;
      }
      return Object.values(state.loading).some((e) => e === true);
    },
    isLoadingByKey: (state) => (key) => {
      if (state.api.debug)
        console.log("loading " + key, [
          state.loading[key],
          undefined != state.api[key] &&
            Array.isArray(state.api[key]) &&
            state.api[key].length == 0,
          state.api[key],
        ]);
      return (
        undefined != state.loading[key] &&
        (state.loading[key] ||
          (undefined != state.api[key] &&
            Array.isArray(state.api[key]) &&
            state.api[key].length == 0))
      );
    },
    isEmptyByKey: (state) => (key) => {
      if (state.api.debug)
        console.log(
          "empty " + key,
          undefined != state.api[key] &&
            Array.isArray(state.api[key]) &&
            state.api[key].length == 0
        );
      return (
        undefined != state.api[key] &&
        Array.isArray(state.api[key]) &&
        state.api[key].length == 0
      );
    },
  },
  mutations: {
    show_dialog(state) {
      if (!state.app.dialog) {
        Object.keys(state.loading).map(function (key) {
          state.loading[key] = false;
        });
        state.app.dialog = true;
        if (state.api.debug) console.log("show dialog");
      }
    },
    dialog(state, enable) {
      state.app.dialog = enable;
      if (state.api.debug) console.log("show dialog", enable);
    },
    apiurl(state, url) {
      state.app.url = url;
    },
    secureapi(state, enable) {
      state.app.secure = enable;
    },
    date(state, date) {
      state.app.date = date;
    },
    loading(state) {
      Object.keys(state.loading).map(function (key) {
        if (!state.loading[key]) state.loading[key] = true;
      });
    },
    loadingByKey(state, key) {
      state.loading[key] = true;
    },
    get(state, { key, value }) {
      if (state.api[key] !== undefined) {
        state.api[key] = value;
        if (state.loading[key])
          setTimeout(
            () => (state.loading[key] = false),
            getRandomArbitrary(500, 1000)
          );
        if (state.api.debug) console.log(key + " loaded", value);
      }
    },
    add(state, { key, value }) {
      if (
        state.api[key] !== undefined &&
        Array.isArray(value) &&
        value.length > 0
      ) {
        state.api[key] = [].concat(state.api[key], value);
        if (state.loading[key])
          setTimeout(
            () => (state.loading[key] = false),
            getRandomArbitrary(500, 1000)
          );
        if (state.api.debug) console.log(key + " expanded", value);
      }
    },
  },
  actions: {
    get_all({ dispatch, commit, state }) {
      commit("loading");
      commit("date", new Date().toISOString().substr(0, 10));
      const keys = Object.keys(state.api);
      keys.forEach((key) => dispatch("get", { key }));
    },
    get({ commit, state }, { key, arg }) {
      setTimeout(() => {
        if (!state.loading[key]) commit("loadingByKey", key);

        const url =
          (state.app.url
            ? (state.app.secure ? "https://" : "http://") + state.app.url
            : "") +
          "/api/?r=" +
          (arg ? key + "/" + arg : key);
        if (state.api.debug) console.log("load " + url);

        axios
          .get(url, {
            crossDomain: state.app.url ? true : false,
          })
          .then((res) => {
            if (res.status == 200 && res.data.error !== undefined)
              console.error(res.data.error.message, res.data.error);
            if (res.status == 200 && res.data[key] !== undefined)
              commit("get", {
                key: key,
                value: res.data[key],
              });
            else if (res.status == 200 && res.data[key + "s"] !== undefined)
              commit("get", {
                key: key,
                value: res.data[key + "s"],
              });
            else
              commit("get", {
                key: key,
                value: res.data,
              });
          })
          .catch(() => commit("show_dialog"));
      }, getRandomArbitrary(500, 1000));
    },
  },
});
