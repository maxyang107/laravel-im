<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## 关于项目

基于Laravel框架，结合Gateway-Worker实现的IM系统（陆续更新中）

## 现已完成功能
【长连接接口】<br><br>
1，用户认证（基于ws交互）
<br>接口访问地址：ws://127.0.0.1:2346
<br>
请求参数格式：
~~~
{
	"action": "login",
	"token": "11",
	"user_id": "11",
}
~~~
用户认证通过会检测用户是否有离线消息，有离线消息的话，会推送离线消息给用户

2，一对一聊天
<br>接口访问地址：ws://127.0.0.1:2346
<br>
请求参数格式：
~~~
{
	"action": "single_chart",
	"token": "22",
	"user_id": "12",
        "to_user_id":"11",
        "message":"你好,IM"
}
支持消息离线发送，上线通知
~~~



【短连接接口】<br><br>
1，好友添加请求<br>
/api/addFriends
<br>请求参数：user_id, friend_user_id
<br>resful 格式返回结果
