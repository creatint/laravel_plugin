<?php


namespace Gallery\Plugin;


use Gallery\Plugin\Contracts\HookInterface;

class Hook implements HookInterface
{

    /**
     * 钩子名称，在当前狗子系统中需唯一
     * @var string
     */
    public $name;

    // 钩子类型：动作钩子
    const ACTION_MODE = 0;

    // 钩子类型：过滤器钩子
    const FILTER_MODE = 1;

    // 钩子类型：一次性钩子
    const ONCE_MODE = 2;

    /**
     * 钩子类型
     * @var int  0: 动作钩子, 1: 过滤器钩子, 2: 一次性钩子
     */
    public $mode = 0;

    /**
     * 钩子调用次数
     * @var int
     */
    public $hookExecTimes = 0;

    /**
     * 挂载的函数或方法个数
     * @var int
     */
    public $functionCount = 0;

    /**
     * 钩子的函数或方法执行总次数
     * @var int
     */
    public $functionExecTimes = 0;

    /**
     * 当前过滤器层级
     * @var int
     */
    public $level = 0;

    /**
     * 过滤器钩子最大嵌套层数
     * @var int
     */
    public $maxLevel = 10;

    /**
     * 挂载的函数或方法列表
     * @var array
     */
    public $callbacks = [];

    /**
     * 描述
     * @var string
     */
    public $desc = '';

    /**
     * 配置，模板包含类型 mode 与描述 desc 字段`
     * @var array
     */
    public $config = [];


    /**
     * 钩子的构造方法
     * @param string $name 钩子名称，唯一
     * @param array $config 钩子配置，可修改钩子的 mode 与 desc
     */
    function __construct(string $name, array $config)
    {
        $this->name = $name;
        $this->config = $config;
        if (isset($config['mode'])) {
            $this->mode = $config['mode'];
        }
        if (isset($config['desc'])) {
            $this->desc = $config['desc'];
        }
    }


    /**
     * 挂载函数或方法
     * 按优先级从小到大的顺序调用，同优先级的按先后挂载的次序调用
     * @param object|callable $callback 函数或方法
     * @param int $priority 优先级，默认 10
     * @param int $args 参数个数
     * @return int 当前已挂载的函数和方法总数
     */
    function add($callback, $priority = 10, $args = 1)
    {
        $this->callbacks[$priority][callable_uid($callback)] = [
            'func' => $callback,
            'args' => $args
        ];
        ksort($this->callbacks, SORT_NUMERIC);
        $this->functionCount = count($this->callbacks);
        return count($this->callbacks[$priority]);
    }

    /**
     * 移除已挂载的函数或方法
     * @param callable|object $callback 函数或方法
     * @return bool 是否移除成功
     */
    function remove($callback)
    {
        if (empty($this->callbacks)) {
            return true;
        }
        $uid = callable_uid($callback);
        if (empty($uid)) {
            return true;
        }
        foreach ($this->callbacks as $priority => $group) {
            foreach ($group as $index => $func) {
                if ($index === $uid) {
                    unset($this->callbacks[$priority][$index]);
                }
            }
        }
        $this->functionCount = count($this->callbacks);
        return true;
    }

    /**
     * 移除指定优先级的所有挂载的函数或方法。
     * 如果传入的优先级为 null，则移除该钩子的全部函数或方法。
     * @param int|null $priority 优先级
     * @return bool 是否移除成功
     */
    function removeAll($priority = null)
    {
        if (empty($this->callbacks)) {
            return true;
        }
        if ($priority === null) {
            $this->callbacks = [];
        } else if (!empty($this->callbacks[$priority])) {
            unset($this->callbacks[$priority]);
        }
        $this->functionCount = 0;
        return true;
    }

    /**
     * 检查钩子是否挂载函数或方法
     * @param callable|object $callback 函数或方法
     * @return int|boolean 所在优先级或false
     */
    function have($callback)
    {
        if (empty($this->callbacks)) {
            return false;
        }
        $uid = callable_uid($callback);
        if (empty($uid)) {
            return false;
        }
        foreach ($this->callbacks as $priority => $group) {
            foreach ($group as $index => $func) {
                if ($index === $uid) {
                    return (int)$priority;
                }
            }
        }
        return false;
    }

    /**
     * 调用钩子
     * @param array $args 参数
     * @return mixed 若钩子类型为过滤器钩子，则返回最终的结果
     */
    function play(array $args)
    {
        try {
            if (empty($this->callbacks)) {
                // 未挂载时，单参数的返回单参数，多参数的返回多参数
                if (!empty($args)) {
                    if (count($args) === 1) {
                        return $args[0];
                    } else {
                        return $args;
                    }
                }
                return null;
            }

            $this->level++;
            $this->hookExecTimes ++;

            // 过滤器的参数
            $value = null;

            // 过滤器类型
            if ($this->mode === self::FILTER_MODE) {
                $value = $args[0];
            }

            foreach ($this->callbacks as $group) {
                foreach ($group as $callback) {
                    $this->functionExecTimes ++;
                    if ($this->mode === self::FILTER_MODE) {
                        // 过滤器钩子传递返回值
                        $args[0] = $value;
                    }
                    if ($callback['args'] === 0) {
                        $value = call_user_func($callback['func']);
                    } else {
                        $value = call_user_func_array($callback['func'], $args);
                    }
                    if ($this->mode === self::ACTION_MODE && $value === false) {
                        // 动作模式遇到false停止后续操作
                        break 2;
                    }
                    if ($this->mode === self::ONCE_MODE) {
                        // 一次性模式只执行一次
                        break 2;
                    }
                }
            }
            $this->level--;
            return $value;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
