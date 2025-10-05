<?php
  header('Access-Control-Allow-Origin: *');

  $dataUSDT = $CBprice = $P2Pprice = array();
  $pricedate = $spreadRUB = array();
 
  foreach($currusdt as $t) {
        $dataUSDT[$t->date]['p2p'][] = $t->price;
        $dataUSDT[$t->date]['cb'][] = $t->cbankprice;
  }
  foreach($dataUSDT as $t => $vv) {
        $pricedate[] = $t; //записывам дату в массив
        $CBprice[] = $vv['cb'][0]; //записывам цену ЦБ
        $s = $i = 0;
        foreach($vv['p2p'] as $v){$i++;$s += floatval($v);}
	    $p2pprr = round($s / $i, 3);
	    
	    $P2Pprice[] = $p2pprr;
	    $spreadRUB[] = abs(($vv['cb'][0] - $p2pprr) / (($vv['cb'][0] + $p2pprr) / 2))*100;
  }
  $lineportf = min($P2Pprice);
  $lineportf1 = 0;
  if (!empty($portfoliouser)) {
      $value_port = 0;
      $old_port = 0;
  
      foreach($portfoliouser as $t){
          $value_port += $t->value;
          $old_port += $t->value * $t->price;
        }
      $current_port = $value_port * $mclist['rub']['price'];
      $lineportf1 = $lineportf = $old_port / $value_port;
  }
?>
<!DOCTYPE html>
<html lang="ru">
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

    .top-0 {
      top: 0 !important
    }

    .top-50 {
      top: 50% !important
    }

    .top-100 {
      top: 100% !important
    }

    @media (max-width:768px) {
      nav .logo {
        font-size: 19px !important;
      }
    }

    .container0 {
      max-width: 1150px;
      align-items: center;
      display: flex;
      justify-content: space-between;
      padding: 0 15px;
      margin-left: auto;
      margin-right: auto;
      padding-left: var(--bs-gutter-x, .75rem);
      padding-right: var(--bs-gutter-x, .8rem);
      width: 100%;
    }

    .nav {
      display: flex;
      flex-wrap: wrap;
      list-style: none;
      margin-bottom: 0;
      padding-left: 0
    }

    nav {
      background: #fff;
      border-bottom: 1px solid #ccc
    }

    .container0>div {
      font-size: 14px
    }

    nav .logo {
      color: #000;
      display: inline-block;
      font-size: 25px;
      font-weight: 700;
      line-height: 30px;
      padding: 10px 5px;
      text-decoration: none
    }

    .top-buttons {
      display: flex
    }

    .top-buttons>a {
      background: #536aad;
      border-radius: 10px;
      color: #fff;
      margin-left: 10px;
      padding: 5px 20px;
      text-decoration: none
    }

    .login-req-btn:hover,
    .btn-primary:hover {
      color: #fff;
      background-color: #286090 !important;
      border-color: #204d74;
    }

    .btn-primary {
      background-color: #536aad;
      margin-right: 5px;
    }
  </style>
</head>

<body>
  <div style="min-height: calc(100vh - 180px);padding: 0 0 50px;">
    <x-price-line />

    @include('layouts.nav')

    <div class="container" style="padding-top: 55px;">
      <div class="panel panel-primary" style="border-color: #b9b9b9;">
        <div class="panel-heading" style="padding: 15px 20px;background-color: #5a5a5a;border-color: #6b6c6c;">Статитика
          отслеживаемых курсов ЦБ и бирж P2P</div>
        <div class="panel-body">

          <canvas id="myChart"></canvas>
          @if (Auth::check())<?php if (!empty($portfoliouser)) { ?>
          <div class="table_sort_div" style=" margin-top: 15px;line-height: 1.7;padding: 16px 20px 20px;">
            <table class="table_sort" style="background: whitesmoke;">
              <tbody id="table-logs">
                <tr>
                  <td style="width: 400px;">Ваша средняя цена покупки <br>(на графике горизонтальная красная черта)</td>
                  <td style="width: 145px;"> <?php echo round($old_port / $value_port, 2) . ' $'; ?> </td>
                </tr>
                <tr>
                  <td style="width: 400px;">Размер портфеля:</td>
                  <td style="width: 145px;">
                    <?php echo round($value_port * $mclist['rub']['price'], 2) . ' руб. ('. $value_port .' $)'; ?> </td>
                </tr>
                <tr>
                  <td style="width: 400px;">Итоговый(-ая) прибыль/убыток:</td>
                  <td style="width: 145px;"> <?php echo round($value_port * $mclist['rub']['price'] - $old_port, 2); ?>
                    руб.
                    <br><?php $color_port = ($current_port - $old_port > 0) ? 'green' : 'red'; echo '(<b style="color:'. $color_port .'">' . round(($current_port / $old_port - 1) * 100, 2) . ' %</b>)'; ?>
                  </td>
                </tr>
              </tbody>
            </table>
            <style>
              .table_sort table {
                border-collapse: collapse;
              }
              .table_sort td,
              .table_sort th {
                height: 50px;
                text-align: center;
                border: 1px solid #846868;
                padding: 0 2px;
              }
            </style>
          </div>
          <?php } ?>@endif
        </div>
        <div class="panel-footer" style="font-style: italic;">На графике выводятся два вида курсов USD/RUB — официальный
          от Центрального Банка России и фактический на биржевых P2P обменниках. <br>Наблюдение ведется (т.е. собираются
          статистические данные) с 13.02.2023.
        </div>
      </div>
    </div>

    <div class="container" style="padding-top: 25px;">
      <div class="panel panel-primary" style="border-color: #b9b9b9;">
        <div class="panel-heading" style="padding: 15px 20px;background-color: #5a5a5a;border-color: #6b6c6c;">Разница
          курсов (спред) пары USD/RUB</div>
        <div class="panel-body">

          <canvas id="myChart1"></canvas>

        </div>
        <div class="panel-footer" style="font-style: italic;">На графике выводится разница в % между официальным курсом
          от Центрального Банка России и фактическим на биржевых P2P обменниках.
        </div>
      </div>

    </div>
<script>
  // Получение элемента canvas
  var ctx = document.getElementById('myChart').getContext('2d');
  var ctx1 = document.getElementById('myChart1').getContext('2d');

  // Создание 1 графика
  var myChart = new Chart(ctx, {
      type: 'line',
      data: {
          labels: <?php echo json_encode($pricedate); ?>,
          datasets: [{
              label: 'P2P',
              data: <?php echo json_encode($P2Pprice); ?>,
              backgroundColor: 'rgba(237, 237, 237, 0.4) ',
              borderColor: '#0c0c0c',
              fill: true,
              borderWidth: 1,
          pointBackgroundColor: function(context) {
          var index = context.dataIndex;
          return index === 3 ? '#0c0c0c' : 'rgba(255, 99, 132, 1)';
        },    
        pointRadius: function(context) {
          var index = context.dataIndex;
          return index === 3 ? 2 : 1;
        },
        pointBorderColor: function(context) {
          var index = context.dataIndex;
          return index === 3 ? '#0c0c0c' : '#0c0c0c';
        }
          },
          {
              label: 'ЦБ РФ',
              data: <?php echo json_encode($CBprice); ?>,
              backgroundColor: 'rgba(0, 128, 0, 0.3)',
              borderColor: '#008000',
              borderWidth: 2,
              pointRadius: 0,
          }]
      },
      options: {
          plugins: {
          annotation: {
          annotations: {
          line1: {
            type: 'line',
            yMin: <?php echo $lineportf1; ?>,
            yMax: <?php echo $lineportf1; ?>,
            borderColor: '#ff0000',
            borderWidth: 1,
          }
        }
      },
        zoom: {
          zoom: {
            drag: {
              enabled: true
            },
            wheel: {
              enabled: true,
            },
            pinch: {
              enabled: true
            },
            mode: 'x',
          }
        }
      
    },
    scales: {
              y: {
                  suggestedMin: <?php echo $lineportf - 5; ?>,
                  suggestedMax: <?php echo max($P2Pprice) + 5; ?>
              }
          }
      }
  });


  // Создание 2 графика
  var myChart = new Chart(ctx1, {
      type: 'line',
      data: {
          labels: <?php echo json_encode($pricedate); ?>,
          datasets: [{
              label: 'Спред ',
              data: <?php echo json_encode($spreadRUB); ?>,
              backgroundColor: 'rgba(237, 237, 237, 0.55) ',
              borderColor: '#0c0c0c',
              fill: true,
              borderWidth: 2,
          pointBackgroundColor: function(context) {
          var index = context.dataIndex;
          return index === 3 ? '#0c0c0c' : 'rgba(237, 237, 237, 1)';
        },    
        pointRadius: 1,
        pointBorderColor: '#0c0c0c'
          }]
      },
      options: {
          plugins: {
        zoom: {
          zoom: {
            drag: {
              enabled: true
            },
            wheel: {
              enabled: true,
            },
            pinch: {
              enabled: true
            },
            mode: 'x',
          }
        }
      
    },
    scales: {
              y: {
                  suggestedMin: <?php echo 0; ?>,
                  suggestedMax: <?php echo max($spreadRUB) + 1; ?>,
                  ticks: {
                      callback: function(value, index, ticks) {
                          return value + '%';
                      }
                  }
              }
          }
      }
  });
</script>
  </div>

  @include('layouts.footer')
</body>
</html>