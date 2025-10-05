<nav class="header-nav" aria-label="Главная навигация">
  <div class="header-nav__container">
    <div class="header-nav__brand">
      <a href="/" class="header-nav__logo" title="p2p">p2p.zchk.ru</a>
    </div>
    <div class="header-nav__actions">
      @if (Auth::check())
        <a class="header-nav__btn" href="/console">Консоль</a>
        <a class="header-nav__btn" href="/logout">Выйти</a>
      @else
        <a class="header-nav__btn" href="/login">Войти</a>
        <a class="header-nav__btn" href="/register">Регистрация</a>
      @endif
    </div>
  </div>
</nav>

<style>
  .header-nav {
    background: #fff;
    border-bottom: 1px solid #ccc;
  }

  .header-nav__container {
    max-width: 1150px;
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-left: var(--bs-gutter-x, .75rem);
    padding-right: var(--bs-gutter-x, .8rem);
  }

  .header-nav__brand {
    font-size: 14px;
  }

  .header-nav__logo {
    color: #000;
    text-decoration: none;
    display: inline-block;
    font-size: 25px;
    font-weight: 700;
    line-height: 30px;
    padding: 10px 5px;
  }

  .header-nav__actions {
    display: flex;
  }

  .header-nav__btn {
    background: #536aad;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    padding: 6px 20px;
    margin-left: 10px;
    transition: background-color .15s ease-in-out;
  }

  .header-nav__btn:hover {
    background-color: #286090 !important;
    color: #fff;
    text-decoration: none;
  }

  @media (max-width: 768px) {
    .header-nav__logo {
      font-size: 19px !important;
    }
  }
</style>
