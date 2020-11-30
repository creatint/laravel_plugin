@extends('plugin::layout')

@section('style')
    <style>
        .title {
            margin: 10px 0;
        }
        .table {
            display: grid;
            grid-template-columns: 1fr 100px 1fr 100px;
        }
        .table .table-cell {
            border-bottom: 1px solid #bebebe;
            padding: 8px;
        }
        .table .table-cell:nth-child(-n+4) {
            border-top: 1px solid #bebebe;
        }
    </style>
@endsection
@section('main')
    <h2 class="title">已安装</h2>
    <div class="table">
        <div class="table-cell">
            名称
        </div>
        <div class="table-cell">
            作者
        </div>
        <div class="table-cell">
            描述
        </div>
        <div class="table-cell">
            操作
        </div>
        @foreach($activated as $i => $plugin)
            <div class="table-cell">
                <h2><a href="{{ route('plugin.show', ['name' => $plugin::name()]) }}">{{ $plugin::title() }}</a></h2>
                <span>{{ $plugin::version() }}</span>
            </div>
            <div class="table-cell">
                <a href="{{ $plugin::authorUrl() }}">{{ $plugin::author() }}</a>
            </div>
            <div class="table-cell">
                {{ $plugin::description() }}
            </div>
            <div class="table-cell">
                @if (\Gallery\Plugin\Manager::$hasDatabases)
                    @if($plugin->status > 0)
                        <a href="{{ route('plugin.disable', ['name' => $plugin::name()]) }}">禁用</a>
                    @elseif($plugin->status == 0)
                        <a href="{{ route('plugin.enable', ['name' => $plugin::name()]) }}">启用</a>
                    @elseif($plugin->status == -1)
                        <a href="{{ route('plugin.add', ['name' => $plugin::name()]) }}">安装</a>
                    @elseif($plugin->status == -2)
                        <a href="{{ route('plugin.download', ['name' => $plugin::name()]) }}">下载</a>
                    @endif
                    @if($plugin->status >= -1)
                        <a href="{{ route('plugin.remove', ['name' => $plugin::name()]) }}">卸载</a>
                    @endif
                @else
                    @if($plugin->status > 0)
                        <a>已启用</a>
                    @elseif($plugin->status == 0)
                        已禁用
                    @endif
                @endif
            </div>
        @endforeach
    </div>
    <h2 class="title">未安装</h2>
    <div class="table">
        <div class="table-cell">
            名称
        </div>
        <div class="table-cell">
            作者
        </div>
        <div class="table-cell">
            描述
        </div>
        <div class="table-cell">
            操作
        </div>
        @foreach($unInstalled as $i => $plugin)
            <div class="table-cell">
                <h2><a href="{{ route('plugin.show', ['name' => $plugin::name()]) }}">{{ $plugin::title() }}</a></h2>
                <span>{{ $plugin::version() }}</span>
            </div>
            <div class="table-cell">
                <a href="{{ $plugin::authorUrl() }}">{{ $plugin::author() }}</a>
            </div>
            <div class="table-cell">
                {{ $plugin::description() }}
            </div>
            <div class="table-cell">
                @if($plugin->status == -1)
                    <a href="{{ route('plugin.add', ['name' => $plugin::name()]) }}">安装</a>
                @elseif($plugin->status == -2)
                    <a href="{{ route('plugin.download', ['name' => $plugin::name()]) }}">下载</a>
                @endif
            </div>
        @endforeach
    </div>
    <h2 class="title">未下载</h2>
    <div class="table">
        <div class="table-cell">
            名称
        </div>
        <div class="table-cell">
            作者
        </div>
        <div class="table-cell">
            描述
        </div>
        <div class="table-cell">
            操作
        </div>
        @foreach($unDownloaded as $i => $plugin)
            <div class="table-cell">
                <h2><a href="{{ route('plugin.show', ['name' => $plugin->name]) }}">{{ $plugin->title }}</a></h2>
                <span>{{ $plugin->version }}</span>
            </div>
            <div class="table-cell">
                <a href="{{ $plugin->authorUrl }}">{{ $plugin->author }}</a>
            </div>
            <div class="table-cell">
                {{ $plugin->description }}
            </div>
            <div class="table-cell">
                <a href="{{ route('plugin.download', ['name' => $plugin->name]) }}">下载</a>
            </div>
        @endforeach
    </div>
@endsection