<div class="price-line">
  <div class="price-line__inner">
    <div class="price-line__group">
      <div class="price-line__title">USDT:</div>

      <div class="price-line__value" title="Средняя цена за 1 USDT">
        <span class="price-line__number">{{ $mclist['usd']['price'] ?? 0 }}</span>
        <span class="price-line__unit">USD</span>
      </div>
      <div class="price-line__value" title="Средняя цена за 1 USDT">
        <span class="price-line__number">{{ $mclist['eur']['price'] ?? 0 }}</span>
        <span class="price-line__unit">EUR</span>
      </div>
      <div class="price-line__value" title="Средняя цена метода TINKOFF за 1 USDT">
        <span class="price-line__number price-line__number--accent">{{ $mclist['rub']['price'] ?? 0 }}</span>
        <span class="price-line__unit">RUB</span>
      </div>
      <div class="price-line__value" title="Средняя цена за 1 USDT">
        <span class="price-line__number">{{ $mclist['uah']['price'] ?? 0 }}</span>
        <span class="price-line__unit">UAH</span>
      </div>
      <div class="price-line__value" title="Средняя цена метода Kaspi Bank за 1 USDT">
        <span class="price-line__number">{{ $mclist['kzt']['price'] ?? 0 }}</span>
        <span class="price-line__unit">KZT</span>
      </div>
      <div class="price-line__value" title="Средняя цена метода Ziraat за 1 USDT">
        <span class="price-line__number">{{ $mclist['try']['price'] ?? 0 }}</span>
        <span class="price-line__unit">TRY</span>
      </div>
    </div>

    <div class="price-line__group">
      <div class="price-line__title">USD (ЦБ {{ $date }}):</div>

      <div class="price-line__value" title="Курс ЦБ">
        <span class="price-line__number">{{ $mclist['eur']['cbprice'] ?? 0 }}</span>
        <span class="price-line__unit">EUR</span>
      </div>
      <div class="price-line__value" title="Курс ЦБ РФ">
        <span class="price-line__number">{{ $mclist['rub']['cbprice'] ?? 0 }}</span>
        <span class="price-line__unit">RUB</span>
      </div>
      <div class="price-line__value" title="Курс ЦБ">
        <span class="price-line__number">{{ $mclist['uah']['cbprice'] ?? 0 }}</span>
        <span class="price-line__unit">UAH</span>
      </div>
      <div class="price-line__value" title="Курс ЦБ РК">
        <span class="price-line__number">{{ $mclist['kzt']['cbprice'] ?? 0 }}</span>
        <span class="price-line__unit">KZT</span>
      </div>
      <div class="price-line__value" title="Курс ЦБ">
        <span class="price-line__number">{{ $mclist['try']['cbprice'] ?? 0 }}</span>
        <span class="price-line__unit">TRY</span>
      </div>
    </div>
  </div>
</div>

<style>
  .price-line {
    background: #fff;
    border-bottom: 1px solid #ccc;
    cursor: default;
    opacity: .7;
    overflow-x: auto;
    transition: all .2s;
  }

  .price-line__inner {
    display: flex;
    align-items: center;
    padding: 15px 15px;
  }

  .price-line__group {
    display: flex;
    align-items: center;
    font-size: 13px;
    white-space: nowrap;
    margin-right: 30px;
  }

  .price-line__group:last-child {
    margin-right: 0;
  }

  .price-line__title {
    color: #58667e;
    font-weight: 600;
    margin-right: 15px;
  }

  .price-line__value {
    background: #efefef;
    color: #58667e;
    margin: 0 15px 0 0;
    padding: 0 11px;
  }

  .price-line__unit {
    color: #384252;
    font-size: 10px;
    font-style: normal;
  }

  .price-line:hover {
    opacity: 1;
  }

  .price-line:hover .price-line__title {
    color: #0c1b48ff;
  }

  /* Модификатор для акцентного числа (бывший .als-usd) */
  .price-line__number--accent {
    font-weight: 600;
  }
</style>