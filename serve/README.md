## 安装说明

>推荐使用宝塔面板进行配置

* 通过Git克隆本项目

```$xslt
git clone https://github.com/pijunkuan/minishop-serve.git
```

* 进入文件夹然后安装依赖
```$xslt
cd minishop-serve/serve
composer install
```

* 复制.env.example文件到.env并进行配置

* 生成app key和jwt-secret
```$xslt
php artisan key:generate
php artisan jwt:secret
```


## License

MINISHOP 框架遵循 [MIT license](https://opensource.org/licenses/MIT).
