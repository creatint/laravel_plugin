@extends('plugin::layout')

@section('style')
    <style>
        .table {
            display: grid;
            grid-template-columns: 100px 1fr;
        }
        .table .table-cell {
            border-bottom: 1px solid #bebebe;
            padding: 8px;
        }
        .table .table-cell:nth-child(-n+2) {
            border-top: 1px solid #bebebe;
        }
    </style>
@endsection
@section('main')
    @if($plugin && !$unDownloaded)
        <div class="table">
            <div class="table-cell">
                标识
            </div>
            <div class="table-cell">
                {{ $plugin::name() }}
            </div>
            <div class="table-cell">
                名称
            </div>
            <div class="table-cell">
                <h1>{{ $plugin::title() }}</h1>
            </div>
            <div class="table-cell">
                版本
            </div>
            <div class="table-cell">
                {{ $plugin::version() }}
            </div>
            <div class="table-cell">
                作者
            </div>
            <div class="table-cell">
                {{ $plugin::author() }}
            </div>
            <div class="table-cell">
                描述
            </div>
            <div class="table-cell">
                {{ $plugin::description() }}
            </div>
            <div class="table-cell">
                状态
            </div>
            <div class="table-cell">
                @switch($plugin->status)
                    @case(0)
                    禁用
                    @break(0)
                    @case(1)
                    启用
                    @break(1)
                    @case(0)
                    调试
                    @break(0)
                @endswitch
            </div>
        </div>
    @elseif($plugin && $unDownloaded)
        <div class="table">
            <div class="table-cell">
                标识
            </div>
            <div class="table-cell">
                {{ $plugin->name }}
            </div>
            <div class="table-cell">
                名称
            </div>
            <div class="table-cell">
                <h1>{{ $plugin->title }}</h1>
            </div>
            <div class="table-cell">
                版本
            </div>
            <div class="table-cell">
                {{ $plugin->version }}
            </div>
            <div class="table-cell">
                作者
            </div>
            <div class="table-cell">
                {{ $plugin->author }}
            </div>
            <div class="table-cell">
                描述
            </div>
            <div class="table-cell">
                {{ $plugin->description }}
            </div>
            <div class="table-cell">
                状态
            </div>
            <div class="table-cell">
                未下载
            </div>
        </div>
    @else
        无插件
    @endif
@endsection