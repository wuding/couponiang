# couponiang

A comparison shopping website

http://www.couponiang.com/

## 凑婆娘

全网比价购物搜索引擎



# ♋️ Cancer-php 2018.7.23

相比较上一时期，数据库 catfan/medoo 分页 pagerfanta

数据库适配器更加灵活，还写了 pagerfanta 分页适配器

增加助手类，用于多个控制器使用相同部分

控制器支持转接动作，模板还是以前概念，但视图类用来把数据变为字符串



## 安装

类加载

```
composer install
```

配置文件 app/config.php

修改版 src/Medoo.php

分页适配器 src/Pagerfanta/Adapter

数据库 docs/db



## 环境需求

### PHP 扩展

| 扩展名        |      |      |
| ------------- | ---- | ---- |
| php_pdo_mysql |      |      |
|               |      |      |
|               |      |      |

