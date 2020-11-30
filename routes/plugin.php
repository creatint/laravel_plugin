<?php

use Illuminate\Support\Facades\Route;
use Gallery\Plugin\Http\Controllers\PluginController;


//列表
Route::get('/index', [PluginController::class, 'index'])->name('plugin.index');
//详情
Route::get('/show/{name}', [PluginController::class, 'show'])->name('plugin.show');
//创建
Route::get('/create', [PluginController::class, 'create'])->name('plugin.create');
//保存
Route::get('/store', [PluginController::class, 'store'])->name('plugin.store');
//启用
Route::get('/enable/{name}', [PluginController::class, 'enable'])->name('plugin.enable');
//禁用
Route::get('/disable/{name}', [PluginController::class, 'disable'])->name('plugin.disable');
//卸载
Route::get('/remove/{name}', [PluginController::class, 'remove'])->name('plugin.remove');
//安装
Route::get('/add/{name}', [PluginController::class, 'add'])->name('plugin.add');
//下载
Route::get('/download/{name}', [PluginController::class, 'download'])->name('plugin.download');
