<?php

namespace Gallery\Plugin;

use Gallery\Plugin\Contracts\PluginInterface;
use Gallery\Plugin\Information;
use Gallery\Plugin\Models\Plugin as PluginModel;

class Plugin implements PluginInterface
{

    /**
     * 插件模型
     * @var PluginModel
     */
    public $model;

    /**
     * 插件状态
     * @var int -1:not installed 0:installed, 1:activated 2:develop
     */
    public $status;

    /**
     * 插件标识，采用下划线命名法（由字母、数字和下划线组成，
     * 且字母开头），不同插件的标识不能相同，建议由类名生成。
     * @return string
     */
    public static function name()
    {
        return getPluginName(static::class);
    }

    /**
     * 作者
     * @return string
     */
    public static function author()
    {
        return null;
    }

    /**
     * 作者主页
     * @return string
     */
    public static function authorUrl()
    {
        return null;
    }

    /**
     * 插件名称
     * @return string
     */
    public static function title()
    {
        return static::name();
    }

    /**
     * 国际化插件名称，由语言代码与名称组成，如：
     * [
     *   'en' => 'Hello World',
     *   'jp' => 'こんにちは世界'
     * ]
     * @return array
     */
    public static function titleI18n()
    {
        return null;
    }

    /**
     * 插件版本，采用 X.Y.Z 格式，如：1.0.1
     * @return string
     */
    public static function version()
    {
        return null;
    }

    /**
     * 插件描述，150字以内，可包含html标签
     * @return string
     */
    public static function description()
    {
        return null;
    }

    /**
     * 国际化插件描述，150字以内，可包含html标签，结构与
     * 国际化标题相同
     * @return string
     */
    public static function descriptionI18n()
    {
        return null;
    }


    /**
     * 插件详情页
     * @return string
     */
    public static function detailUrl()
    {
        return null;
    }

    /**
     * 插件配置页
     * @return string
     */
    public static function settingUrl()
    {
        return null;
    }

    /**
     * 安装时执行
     * @return bool|string
     */
    public static function install()
    {
        $plugin = PluginModel::where('name', self::name())->first();
        if ($plugin && $plugin->exists()) {
            return "Plugin [" . self::name() . "] has already been installed.";
        }
        $plugin = new PluginModel();
        $plugin->name = static::name();
        $plugin->version = static::version();
        $plugin->title = static::title();
        $plugin->title_i18n = static::titleI18n();
        $plugin->description = static::description();
        $plugin->description_i18n = static::descriptionI18n();
        $plugin->save();
        return $plugin->id > 0;
    }

    /**
     * 安装完成后执行
     * @return bool|string|void
     */
    public static function installed() {

    }

    /**
     * 卸载时执行
     * @param $name string 指定插件名
     * @param false $removeData 是否移除插件数据
     * @return boolean|string true 或失败原因
     */
    public static function uninstall($name, $removeData = false)
    {
        $plugin = PluginModel::where('name', $name)->first();
        if (empty($plugin) || !$plugin->exists()) {
            return "插件[{$name}]不存在";
        }
        PluginModel::where('name', $name)->delete();
        return true;
    }

    /**
     * 卸载完成后执行
     * @return boolean|string true 或失败原因
     */
    public static function uninstalled()
    {
        return null;
    }

    /**
     * 注册插件到插件系统
     * @param string|null $name 插件标识
     */
    public static function register($name = null)
    {
        Manager::$raws[$name ?? static::name()] = new Information([
            'name' => static::name(),
            'class' => static::class,
            'version' => static::version(),
            'author' => static::author(),
            'authorUrl' => static::authorUrl(),
            'title' => static::title(),
            'titleI18n' => static::titleI18n(),
            'description' => static::description(),
            'descriptionI18n' => static::descriptionI18n(),
            'closure' => function () {
                return new static();
            },
            'status' => 0
        ]);
        return Manager::$raws[$name ?? static::name()];
    }

    /**
     * 执行
     * 插件初始化、挂载钩子等操作
     */
    public function run() {

    }

    public function toArray()
    {
        return [
            'name' => $this->model->name,
            'version' => $this->model->version,
            'author' => $this->model->author,
            'authorUrl' => $this->model->author_url,
            'title' => $this->model->title,
            'titleI18n' => $this->model->title_i18n,
            'description' => $this->model->description,
            'descriptionI18n' => $this->model->description_i18n,
            'status' => $this->model->status,
        ];
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
