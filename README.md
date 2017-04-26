# yii2-redis-queue
基于yii2通过redis的订阅／发布者模式实现的消息队列

# install
- 安装phpredis扩展
- 安装代码
``` php
composer require --prefer-dist johnnylei/yii-redis-queue
```

# usage
- 配置文件
``` php
'redis_queue'=>[
    'class'=>'johnnylei\redis_queue\RedisQueue',
],
```
- 使用
```php
//　前台发送
Yii::$app->redis_queue->publish('test', 'xxxxxxxxxxxxxxx');

// console里面监听，并且处理，设置监听不超时
ini_set('default_socket_timeout', -1);
Yii::$app->redis_queue->subscribe('test', function($instance, $channelName, $message) {
    var_dump($message);
});
```
