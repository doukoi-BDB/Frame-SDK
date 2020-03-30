# wechaty / wechaty-puppet-padplus 

基于 wechaty-puppet-padplus 的微信机器人助手

----------------------
这里是一个实验场，希望给读者呈现一副关于IT编程技术的「清明上河图」。

----------------------

<a href="https://github.com/gtcarry888/WeChat-Sharing-records">「每日微信分享记录」</a> ：https://github.com/gtcarry888/WeChat-Sharing-records

<a href="https://github.com/gtcarry888/Source-code">「小程序相关源码」</a> ：https://github.com/gtcarry888/Source-code


「技术群」WeChat：xzzs730 （标注加群原因，方便快速审核）

It is forbidden to despise or satirize any beginners, or kick the group directly

---------------------


#### 目前实现功能

- 自动通过好友验证
  - 当有人添加机器人时，判断验证消息关键字后通过或直接通过
  - 通过验证后自动回复并介绍机器人功能
- 私聊关键字回复
  - 例如回复 `加群` 推送群聊邀请
  - 例如回复 `作者微信` 推送作者微信名片
- 自动聊天
  - 群聊中通过 `@[机器人]xxx` 可以和机器人聊天
  - 私聊发送消息即可聊天
- 加入群聊自动欢迎
  - 当新的小伙伴加入群聊后自动 `@[新的小伙伴]` 发一个文字欢迎



#### 结构

```js
|-- src/
|---- index.js				# 入口文件
|---- config.js		  	# 配置文件
|---- onScan.js				# 机器人需要扫描二维码时监听回调
|---- onRoomJoin.js 	# 进入房间监听回调
|---- onMessage.js		# 消息监听回调
|---- onFriendShip.js	# 好友添加监听回调
|-- package.json
```
