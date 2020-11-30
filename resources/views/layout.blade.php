<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gallery Plugin</title>


    <!-- Styles -->
    <style>
        body{
            font-size: 10px;
        }
        * {
            margin: 0;
        }
        a {
            color: #72beff;
        }
        .navbar {
            max-width: 100%;
            width: 900px;
            margin: 20px auto;
        }
        .nav {
            font-size: 1.5rem;
            color: #1365ad;
            text-decoration: none;
        }
        .main {
            max-width: 100%;
            width: 900px;
            margin: 20px auto;
        }
    </style>
    @yield('style')
</head>
<body>
<header>
    <div class="navbar">
        <a href="{{ route('plugin.index') }}" class="nav">插件列表</a>
        <a href="{{ route('plugin.create') }}" class="nav">创建插件</a>
    </div>
</header>
<main class="main">@yield('main')</main>
<footer></footer>
@yield('script')
</body>
</html>
