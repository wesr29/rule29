const path = require('path')
const VueLoaderPlugin = require('vue-loader/lib/plugin')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')
const glob = require('glob')

//create an array of entry points main then all the block JS files
let allJavascriptFiles = glob.sync('../blocks/**/*.js')
allJavascriptFiles.push(path.resolve(__dirname, 'js/main.js'))

module.exports = {
  mode: process.env.NODE_ENV,
  watch: process.env.NODE_ENV == 'development',
  entry: {
    main: allJavascriptFiles,
    admin: path.resolve(__dirname, 'js/admin.js')
  },
  output: {
    path: path.resolve(__dirname, '../public/js'),
    filename: '[name].js'
  },
  module: {
    rules: [
      {
        test: /\.pug$/,
        oneOf: [
          {
            resourceQuery: /^\?vue/,
            use: ['pug-plain-loader']
          },
          {
            use: ['raw-loader', 'pug-plain-loader']
          }
        ]
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader',
      },
      {
        test: /\.js$/,
        exclude: /node_modules\/[^vue]/, //exclude all node modules except for vue (because they use arrow functions and those need compilation)
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      }
    ]
  },
  plugins: [
    new CleanWebpackPlugin(),
    new VueLoaderPlugin()
  ],
  resolve: { 
    alias: {
      'vue$': 'vue/dist/vue.esm.js' // 'vue/dist/vue.common.js' for webpack 1
    }
  }
}