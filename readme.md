# 工资条后台

基于 Laravel 5.7，后台 UI 使用 Core UI。

功能十分简单的工资条后台，和小程序端配合，开箱即用。

工资条微信小程序端：[工资条小程序](https://github.com/fengzifz/gongzitiao)

## 功能

- 导入工资：按月份导入，导入模板前面三列固定是“姓名”、“年度” 和 “月份”，其他随意添加，最多可以增加到 30 个字段。
- 工资历史记录
- 按月份删除工资
- 员工管理：员工在小程序端绑定后，自动添加一条员工记录。管理员可以在后台锁定用户，锁定后，员工无法登录查看工资。
- 回执记录：已查看工资记录、未查看工资记录。
- 系统设置：系统维护和后台 IP 登录限制。

## 员工绑定的说明

该项目只是一个十分简单的工资条工具，功能实现一切从简，没有复杂的技术。

- 因为没有接入短信验证，所以员工绑定是用户在小程序端，使用“真实姓名”和“电话号码”进行绑定，绑定后，会记录该用户的 openid。
- 绑定时的 “真实姓名” 会和导入模板的 “姓名” 关联。
- 用户绑定后，无法自己解绑，需要管理员在后台解绑。
- 用户绑定后，同一个姓名和电话号码无法在其他微信端登录。

## 安装

克隆到本地后

```
# step 1
composer install

# step 2
php artisan migrate

# step 3
php artisan db:seed
```

管理员默认登录账号密码

- username: admin
- password: 123456


## 截图

![Alt text](./screenshot/04.png?raw=true)

![Alt text](./screenshot/01.png?raw=true)

![Alt text](./screenshot/02.png?raw=true)

![Alt text](./screenshot/03.png?raw=true)

## License
MIT
