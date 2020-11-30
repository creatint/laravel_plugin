# Gallery Plugin

一个使您的 [Laravel]() 项目获得即插即用插件功能的扩展。


## 项目概述

启发于 [WordPress](https://wordpress.org)，安装 [GalleryPlugin]() (以下简称 `GP` )后，您的 Laravel 项目即可拥有即插即用的插件扩展能力。

> 插件需要基于 `GP` 并配合相应的 `钩子放置策略` 专门开发。
>
> 插件可发布到 [Composer]() 并通过其安装。

## 使用场景

假设您在用 Laravel 开发一套 CMS 系统，
当您想要对它增加插件能力，以便为您的用户提供热插拔插件的功能时，
您可以选择 `GP`。

项目安装 `GP` 后，按需放置钩子，然后按照 `钩子放置策略` 开发相应的插件。

插件发布后，使用此 CMS 系统的用户即可安装此插件以扩展其网站的功能。



## 功能

- [x] 安装插件 —— 安装插件
- [x] 卸载插件 —— 卸载插件
- [x] 启用插件 —— 修改插件状态以启用
- [x] 禁用插件 —— 修改插件状态以禁用
- [x] 插件列表 —— 获取插件列表
- [x] 挂载钩子 —— 向钩子挂载函数或方法
- [x] 执行钩子 —— 执行钩子上挂载的函数或方法
- [ ] 禁用钩子 —— 禁止执行钩子
- [ ] 卸载钩子 —— 卸载钩子上挂载的函数或方法
- [ ] 钩子列表 —— 获取钩子列表
- [ ] 创建插件 —— 创建本地插件
- [ ] 发布插件 —— 发布到插件市场
- [ ] 下载插件 —— 从 `Composer` 或网站下载插件

## 环境要求
- PHP 7.0+
- Mysql 5.7+
- Laravel 5.8+

## 安装

- 通过 [Composer](https://getcomposer.org/) 安装

```bash
composer require gallery/plugin
php artisan vendor:publish --provider=Gallery\Plugin\PluginServiceProvider
php artisan migrate
```
- 通过源码安装

首先下载 [源码包]()，解压后放在你希望放在的位置。

在 Composer 配置中添加 classmap 参数，如：

```json
{
  "autolaod": {
    "classmap": [
      "项目中 GP 文件夹的路径"
    ]
  }
}
```

执行 `composer dump-autoload`

最后把 `\Gallery\Plugin\PluginServiceProvider::class` 放到 `config/app.php` 的 `providers` 中。

## 核心概念

#### 钩子原理

钩子是程序执行过程中特定的点或事件，把插件的一个或多个函数或方法挂载到钩子上，就可以在指定的时机调用他们。

#### 钩子类型

- 动作钩子

  激发钩子时，钩子按优先级 `priority` 从小到大的顺序调用挂载其上的所有函数或方法；
  
  同优先级下按先后挂载的次序先后调用；
  
  调用是相互独立的，可传递多个参数，每个函数或方法接收的参数都是相同的。

- 过滤器钩子

  激发钩子时，钩子按优先级 `priority` 从小到大的顺序调用挂载其上的所有函数或方法；
  
  同优先级下按先后挂载的次序先后调用；
  
  调用是串联接力的，只传递单一参数，函数或方法的返回值是其后函数或方法的参数。 

- 一次性钩子

  激发钩子时，只调用一个函数或方法，且调用的是按照上面👆描述的执行次序的第一个函数或方法。


#### 放置策略

事实上每一个项目都有不同的需求和不同的结构。
在哪里放置钩子，钩子是什么类型，放置多少钩子，每个项目都是不同的，或者说很难做到一致。
因此，不同的项目有不同的放置策略，基于某一策略开发的插件难以在另一个项目中使用。一个项目只能安装符合它的放置策略的插件。

当项目升级时，放置策略可能会发生变化，项目开发者要考虑长远，尽量满足较小差异下的兼容性。
当然如果难以兼容或差异较大，项目开发者就要有放弃所有旧版本插件的准备，或者自己升级插件，或者鼓励插件开发者社区更新他们的插件。

#### 策略标签

对于不同的 `放置策略`，我们用 `策略标签` 加以区分。

> 例如，有A项目集成了 `GP`，在概念上诞生了A项目的策略标签，不妨叫 `a-strategy-v1.0`，
> 只要是满足 `a-strategy-v1.0` 策略的插件，就可以安装在A项目上。

同时，同一个策略标签还可能有不同的版本，小版本之间需满足兼容性，大版本之间不能混用。

如果某插件同时支持多种不同的策略，那么它可以应用在不同的项目中。建议多做测试。

## 使用

#### 安装插件

- 通过 [Composer](https://getcomposer.org/) 安装

```bash
composer require foo/bar
php artisan gallery:add foo_bar
```
- 通过源码安装

下载源码包，解压后放在自定义的插件文件夹中，如 `/plugins`。

修改配置文件 `config/plugin.php` 的 `root` 值为 `plugins/`。

若有 `\Foo\Bar\SomethingServiceProvider::class` ，请放到 `config/app.php` 的 `providers` 中。

最后执行：

- 命令行方式：
```bash
php artisan gallery:add foo_bar
```

- 代码方式：
```php
\Gallery\Plugin\Plugin::addPlugin('foo_bar');
```

#### 启用插件

- 命令行方式：
```bash
php artisan gallery:enable foo_bar
```

- 代码方式：
```php
\Gallery\Plugin\Plugin::enablePlugin('foo_bar');
```

#### 禁用插件

- 命令行方式：
```bash
php artisan gallery:disable foo_bar
```

- 代码方式：
```php
\Gallery\Plugin\Plugin::disablePlugin('foo_bar');
```

#### 卸载插件

- 命令行方式：
```bash
php artisan gallery:remove foo_bar
```

- 代码方式：
```php
\Gallery\Plugin\Plugin::removePlugin('foo_bar');
```

#### 插件列表

- 命令行方式：
```bash
php artisan gallery:plugin-list
```

- 代码方式：
```php
\Gallery\Plugin\Plugin::getPlugins();
```

#### 挂载钩子

```php
// 注册动作钩子
add_action('hook1', function(){
    echo '本段文字将在系统初始化时输出';
});

// 注册过滤器钩子
add_filter('hook2', function(){
    echo '本段文字将在系统初始化时输出';
});

// 注册一次性钩子
add_once('hook3', function(){
    echo '本段文字将在系统初始化时输出';
});
```

#### 执行钩子


```php
// 在业务流程或视图中执行钩子
play('hook2');
```

#### 禁用钩子

把插件的 `status` 字段改成 `0`。

```php
return [
    'hooks' => [
        'init' => [
            'name' => 'init',
            'type' => 1,
            'desc' => 'PluginServiceProvider::boot()执行时',
            'status' => 0
        ]
    ]
];
```

#### 钩子列表

```php
\Gallery\Plugin\Plugin::getHooks();
```


## 插件开发

- 按照 `prs4` 规则编写插件，插件入口类的全限定类名即文件路径。

- 默认入口为 `Plugin.php`，可在配置文件中修改。

- 避免其他文件名与入口文件名相同。

- 插件入口文件不可在3级目录以下。

- 插件入口必须继承 `Gallery\Plugin\Plugin` 类。

- 覆盖父类来定义插件信息。
