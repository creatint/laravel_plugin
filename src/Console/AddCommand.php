<?php

namespace Gallery\Plugin\Console;

use Gallery\Plugin\Manager;
use Gallery\Plugin\Plugin;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class AddCommand extends Command
{
    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'gallery:add {plugin}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = '安装插件';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $plugin = $this->argument('plugin');

        if (!empty($plugin) && !empty(Manager::$raws[$plugin])) {
            $plugin = $this->choice('Please choose a plugin to install', array_keys(Manager::$raws));
        }

        $res = Manager::addPlugin($plugin);

        if ($res !== true) {
            $this->error("安装插件失败：{$res}");
            return;
        }

        $this->info("安装插件[$plugin]成功！");
    }
}
