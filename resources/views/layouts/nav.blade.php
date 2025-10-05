<nav>
  <div class="container0">
    <div><a href="/" class="logo" title="p2p">p2p.zchk.ru</a></div>
    <div>
      <div class="top-buttons">
        @if (Auth::check())
          <a class="login-req-btn" href="/console">Консоль</a>
          <a class="login-req-btn" href="/logout">Выйти</a>
        @else
          <a class="login-req-btn" href="/login">Войти</a>
          <a class="login-req-btn" href="/register">Регистрация</a>
        @endif
      </div>
    </div>
  </div>
</nav>