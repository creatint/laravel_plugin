<?php

namespace Gallery\Plugin\Console;

use Gallery\Plugin\Manager;
use Gallery\Plugin\Plugin;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class RemoveCommand extends Command
{
    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'gallery:remove {plugin} {--D|data 删除数据} {--C|code 删除源码文件}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = '卸载插件';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $plugin = $this->argument('plugin');

        $res = Manager::removePlugin($plugin);

        if ($res !== true) {
            $this->error("卸载插件[$plugin]失败：$res");
            return;
        }

        $this->info("卸载插[$plugin]件成功！");
    }
}
