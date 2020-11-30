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
    <div class="table">
        <div class="table-cell">
            标识
        </div>
        <div class="table-cell">
        </div>
        <div class="table-cell">
            名称
        </div>
        <div class="table-cell">
        </div>
        <div class="table-cell">
            版本
        </div>
        <div class="table-cell">
        </div>
        <div class="table-cell">
            作者
        </div>
        <div class="table-cell">
        </div>
        <div class="table-cell">
            描述
        </div>
        <div class="table-cell">
        </div>
    </div>
    <button>提交</button>
@endsection