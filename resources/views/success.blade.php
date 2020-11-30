@extends('plugin::layout')

@section('main')
    <h1>操作成功！</h1>
    <p>3秒后自动返回...</p>
@endsection
@section('script')
    <script>
        const to = '{{ $to }}'
        setTimeout(() => {
            window.location.href = to
        }, parseInt('{{ $time ?? 3000 }}'))
    </script>
@endsection
