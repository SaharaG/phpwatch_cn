

forked from [phpwatch](https://github.com/arosenfeld/phpwatch)

### 安装

#### 自动安装
  1. Change the permissions on config.php within the root directory to allow for writing.
  2. Navigate to the install directory from a web browser and follow the instructions.
  3. Delete the install directory and change the permissions of config.php to disallow writing.

#### 手动安装
  1. Fill in the database host, user, password, and name in config.php.
  2. Import install/dump.sql into the specified database.
  3. Navigate to the root directory of phpWatch and verify there are no errors.
  4. Delete the install directory.
    
### 更新

2.x.x更新只需要复制config.php和/install目录以外到文件到原安装目录进行覆盖即可。特别需要注意的是重新安装数据库有可能会被覆盖。

### CRONTAB配置
```bash
*/5 * * * * php /path/to/phpwatch/root/directory/cron.php
```

### 中文版用户认证：
    中国版 增加用户登陆机制，不登陆不允许管理监控查看详情；
    默认用户名：phpwatchcn
    默认密  码：phpwatchcn
    
### 邮件客户端模式：
    中国版 增加客户端模式，不用管理员设置服务器的邮件环境 利用smtp 就可以发送邮件给接收者
    详见config.php 里关于client的配置
    
### 已知bug
    未完整的 汉化
    如果联系方式 channel 在用，删除channel后 导致 monitor 报错
