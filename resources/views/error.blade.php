@extends('plugin::layout')

@section('main')
    <h1>失败！</h1>
    <p><a href="{{ $to }}">返回</a></p>
    <p>{{ $message }}</p>
@endsection
