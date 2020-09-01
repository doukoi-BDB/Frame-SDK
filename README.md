# BDB-frame V1.0.0 | 源码官方社群

> **作者：** Bruce.D，PHP 数据库，[:pencil2: 八点半技术站 · 科技栈](#)，[:trophy: CSDN 博客](https://blog.csdn.net/qq_40884473)

> 本仓库是作者Bruce.D 从事一线互联网 ```PHP/Golang/数据库``` 开发的学习历程技术汇总，旨在为大家提供一个清晰详细的学习技术干货。如果本仓库能为您提供帮助，请给予支持(点star)！

<br/>
<div align="center">
    <a href="#" style="text-decoration:none"><img src="https://bugstack.cn/assets/images/icon.svg" width="128px"></a>
</div>
<br/>  

### 简介：

1、本仓库管理系统源码完全免费，防止被一些不良商家拿去卖钱，<b> 所以我需要你先帮我点一个 star </b>，助力原创，防止更多人上当受骗，也顺便支持我一下。

2、欢迎加入BDB-frame 官方社群一起研发、讨论、寻找bug。

3、如果你是读者一枚，欢迎分享到朋友圈及个人好友。

<br/>

### 附：特别说明 <br/>
▽<br/>
来   源（公众号）：八点半技术站（ID：gtcarry）<br/>
作   者：Bruce.D<br/>
微  信：xzzs730 （备注：BDB官方社群）<br/>


### 加入我们

| 技术交流wechat群 | xzzs730  期待与你一起更新开源系统、男女不限、水平不限|
| :------------- | :----------- |
| 「CSDN博客」| https://blog.csdn.net/qq_40884473 |
| 「加入我们」| wechat：xzzs730 ，备注BDB |
| 「公众号」 | 八点半技术站 |
<br/>

### 使用说明
1、BDB-frame 系统后端框架采用 CI框架，语言采用 PHP + MYSQL + layui

2、BDB-frame 系统运行环境支持 PHP5.4 + nginx/apache

3、演示地址：待定（图片 or 网站）

#### 结构

```js
|-- application /     # 开发者代码
  |-----core            # 项目的核心程序 
  |-----helpers         # 项目的辅助函数 
  |-----libraries       # 通用类库  
  |-----language        # 语言包 
  |-----config          # 项目相关的配置 
  |-----controllers     # 控制器目录  
  |-----models          # 模型目录  
  |-----views           # 视图目录
  |-----cache           # 存放数据或模板的缓存文件  
  |-----hooks           # 钩子，在不修改系统核心文件的基础上扩展系统功能  
  |-----third_party     # 第三方库  
  |-----logs            # 日志  
  |-----modules         # 模块文件  
|-- system /          # 系统代码
|-- public /          # web 代码
|-- vendor /          # 核心代码
|-- index.php         # 入口文件

```
<br/>

#### 更新公告
1. 本次更新时间-2020.9.1
2. 上传BDB-frame，初始版本V1.0.0 
