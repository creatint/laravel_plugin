<?php


namespace Gallery\Plugin\Contracts;


interface HookInterface
{
    /**
     * 挂载函数或方法
     * 按优先级从小到大的顺序调用，同优先级的按先后挂载的次序调用
     * @param object|callable $callback 函数或方法
     * @param int $priority 优先级，默认 10
     * @param int $args 参数个数
     * @return int 当前已挂载的函数和方法总数
     */
    function add($callback, $priority = 10, $args = 1);

    /**
     * 移除已挂载的函数或方法
     * @param callable|object $callback 函数或方法
     * @return bool 是否移除成功
     */
    function remove($callback);

    /**
     * 移除指定优先级的所有挂载的函数或方法。
     * 如果传入的优先级为 null，则移除该钩子的全部函数或方法。
     * @param int|null $priority 优先级
     * @return bool 是否移除成功
     */
    function removeAll($priority = null);

    /**
     * 检查钩子是否挂载函数或方法
     * @param callable|object $callback 函数或方法
     * @return int|boolean 所在优先级或false
     */
    function have($callback);

    /**
     * 调用钩子
     * @param array $args 参数
     * @return mixed 若钩子类型为过滤器钩子，则返回最终的结果
     */
    function play(array $args);
}