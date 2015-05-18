#五指CMS网站管理系统
五指cms网站管理系统，网站内容管理系统，php5+mysql开发。

![](http://www.wuzhicms.com/uploadfile/2015/05/18/1431917862749065.png)
#### 服务器要求
Web服务器：apache/nginx/iis

PHP环境要求：支持php5.2、php5.3、php5.4、php5.5、php5.6！（推荐使用5.4或更高版本！）

数据库要求： Mysql 5


#### 产品简介
> 《五指互联网站内容管理系统》简称“五指CMS”，是基于PHP（5.2及以上版本） + Mysql 开发的网站管理系统，系统架构通过MVC实现，可非常灵活的进行二次开发扩展。

> 本系统是由具有8年专职网站管理系统开发经验的王参加担任系统总架构师，聚集数10名开发者共同精心开发，打造！系统从安全，高效，用户体验入手，真正做到了安全第一，性能卓越！


#### 获取帮助
官网：http://www.wuzhicms.com/

开发文档：http://www.wuzhicms.com/doc/

#### 功能特点描述
##### 模型化设计： 

* 全站统一模型，二次开发更方便
* 独创共享模型，独立模型。大小数据全部按需存储
* 模块继承统一模型，只需要改改参数即可实现模块支持模型功能
* 支持10多种不同类型的字段添加：如，文本字段，超级字段，地图字段，滑动条字段，组图字段，下载字段，URL加密字段

##### 数据读写分离：

* 默认支持数据读写分离
* 支持1台主数据，多台从数据库
* 支持按照权重分配数据资源

##### 安全性设计：
- 支持缓存文件目录独立设置
- 支持www目录与核心代码分离部署
- 支持cookie加密存储
- 支持后台程序文件与前台文件分离
- 支持全局Mysql注入过滤
- 支持上传目录自定义，禁用php执行
- 后台登录采用session登录验证，记录所有登录历史
- 后台管理日志记录
- 是否允许修改模版需要有服务器文件管理权限
- 所有菜单都需要进行权限验证

##### 性能设计：

- 缓存支持内存缓存，如：memcache 缓存
- 不重复生成和检查模版缓存，提升性能
- 支持cookie加密存储
- 支持后台程序文件与前台文件分离
- 支持全局Mysql注入过滤
- 支持上传目录自定义，禁用php执行

##### 移动站设计：
- 默认支持移动端访问自适应
- 无需重复发文章，一条记录即可解决
- 支持android／ios手机浏览器模式访问

###程序模块结构说明
```
|-- coreframe                   #框架目录
|   |-- app                     #模块（应用程序）目录
|   |   |-- affiche             #公告模块
|   |   |-- appshop             #应用商城
|   |   |-- attachment          #附件模块
|   |   |-- collect             #采集器
|   |   |-- content             #内容模块
|   |   |-- core                #核心模块
|   |   |-- coupon              #优惠券模块
|   |   |-- credit              #积分模块
|   |   |-- database            #数据库模块
|   |   |-- dianping            #点评模块
|   |   |-- guestbook           #留言板模块
|   |   |-- link                #友情链接模块
|   |   |-- linkage             #联动菜单
|   |   |-- member              #会员模块
|   |   |-- message             #站内短信模块
|   |   |-- mobile              #移动手机模块
|   |   |-- order               #订单模块
|   |   |-- pay                 #支付模块
|   |   |-- ppc                 #推广模块
|   |   |-- receipt             #发票申请模块
|   |   |-- search              #全站搜索模块
|   |   |-- sms                 #短信模块
|   |   |-- tags                #tags模块
|   |   --- template            #在线模板编辑
|   |-- configs                 #框架配置
|   |-- core.php                #框架入口
|   |-- crontab                 #定时脚本目录
|   |-- crontab.php             #定时脚本入口
|   |-- extend                  #扩展目录
|   |-- languages               #语言包
|   --- templates               #模板
|-- caches                      #缓存目录
|   |-- _cache_
|   |-- block
|   |-- caches
|   |-- content
|   |-- database
|   |-- db_bak
|   |-- install.check
|   |-- linkage
|   |-- logs
|   |-- member
|   |-- model
|   |-- order
|   |-- ppc
|   --- templates               #模板缓存
--- www                         #网站根目录
    |-- 404.html                #404页面
    |-- admin.php               #后台入口
    |-- api                     #api目录
    |-- configs                 #网站配置
    |-- favicon.ico             #浏览器icon
    |-- index.html              #网站首页
    |-- index.php               #动态地址首页
    |-- res                     #静态资源
    |-- robots.txt              #搜索引擎防抓取规则
    |-- uploadfile              #附件
    `-- web.php                 #自定义路由
```

### V2.0 版本更新说明 (2015-05-18)

*  所有源码完全开源！
*  支持php5.2、php5.3、php5.4、php5.5、php5.6！（推荐使用5.4或更高版本！）
*   新增图片模型及前台模板展示
*  新增下载模型及前台模板展示
*  全新会员中心
*  新增短信手机验证
*  新增会员公司模型，机构模型注册
*  新增积分管理、积分配置、积分消费记录
*  新增订单管理
*  新增优惠券管理
*  新增百度地图字段
*  新增下载字段
*  新增管理模型内容字段
*  新增全新门户版PC模板1套
*  新增全新门户版手机模板1套
*  新增云端区块ID添加，后台菜单云端ID，开发者可轻松打包发布！
*  新增微信公众号自动回复功能
*  新增微信公众号菜单设置
*  新增已关注微信公众号通过公众号进入Html5页面自动登录
*  修复php5.2上传附件问题
*  修复php5.3页面部分页面白屏问题
*  修复多处字段输出格式错误问题
*  其它修复项多达50项