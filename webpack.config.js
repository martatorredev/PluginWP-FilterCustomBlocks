const defaultConfig = require("@wordpress/scripts/config/webpack.config");
 
module.exports = {
  externals: {
    'react': 'React',
    'react-dom': 'ReactDOM',
  },
  ...defaultConfig,
  module: {
    ...defaultConfig.module,
    rules: [
      ...defaultConfig.module.rules,
      {
        test: /\.svg$/,
        use: ["@svgr/webpack", "url-loader"],
      }
    ]
  }
};