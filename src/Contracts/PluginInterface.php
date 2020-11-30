<?php
namespace Gallery\Plugin\Contracts;


interface PluginInterface
{
    /**
     * 插件标识，采用下划线命名法（由字母、数字和下划线组成，
     * 且字母开头），不同插件的标识不能相同，建议由类名生成。
     * @return string
     */
    public static function name();

    /**
     * 作者
     * @return string
     */
    public static function author();

    /**
     * 作者主页
     * @return string
     */
    public static function authorUrl();

    /**
     * 插件名称
     * @return string
     */
    public static function title();

    /**
     * 国际化插件名称，由语言代码与名称组成，如：
     * [
     *   'en' => 'Hello World',
     *   'jp' => 'こんにちは世界'
     * ]
     * @return array
     */
    public static function titleI18n();

    /**
     * 插件版本，采用 X.Y.Z 格式，如：1.0.1
     * @return string
     */
    public static function version();

    /**
     * 插件描述，150字以内，可包含html标签
     * @return string
     */
    public static function description();

    /**
     * 国际化插件描述，150字以内，可包含html标签，结构与
     * 国际化标题相同
     * @return string
     */
    public static function descriptionI18n();

    /**
     * 插件详情页
     * @return string
     */
    public static function detailUrl();

    /**
     * 插件配置页
     * @return string
     */
    public static function settingUrl();

    /**
     * 安装时执行
     * @return boolean|string true 或失败原因
     */
    public static function install();

    /**
     * 安装完成后执行
     * @return boolean|string true 或失败原因
     */
    public static function installed();

    /**
     * 卸载时执行
     * @param $name string 指定插件名
     * @param false $removeData 是否移除插件数据
     * @return boolean|string true 或失败原因
     */
    public static function uninstall($name, $removeData = false);

    /**
     * 卸载完成后执行
     * @return boolean|string true 或失败原因
     */
    public static function uninstalled();

    /**
     * 注册到插件系统，等待被执行
     * @param string|null $name 插件标识
     */
    public static function register($name = null);

    /**
     * 执行
     * 插件初始化、挂载钩子等操作
     */
    public function run();

    /**
     * Convert to array.
     * @return array
     */
    public function toArray();

    /**
     * Convert to json.
     * @return false|string
     */
    public function toJson();
}
