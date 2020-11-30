<?php

namespace Gallery\Plugin;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;


class PluginServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @return void
     */
    public function register()
    {
        // 注册自动加载函数
        spl_autoload_register('Gallery\Plugin\Manager::autoload');

        $this->mergeConfigFrom(__DIR__ . '/../config/plugin.php', 'plugin');
    }

    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->configureCommands();
            $this->configurePublishing();
//            // TODO:安装时临时下载，安装后删除
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
        $this->configureRoutes();
        $this->resolvePlugins();
        $this->configureViews();
    }

    protected function resolvePlugins()
    {
        // 搜索插件入口文件并注册插件
        $files = search_files(base_path(config('plugin.root')), 'Plugin.php', 3);
        foreach ($files as $file) {
            $class = get_file_class($file, base_path(config('plugin.root')), config('plugin.ext'));
            if (class_exists($class)) {
                call_class_func($class, 'register');
            }
        }

        if (Schema::hasTable(config('plugin.table'))) {
            // 已安装的插件列表
            $pluginsInDb = config('plugin.model')::all();
            $pluginsInDb->each(function ($pluginModel) {
                // 由信息实例创建实例
                if (!empty(Manager::$raws[$pluginModel->name]) && ($information = Manager::$raws[$pluginModel->name])) {
                    if (!empty($information->closure)) {
                        // 实例化插件
                        $plugin = ($information->closure)();
                        // 移除已处理的插件信息
                        unset(Manager::$raws[$pluginModel->name]);
                    }
                }

                // 由数据库中的类创建
                if (empty($plugin) && !empty($pluginModel->class) && class_exists($pluginModel->class)) {
                    $plugin = new $pluginModel->class;
                }
                if (empty($plugin)) {
                    // 源码不存在，添加到未下载
                    Manager::$unDownloaded[$pluginModel->name] = $pluginModel;
                    return;
                }

                $plugin->model = $pluginModel;
                $plugin->status = $pluginModel->status;

                // 加入已处理列表
                Manager::$resolved[$pluginModel->name] = $plugin;

                // 加入已激活列表
                if ($plugin->status >= 1) {
                    Manager::$activated[$pluginModel->name] = $plugin;
                }
            });
        } else {
            Manager::$hasDatabases = false;
        }


        // 处理插件信息列表
        foreach (Manager::$raws as $name => $info) {
            if (empty($info->closure)) {
                continue;
            }
            $plugin = ($info->closure)();
            unset(Manager::$raws[$name]);
            $plugin->status = 0;
            Manager::$resolved[$name] = $plugin;
            // 加入已激活列表
            if (!empty(config('plugin.plugins')[$name]) && config('plugin.plugins')[$name]['enable']) {
                $plugin->status = 1;
                Manager::$activated[$name] = $plugin;
            }
        }

        // TODO:从配置文件读取插件

        // TODO:未安装的非自动注册插件列表
        // 即非composer安装的，非注册服务提供者的，放在自定义插件文件夹下的插件

        // 执行插件初始化
        foreach (Manager::$activated as $plugin) {
            $plugin->run();
        }
    }

    /**
     * Configure the commands offered by the application.
     * @return void
     */
    protected function configureCommands()
    {
        $this->commands([
            Console\AddCommand::class,
            Console\RemoveCommand::class,
        ]);
    }

    /**
     * Configure publishing for the package.
     * @return void
     */
    protected function configurePublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/plugin.php' => config_path('plugin.php'),
        ], 'plugin-config');

        $this->publishes([
            __DIR__ . '/../database/migrations/2020_11_09_063026_create_plugins_table.php'
            => database_path('migrations/2020_11_09_063026_create_plugins_table.php'),
        ], 'plugin-migrations');

        $this->publishes([
            __DIR__ . '/../routes/plugin.php' => base_path('routes/plugin.php'),
        ], 'plugin-routes');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/plugin'),
        ], 'plugin-views');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/plugin'),
        ], 'plugin-lang');
    }

    /**
     * Configure the routes offered by the application.
     * @return void
     */
    protected function configureRoutes()
    {
        Route::group([
            'namespace' => 'Gallery\Plugin\Http\Controllers',
            'domain' => config('plugin.domain', null),
            'prefix' => config('plugin.path', 'plugin'),
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/plugin.php');
        });
    }

    protected function configureViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'plugin');
    }
}
