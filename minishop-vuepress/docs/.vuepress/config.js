module.exports = {
	title:'Minishop电商系统',
	description:'快速开发上线的最小单元化电商系统',
	head:[],
	locales:{},
	themeConfig: {
		nav:[
			{ text:'介绍', link:'/pages/guide/' },
			{ text:'实操', link:'/pages/blogs/' },
			{ text:'文档', link:'/pages/docs/' },
			{ 
				text:'GitHub',
				items:[
					{ text:'GitHub地址', link:'' },
					{ text:'Gitee地址', link:'' }
				]
			}
		],
		sidebar:[
			{
				title:'项目介绍',
				collapsable:false,
				children:[
					'/pages/guide/intro.md'
				]
			},
			{
				title:'项目实操——后端',
				collapsable:false,
				children:[
					
				]
			},
			{
				title:'项目实操——前端',
				collapsable:false,
				children:[
					'/pages/blogs/frontend/create.md'
				]
			}
		]
	}
}