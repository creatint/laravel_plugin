<?php

namespace Gallery\Plugin;


use Gallery\Plugin\Contracts\HookInterface;
use Gallery\Plugin\Contracts\PluginInterface;

class Manager
{
    /**
     * 是否由数据库管理插件状态
     * @var bool
     */
    public static $hasDatabases = true;

    /**
     * 未下载的插件
     * @var \Gallery\Plugin\Models\Plugin[]
     */
    public static $unDownloaded = [];

    /**
     * 自动注册的信息实例列表
     * @var Information[]
     */
    public static $raws = [];

    /**
     * 插件实例列表，包括未启用的与启用的
     * @var Plugin[]
     */
    public static $resolved = [];

    /**
     * 已启用的插件实例列表
     * @var PluginInterface[]
     */
    public static $activated = [];

    /**
     * 钩子列表
     * @var HookInterface[]
     */
    public static $hooks = [];

    /**
     * 类库文件缓存映射
     * @var array
     */
    private static $classMap = [];

    /**
     * 自动加载类库文件
     * @param $class
     */
    public static function autoload($class)
    {
        if (isset(self::$classMap[$class])) {
            include_once self::$classMap[$class];
        } else {
            $path = base_path(config('plugin.root') . str_replace('\\', '/', $class) . config('plugin.ext'));
            $path = realpath($path);
            if (is_file($path)) {
                include_once $path;
            }
        }
    }

    /**
     * 下载插件
     * 返回true或失败原因
     * @param string $name 插件标识
     */
    public static function downloadPlugin($name)
    {
    }

    /**
     * 安装插件
     * 返回true或失败原因
     * @param string $name 插件标识
     * @return bool|string
     */
    public static function addPlugin($name) {
        try {
            if (empty($name)) {
                return '插件标识为空';
            }

            if (!empty(Manager::$activated[$name])) {
                return "插件[${name}]已经安装";
            }

            if (empty(Manager::$resolved) || empty(Manager::$resolved[$name])) {
                return '找不到要安装的插件';
            }

            $plugin = Manager::$resolved[$name];

            $res = call_class_func($plugin, 'install');

            if ($res !== true) {
                return $res;
            }

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 卸载插件
     * 返回true或失败原因
     * @param string $name 插件标识
     * @param false $removeData 移除数据
     * @param false $removeCode 移除源码
     * @return bool|string
     */
    public static function removePlugin($name, $removeData = false, $removeCode = false) {
        try {
            if (empty($name)) {
                return '插件标识为空';
            }

            if (empty(Manager::$resolved[$name])) {
                return '找不到要卸载的插件';
            }

            $plugin = Manager::$resolved[$name];

            $res = call_class_func($plugin, 'uninstall', [$name, $removeData, $removeCode]);

            if ($res !== true) {
                return $res;
            }

            // TODO:删除源码

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 获取插件列表
     */
    public static function getPlugins() {
        return self::$resolved;
    }

    /**
     * 获取插件详情
     */
    public static function getPlugin($name) {
        if (!empty(self::$resolved[$name])) {
            return self::$resolved[$name];
        }
        return null;
    }

    /**
     * 创建插件
     * 返回true或失败原因
     * @param string $name 插件标识
     */
    public static function createPlugin($name) {

    }

    /**
     * 启用插件
     * 返回true或失败原因
     * @param string $name
     * @return bool|string
     */
    public static function enablePlugin($name) {
        try {
            $plugin = config('plugin.model')::where('name', $name)->first();
            if ($plugin && $plugin->exists()) {
                $plugin->status = 1;
                $plugin->save();
                return true;
            } else {
                return '插件不存在';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 禁用插件
     * 返回true或失败原因
     * @param string $name
     * @return bool|string
     */
    public static function disablePlugin($name) {
        try {
            $plugin = config('plugin.model')::where('name', $name)->first();
            if ($plugin && $plugin->exists()) {
                $plugin->status = 0;
                $plugin->save();
                return true;
            } else {
                return '插件不存在';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 挂载钩子
     * 返回true或失败原因
     */
    public static function addHook() {

    }
}