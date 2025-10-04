<style>
  a {
    color: #0d6efd;
    text-decoration: underline
  }

  a:hover {
    color: #0a58ca
  }

  a:not([href]):not([class]),
  a:not([href]):not([class]):hover {
    color: inherit;
    text-decoration: none
  }

  [role=button] {
    cursor: pointer
  }

  .footer {
    background: #ededed;
    height: 180px;
    padding: 40px 30px 0;
    text-align: center
  }

  .footer .mini-links {
    display: flex;
    flex-wrap: wrap;
    justify-content: center
  }

  .footer .mini-links a {
    color: #777;
    font-size: 15px;
    margin: 5px 20px;
    text-decoration: none
  }

  .footer .mini-links a:hover {
    text-decoration: underline
  }
</style>
<div class="footer">
  <div class="mini-links">
    <a href="/">Главная</a>
    <a href="/faq">FAQ</a>
    <a href="/stat">Статистика</a>
  </div>
  <div style="font-style: italic; color: #c6c9c9; padding-top: 11px;">Александр Зайченко | Laravel
    v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</div>
</div>