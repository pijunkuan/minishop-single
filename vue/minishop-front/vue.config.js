const path = require('path')
const defaultSetting = require('./src/setting.js')

const isProduction = process.env.NODE_ENV === 'production'

function resolve(dir) {
  return path.join(__dirname, dir)
}

const assetsDir = 'templates/' + defaultSetting.themeName

module.exports = {
  css:{
    loaderOptions:{
      sass:{
        prependData:`
          @import "@/assets/style/mixin.scss";
          @import "@/assets/style/theme.scss";
        `
      }
    }
  },
  publicPath:'/',
  indexPath:'index.blade.php',
  assetsDir,
  filenameHashing:false,
  chainWebpack: config => {
    if(isProduction){
      config.plugins.delete('prefetch')
      config.plugins.delete('preload')
      config.optimization.splitChunks(false)
    }
  },
  configureWebpack: (config) => {
    config.resolve.alias = {'@':resolve('src')}
  },
  productionSourceMap: false
}