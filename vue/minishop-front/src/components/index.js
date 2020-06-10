/* 
	批量注册组件
*/
function changeStr(str){
	return str.charAt(0).toUpperCase() + str.slice(1)
}

const requireComponents = require.context('./Elements', false, /\.vue$/)

const install = (Vue) => {
	requireComponents.keys().forEach((file) => {
		let config = requireComponents(file)
		let componentsName = changeStr(
			file.replace(/^\.\//, "").replace(/\.\w+$/, "")
		)
		Vue.component(componentsName, config.default || config)
	})
}

export default{
	install
}