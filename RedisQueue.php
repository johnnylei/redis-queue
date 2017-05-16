<?php
namespace johnnylei\redis_queue;
use yii\base\Component;
use Exception;

class RedisQueue extends Component
{
    private $redis;
    public $hostname = 'localhost';
    public $point = 6379;
    public $timeout = 0;
    public function init()
    {
        $this->redis = new \Redis();
        $this->redis->connect($this->hostname, $this->point, $this->timeout);
    }
    public function publish($queue, $data) {
        if(is_string($queue)) {
            return $this->redis->publish($queue, serialize($data));
        }
        if(!is_array($queue)) {
            throw new Exception('invalid queue');
        }
        try {
            foreach ($queue as $item) {
                $this->redis->publish($item, serialize($data));
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return true;
    }
    public function subscribe($queue, $callback) {
        if(!is_array($callback) && !is_string($callback) && !is_callable($callback)) {
            throw new Exception('invalid callback');
        }
        if(is_string($queue)) {
            $queue = [$queue];
        }
        return $this->redis->subscribe($queue, $callback);
    }

    public function __call($name, $params)
    {
        try {
            return call_user_func_array([$this->redis, $name], $params);
        } catch (Exception $e) {
            var_dump($e->getMessage());
            throw new \yii\base\Exception($e->getMessage());
        }
    }
}
