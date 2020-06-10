const path = require('path')


const isProduction = process.env.NODE_ENV === 'production'

const CompressionPlugin = require('compression-webpack-plugin')
const { openGzip } = require('./package.json')

function resolve(dir) {
  return path.join(__dirname, dir)
}

module.exports = {
  assetsDir: ('templates/center'),
  filenameHashing:false,
  chainWebpack: config => {
    if(isProduction){
      config.plugins.delete('prefetch')
      config.plugins.delete('preload')
      if(openGzip){
        config.plugins = [
          ...config.plugins,
          new CompressionPlugin({
            test:/\.js$|\.html$|.\css/,
            threshold:10240,
            deleteOriginalAssets:false
          })
        ]
      }
      config.optimization.splitChunks({
        chunks: 'all',
        cacheGroups: {
          libs: {
            name: 'template-libs',
            test: /[\\/]node_modules[\\/]/,
            priority: 10,
            chunks: 'initial'
          },
          elementUI: {
            name: 'template-elementUI', 
            priority: 20, 
            test: /[\\/]node_modules[\\/]_?element-ui(.*)/ 
          },
          commons: {
            name: 'template-commons',
            test: resolve('src/components'), 
            minChunks: 3,
            priority: 5,
            reuseExistingChunk: true
          }
        }
      })
    }
  },
	configureWebpack: {
    resolve: {
     		alias: {
       		'@': resolve('src')
     		}
    }
  },
  productionSourceMap: false,
  publicPath:'/'
}