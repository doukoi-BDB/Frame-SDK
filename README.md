# BDB-frame V1.0.0 | 源码官方社群

> **作者：** Bruce.D，PHP 数据库，[:pencil2: 八点半技术站 · 科技栈](#)，[:trophy: CSDN 博客](https://blog.csdn.net/qq_40884473)

> 本仓库是作者Bruce.D 从事一线互联网 ```PHP/Golang/数据库``` 开发的学习历程技术汇总，旨在为大家提供一个清晰详细的学习技术干货。如果本仓库能为您提供帮助，请给予支持(点star)！


<br/>  

### 简介：

1、本仓库管理系统源码完全免费，防止被一些不良商家拿去卖钱，<b> 所以我需要你先帮我点一个 star </b>，助力原创，防止更多人上当受骗，也顺便支持我一下。

2、欢迎加入BDB-frame 官方社群一起研发、讨论、寻找bug。

3、如果你是读者一枚，欢迎分享到朋友圈及个人好友。

<br/>

### 重点：

1、本仓库管理系统分享各种单独API、插件、类 文件（例如：微信支付、扫码支付、授权登录、发送短信.....一系列），本仓库语言暂时为 PHP 为主 web 为辅（其他仓库，后续接入python、go）
目的是什么：小白可以直接套用，也可以为处于职场的开发者提供思路。不知道思路 or 无法下手时候，可以来此寻找 对应的API 等等，拿过去几乎可以套用。（有不懂的问题，欢迎在社群讨论）

2、本仓库管理系统，代码定会简洁，但不是最优，所以不喜勿喷。有大佬者，看到此代码，如果越意帮助更新 or 自己也有功能，插件，方可联系我们，一起加入开发队列。 

<br/>

### 附：特别说明 <br/>
▽<br/>
来   源（公众号）：八点半技术站（ID：gtcarry）<br/>
作   者：Bruce.D<br/>
微  信：xzzs730 （备注：BDB官方社群）<br/>


### 加入我们

| 技术交流wechat群 | xzzs730  期待与你一起更新开源系统、男女不限、水平不限|
| :------------- | :----------- |
| 「加入我们」| wechat：xzzs730 ，备注BDB |
| 「公众号」 | 八点半技术站 |
<br/>


### 目前贡献代码的队友

| 昵称 | 领域  | 国籍 | 一句话的简介   |
| :------------- | :----------- | :----------- |:----------- |
| 小峰哥| <a href="#">「web、数据库」</a> |「China」 | 一名5年经验的web工程师 |
| 大头 | <a href="#">「web、数据库」</a> |「China」 | 暂无 |
| 娟姐 | <a href="#">「web」</a> |「China」 | 姐是个传说 |
<br/>


### 使用说明
1、BDB-frame 系统后端框架本次采用 CI（后续接入：yii/tp/laravel）

2、BDB-frame API请求方式 可依次查看 application/controller/Main.php 文件，请求方式在内部有填写

<br/>

### CI 目录结构
    |-----system                 框架程序目录  
         |-----core              框架的核心程序  
         |-----helpers           辅助函数  
         |-----libraries         通用类库  
         |-----language          语言包  
         |-----database          数据库操作相关的程序              
         |-----fonts             字库  
          
    |-----application            项目目录  
         |-----core              项目的核心程序  
         |-----helpers           项目的辅助函数    
         |-----libraries         通用类库  
         |-----language          语言包  
         |-----config            项目相关的配置  
         |-----controllers       控制器目录  
         |-----models            模型目录  
         |-----views             视图目录  
         |-----cache             存放数据或模板的缓存文件  
         |-----hooks             钩子，在不修改系统核心文件的基础上扩展系统功能  
         |-----third_party       第三方库  
         |-----logs              日志  
         
    |-----public                 公共目录  
         |-----css               样式  
         |-----img               图片    
         |-----js                js  
    
    |-----vendor                 核心目录  
     
    |-----index.php              入口文件 


<br/>



#### 更新公告
---2020.9.1
1. 本次更新时间-2020.9.1
2. 上传Component-API，初始版本V1.0.0 

---2021.2.7
1. Component-API 版本优化V1.0.1
2. 新增 Application/Core/MY_Model.php
3. 新增封装方法（getList、getJoinList、getRow、getOne、getJoinOne、getJoinRow、insertData、delData、delData）
