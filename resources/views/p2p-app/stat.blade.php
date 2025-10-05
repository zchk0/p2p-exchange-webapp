<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Статистика, графики курсов P2P | p2p.zchk.ru</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2.2.1/dist/chartjs-plugin-annotation.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    body {
      background-attachment: fixed !important;
      background: linear-gradient(0deg, rgb(27 56 44 / 13%), rgb(242 242 255));
    }
    .top-0 { top: 0 !important }
    .top-50 { top: 50% !important }
    .top-100 { top: 100% !important }
    @media (max-width:768px) {
      .table_sort_div { padding: 16px 10px 20px !important; }
    }
    .table_sort table { border-collapse: collapse; }
    .table_sort td, .table_sort th {
      height: 50px; text-align: center; border: 1px solid #846868; padding: 0 2px;
    }
  </style>
</head>
<body>
<div style="min-height: calc(100vh - 180px);padding: 0 0 50px;">
  <x-price-line />

  @include('layouts.nav')

  <div class="container" style="padding-top: 55px;">
    <div class="panel panel-primary" style="border-color: #b9b9b9;">
      <div class="panel-heading" style="padding: 15px 20px;background-color: #5a5a5a;border-color: #6b6c6c;">
        Статистика отслеживаемых курсов ЦБ и бирж P2P
      </div>
      <div class="panel-body">
        <canvas id="chartRates"></canvas>

        @auth
          @if(!is_null($portfolioRub))
          <div class="table_sort_div" style=" margin-top: 15px;line-height: 1.7;padding: 16px 20px 20px;">
            <table class="table_sort" style="background: whitesmoke;">
              <tbody id="table-logs">
                <tr>
                  <td style="width: 400px;">
                    Ваша средняя цена покупки <br>(на графике — горизонтальная красная черта)
                  </td>
                  <td style="width: 145px;">
                    {{ number_format($avgBuyPrice, 2, '.', ' ') }} $
                  </td>
                </tr>
                <tr>
                  <td style="width: 400px;">Размер портфеля:</td>
                  <td style="width: 145px;">
                    {{ number_format($portfolioRub, 2, '.', ' ') }} руб. ({{ number_format($totalValue, 2, '.', ' ') }} $)
                  </td>
                </tr>
                <tr>
                  <td style="width: 400px;">Итоговый(-ая) прибыль/убыток:</td>
                  <td style="width: 145px;">
                    {{ is_null($profitRub) ? '—' : number_format($profitRub, 2, '.', ' ') . ' руб.' }}
                    @if(!is_null($profitPct))
                      @php($color = $profitPct >= 0 ? 'green' : 'red')
                      <br>(<b style="color: {{ $color }}">{{ number_format($profitPct, 2, '.', ' ') }} %</b>)
                    @endif
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          @endif
        @endauth
      </div>
      <div class="panel-footer" style="font-style: italic;">
        На графике выводятся два вида курсов USD/RUB — официальный от Центрального Банка России и фактический на биржевых P2P обменниках.<br>
        Наблюдение ведется (т.е. собираются статистические данные) с 13.02.2023.
      </div>
    </div>
  </div>

  <div class="container" style="padding-top: 25px;">
    <div class="panel panel-primary" style="border-color: #b9b9b9;">
      <div class="panel-heading" style="padding: 15px 20px;background-color: #5a5a5a;border-color: #6b6c6c;">
        Разница курсов (спред) пары USD/RUB
      </div>
      <div class="panel-body">
        <canvas id="chartSpread"></canvas>
      </div>
      <div class="panel-footer" style="font-style: italic;">
        На графике выводится разница в % между официальным курсом от Центрального Банка России и фактическим на биржевых P2P обменниках.
      </div>
    </div>
  </div>

  @include('layouts.footer')
</div>

<script>
  // Данные из контроллера
  const labels   = @json($priceDates);
  const p2pData  = @json($p2pPrices);
  const cbData   = @json($cbPrices);
  const spreads  = @json($spreads);
  const avgBuy   = @json($avgBuyPrice);
  const yMin     = @json(max(0, $lineportf - 5));
  const yMax     = @json((!empty($p2pPrices) ? max($p2pPrices) + 5 : 10));

  const ctxRates = document.getElementById('chartRates').getContext('2d');
  const ctxSpr   = document.getElementById('chartSpread').getContext('2d');

  // График 1 — курсы
  new Chart(ctxRates, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'P2P',
          data: p2pData,
          backgroundColor: 'rgba(237, 237, 237, 0.4)',
          borderColor: '#0c0c0c',
          fill: true,
          borderWidth: 1,
          pointRadius: 1,
          pointBorderColor: '#0c0c0c'
        },
        {
          label: 'ЦБ РФ',
          data: cbData,
          backgroundColor: 'rgba(0, 128, 0, 0.3)',
          borderColor: '#008000',
          borderWidth: 2,
          pointRadius: 0
        }
      ]
    },
    options: {
      plugins: {
        annotation: {
          annotations: (avgBuy && avgBuy > 0) ? {
            avgBuyLine: {
              type: 'line',
              yMin: avgBuy,
              yMax: avgBuy,
              borderColor: '#ff0000',
              borderWidth: 1,
            }
          } : {}
        },
        zoom: {
          zoom: {
            drag: { enabled: true },
            wheel: { enabled: true },
            pinch: { enabled: true },
            mode: 'x',
          }
        }
      },
      scales: { y: { suggestedMin: yMin, suggestedMax: yMax } }
    }
  });

  // График 2 — спред
  new Chart(ctxSpr, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Спред',
          data: spreads,
          backgroundColor: 'rgba(237, 237, 237, 0.55)',
          borderColor: '#0c0c0c',
          fill: true,
          borderWidth: 2,
          pointRadius: 1,
          pointBorderColor: '#0c0c0c'
        }
      ]
    },
    options: {
      plugins: {
        zoom: {
          zoom: {
            drag: { enabled: true },
            wheel: { enabled: true },
            pinch: { enabled: true },
            mode: 'x',
          }
        }
      },
      scales: {
        y: {
          suggestedMin: 0,
          suggestedMax: spreads && spreads.length ? Math.max.apply(null, spreads) + 1 : 5,
          ticks: { callback: (value) => value + '%' }
        }
      }
    }
  });
</script>
</body>
</html>
