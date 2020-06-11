module.exports = {
	title:'Minishop电商系统',
	description:'快速开发上线的最小单元化电商系统',
	head:[],
	locales:{},
	themeConfig: {
		nav:[
			{ text:'介绍', link:'/pages/guide/' },
			{ text:'教程', link:'/blogs/' },
			{ text:'文档', link:'/docs/' },
			{ 
				text:'GitHub',
				items:[
					{ text:'GitHub地址', link:'' },
					{ text:'Gitee地址', link:'' }
				]
			}
		],
		sidebar:[
			'/pages/guide/',
			{
				title:'教程',
				collapsable:false,
				children:[
					'/pages/blogs/'
				]
			}
		]
	}
}