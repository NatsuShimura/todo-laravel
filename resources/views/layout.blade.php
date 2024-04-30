<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>ToDo App</title>
  @yield('styles')
  <link rel="stylesheet" href="/css/styles.css">
</head>
<body>

  {{-- 〈９章〉--}}
  <header>
    <nav class="my-navbar">
      <a class="my-navbar-brand" href="/">ToDo App</a>
      <div class="my-navbar-control">
        {{--
          ▼Auth::check() ユーザがログインしているかチェック
          ▼Auth::user()ログインしているユーザー情報が格納
          --}}
        @if(Auth::check())
          <span class="my-navbar-item">ようこそ, {{ Auth::user()->name }}さん</span>
          ｜
          <a href="#" id="logout" class="my-navbar-item">ログアウト</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        @else
          <a class="my-navbar-item" href="{{ route('login') }}">ログイン</a>
          ｜
          <a class="my-navbar-item" href="{{ route('register') }}">会員登録</a>
        @endif
      </div>
    </nav>
  </header>
<main>
  {{-- 【重要】yieldは親クラス側、共通しない部分--}}
  @yield('content')
</main>

{{-- ログアウト機能 --}}
@if(Auth::check())
  <script>
    document.getElementById('logout').addEventListener('click', function(event) {
      event.preventDefault();
      document.getElementById('logout-form').submit();
    });
  </script>
@endif
@yield('scripts')
</body>
</html>