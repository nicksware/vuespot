module.exports = {
  devServer: {
    proxy: {
      "^/api/": {
        target: "http://localhost:8000",
        changeOrigin: true,
      },
    },
  },
  pluginOptions: {
    webpackBundleAnalyzer: {
      openAnalyzer: false,
    },
  },
  transpileDependencies: ["vuetify"],
  chainWebpack: (config) => {
    if (process.env.NODE_ENV === "production") {
      config.module.rule("vue").uses.delete("cache-loader");
      config.module.rule("js").uses.delete("cache-loader");
      config.module.rule("ts").uses.delete("cache-loader");
      config.module.rule("tsx").uses.delete("cache-loader");
    }
  },
};
