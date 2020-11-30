<?php


namespace Gallery\Plugin\Http\Controllers;


use Gallery\Plugin\Manager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;

class PluginController extends BaseController
{
    /**
     * 插件列表
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        $plugins = Manager::getPlugins();
        return view('plugin::index', ['activated' => Arr::where($plugins, function($plugin){
            return $plugin->status >= 0;
        }), 'unInstalled' => Arr::where($plugins, function($plugin){
            return $plugin->status < 0;
        }), 'unDownloaded' => Manager::$unDownloaded]);
    }

    /**
     * 插件详情
     * @param Request $request
     * @param string $name 插件标识
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $name) {
        $unDownloaded = false;
        $plugin = Manager::getPlugin($name);
        if (empty($plugin)) {
            $plugin = config('plugin.model')::where('name', $name)->first();
            $unDownloaded = true;
        }
        return view('plugin::show', ['plugin' => $plugin, 'unDownloaded' => $unDownloaded]);
    }

    /**
     * 创建新插件的表单页面
     * @param Request $request
     */
    public function create(Request $request) {
        return view('plugin::create');
    }

    /**
     * 保存新插件
     * @param Request $request
     */
    public function store(Request $request) {
        $res = true;
        if ($res === true) {
            return view('plugin::success', ['to' => $request->headers->get('referer')]);
        } else if ($res instanceof \Exception){
            return view('plugin::error', ['to' => $request->headers->get('referer'), 'message' => $res->getMessage()]);
        } else {
            return view('plugin::error', ['to' => $request->headers->get('referer'), 'message' => $res]);
        }
    }

    /**
     * 下载插件
     * @param Request $request
     * @param string $name 插件标识
     */
    public function download(Request $request, $name) {
        $res = Manager::downloadPlugin($name);
        if ($res === true) {
            return view('plugin::success', ['to' => $request->headers->get('referer')]);
        } else if ($res instanceof \Exception){
            return view('plugin::error', ['to' => $request->headers->get('referer'), 'message' => $res->getMessage()]);
        } else {
            return view('plugin::error', ['to' => $request->headers->get('referer'), 'message' => $res]);
        }
    }

    /**
     * 安装插件
     * @param Request $request
     * @param string $name 插件标识
     */
    public function add(Request $request, $name) {
        $res = Manager::addPlugin($name);
        if ($res === true) {
            return view('plugin::success', ['to' => $request->headers->get('referer')]);
        } else if ($res instanceof \Exception){
            return view('plugin::error', ['to' => $request->headers->get('referer'), 'message' => $res->getMessage()]);
        } else {
            return view('plugin::error', ['to' => $request->headers->get('referer'), 'message' => $res]);
        }
    }

    /**
     * 卸载插件
     * @param Request $request
     * @param string $name 插件标识
     */
    public function remove(Request $request, $name) {
        $res = Manager::removePlugin($name);
        if ($res === true) {
            return view('plugin::success', ['to' => $request->headers->get('referer')]);
        } else if ($res instanceof \Exception){
            return view('plugin::error', ['to' => $request->headers->get('referer'), 'message' => $res->getMessage()]);
        } else {
            return view('plugin::error', ['to' => $request->headers->get('referer'), 'message' => $res]);
        }
    }

    /**
     * 启用插件
     * @param Request $request
     * @param string $name 插件标识
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function enable(Request $request, $name) {
        $res = \Gallery\Plugin\Manager::enablePlugin($name);
        if ($res === true) {
            return view('plugin::success', ['to' => $request->headers->get('referer')]);
        } else if ($res instanceof \Exception){
            return view('plugin::error', ['to' => $request->headers->get('referer'), 'message' => $res->getMessage()]);
        } else {
            return view('plugin::error', ['to' => $request->headers->get('referer'), 'message' => $res]);
        }
    }

    /**
     * 禁用插件
     * @param Request $request
     * @param string $name 插件标识
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function disable(Request $request, $name) {
        $res = \Gallery\Plugin\Manager::disablePlugin($name);
        if ($res === true) {
            return view('plugin::success', ['to' => $request->headers->get('referer')]);
        } else if ($res instanceof \Exception){
            return view('plugin::error', ['to' => $request->headers->get('referer'), 'message' => $res->getMessage()]);
        } else {
            return view('plugin::error', ['to' => $request->headers->get('referer'), 'message' => $res]);
        }
    }
}
