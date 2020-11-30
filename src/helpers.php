<?php

if (!function_exists('getPluginName')) {
    /**
     * 获取插件默认标识
     * @param $name string Namespace
     * @return string
     */
    function getPluginName(string $name)
    {
        $arr = explode('\\', $name);
        array_pop($arr);
        return strtolower(join('_', $arr));
    }
}


if (!function_exists('call_class_func')) {
    /**
     * 调用类的方法
     * @param string|object $class
     * @param string $method
     * @param array $args 参数列表
     * @return mixed
     * @throws \Gallery\Plugin\Exceptions\PluginException
     * @throws \Gallery\Plugin\Exceptions\PluginInvalidException
     */
    function call_class_func($class, $method, $args = [])
    {
        if (is_string($class)) {
            $className = $class;

            if (!class_exists($class)) {
                throw new \Gallery\Plugin\Exceptions\PluginInvalidException("Plugin [$className] not found.");
            }
        } else {
            $className = get_class($class);
        }

        if (!method_exists($class, $method)) {
            throw new \Gallery\Plugin\Exceptions\PluginInvalidException("Plugin [$className] has not method: [$method].");
        }

        try {
            return call_user_func_array([$class, $method], $args);
        } catch (\Exception $e) {
            throw new Gallery\Plugin\Exceptions\PluginException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}


if (!function_exists('add')) {
    /**
     * 挂载函数或方法
     * @param string $name 钩子标识
     * @param callable|object $callback 函数或方法
     * @param int $args 参数
     * @param int $priority 优先级，默认 10
     * @param int $mode 模式 0：动作钩子 1：过滤器钩子 2：一次性钩子
     * @return int|boolean 当前已挂载的函数和方法总数或false
     */
    function add($name, $callback, $args = 0, $priority = 10, $mode = \Gallery\Plugin\Hook::ACTION_MODE)
    {
        if (empty($name)) {
            return false;
        }
        if (empty(\Gallery\Plugin\Manager::$hooks[$name])) {
            $config = [
                'mode' => $mode
            ];
            \Gallery\Plugin\Manager::$hooks[$name] = new \Gallery\Plugin\Hook($name, $config);
        }
        return \Gallery\Plugin\Manager::$hooks[$name]->add($callback, $priority, $args);
    }
}

if (!function_exists('add_action')) {
    /**
     * 挂载函数或方法，动作模式
     * 若挂载的方法返回false，则停止后续动作
     * @param string $name 钩子标识
     * @param callable|object $callback 函数或方法
     * @param int $args 参数
     * @param int $priority 优先级，默认 10
     * @return int|boolean 当前已挂载的函数和方法总数或false
     */
    function add_action($name, $callback, $args = 0, $priority = 10)
    {
        return add($name, $callback, $args, $priority, \Gallery\Plugin\Hook::ACTION_MODE);
    }
}

if (!function_exists('add_filter')) {
    /**
     * 挂载函数或方法，过滤器模式
     * @param string $name 钩子标识
     * @param callable|object $callback 函数或方法
     * @param int $args 参数
     * @param int $priority 优先级，默认 10
     * @return int|boolean 当前已挂载的函数和方法总数或false
     */
    function add_filter($name, $callback, $args = 0, $priority = 10)
    {
        return add($name, $callback, $args, $priority, \Gallery\Plugin\Hook::FILTER_MODE);
    }
}

if (!function_exists('add_once')) {
    /**
     * 挂载函数或方法，一次性模式
     * @param string $name 钩子标识
     * @param callable|object $callback 函数或方法
     * @param int $args 参数
     * @param int $priority 优先级，默认 10
     * @return int|boolean 当前已挂载的函数和方法总数或false
     */
    function add_once($name, $callback, $args = 0, $priority = 10)
    {
        return add($name, $callback, $args, $priority, \Gallery\Plugin\Hook::ONCE_MODE);
    }
}

if (!function_exists('remove')) {
    /**
     * 移除挂载的函数或方法
     * @param string $name 钩子标识
     * @param callable|object $callback 函数或方法
     * @return bool 是否移除成功
     */
    function remove($name, $callback)
    {
        if (empty($name) || empty(\Gallery\Plugin\Manager::$hooks[$name])) {
            return true;
        }
        return \Gallery\Plugin\Manager::$hooks[$name]->remove($callback);
    }
}

if (!function_exists('remove_all')) {
    /**
     * 移除指定优先级的所有挂载的函数或方法。
     * 如果传入的优先级为 null，则移除该钩子的全部函数或方法。
     * @param string $name 钩子标识
     * @param int|null $priority 优先级
     * @return bool 是否移除成功
     */
    function remove_all($name, $priority = null)
    {
        if (empty($name) || empty(\Gallery\Plugin\Manager::$hooks[$name])) {
            return true;
        }
        return \Gallery\Plugin\Manager::$hooks[$name]->removeAll($priority);
    }
}

if (!function_exists('have')) {
    /**
     * 检查钩子是否挂载函数或方法
     * @param string $name 钩子标识
     * @param callable|object $callback 函数或方法
     * @return int|null 所在优先级或false
     */
    function have($name, $callback)
    {
        if (empty($name) || empty($callback) || empty(\Gallery\Plugin\Manager::$hooks[$name])) {
            return false;
        }
        return \Gallery\Plugin\Manager::$hooks[$name]->have($callback);
    }
}

if (!function_exists('play')) {
    /**
     * 调用钩子
     * @param string $name 钩子标识
     * @param mixed ...$args 参数
     * @return mixed 若钩子类型为过滤器钩子，则返回最终的结果
     */
    function play($name, ...$args)
    {
        if (empty($name)) {
            return null;
        }
        if (empty(\Gallery\Plugin\Manager::$hooks[$name])) {
            if (!empty($args)) {
                if (count($args) === 1) {
                    return $args[0];
                } else {
                    return $args;
                }
            }
            return null;
        }
        return \Gallery\Plugin\Manager::$hooks[$name]->play($args);
    }
}

if (!function_exists('callable_uid')) {
    /**
     * 生成函数或方法的UID
     * @param callable|object $callable
     * @return string|null
     */
    function callable_uid($callable)
    {
        if (empty($callable)) {
            return null;
        }

        if (is_string($callable)) {
            return $callable;
        }

        if (is_object($callable)) {
            return spl_object_hash($callable);
        }

        $callable = (array)$callable;

        if (is_object($callable[0])) {
            $uid = spl_object_hash($callable[0]);
            if (count($callable) >= 2) {
                $uid .= '.' . (string)$callable[1];
            }
            return $uid;
        }

        $uid = (string)$callable[0];
        if (count($callable) >= 2) {
            $uid .= '::' . (string)$callable[1];
        }
        return $uid;
    }
}

if (!function_exists('search_files')) {
    /**
     * 递归搜索文件
     * @param string $base 文件夹
     * @param string $target 目标文件名，包含后缀
     * @param int $depth 搜索最大目录层级，0为无限制，默认-1
     * @param bool $root 当前是否是顶级目录
     * @return array
     */
    function search_files(string $base, string $target, int $depth = -1, $root = true) {
        $files = [];
        $base = realpath($base);
        static $count = 0;

        if ($root) {
            $count = 0;
        }



        if (is_dir($base)) {
            if ($depth > -1 && $count > $depth){
                return [];
            }
            if ($handler = opendir($base)) {
                while(($file = readdir($handler)) !== false) {
                    if ($file === '.' || $file === '..') {
                        continue;
                    }
                    $path = $base . DIRECTORY_SEPARATOR . $file;
                    $count ++;
                    $res = search_files($path, $target, $depth, false);
                    $count --;
                    if ($res) {
                        array_push($files, ...$res);
                    }
                }
            } else {
                return [];
            }
        } else if (file_exists($base)) {
            if ($target === basename($base)) {
                return [$base];
            } else {
                return [];
            }
        }
        return $files;
    }
}

if (!function_exists('get_file_class')) {
    /**
     * 按psr-4规则获取类文件中的类名
     * @param string $file 类文件完整路径
     * @param string $base 基准路径
     * @param string $ext 类文件后缀，包括点号
     * @return string|string[]
     */
    function get_file_class($file, $base, $ext) {
        $base = rtrim($base, '/');
        $base = rtrim($base, '\\');
        $class = str_replace($base, '', $file);
        $class = str_replace($ext, '', $class);
        return $class;
    }
}
