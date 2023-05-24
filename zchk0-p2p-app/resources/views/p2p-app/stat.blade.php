<?php

header('Access-Control-Allow-Origin: *');

 $mascurr = ["usd" => [], "eur" => [], "rub" => [], "kzt" => [], "try" => [], "uah" => []];
 $mclist = ["usd" => ["price", "cbprice"], "eur" => ["price", "cbprice"], "rub" => ["price", "cbprice"], "kzt" => ["price", "cbprice"], "try" => ["price", "cbprice"], "uah" => ["price", "cbprice"]];
 
 //dd($currusdt);
 
 if(!empty($currlist)){
    for($i = 0; $i < count($currlist); $i++){
        $mascurr[$currlist[$i]->fiat][] = $currlist[$i]->price; 
        $mclist[$currlist[$i]->fiat]['cbprice'] = $currlist[$i]->cbankprice;
    }
     foreach($mascurr as $title => $vv){
        $s = $i = 0;
        foreach($vv as $v){$i++;$s += $v;}
	    $mclist[$title]['price'] = round($s / $i, 3);
    }
 }
 else{
     for($i = 0; $i < count($currlistold); $i++){
        $mascurr[$currlistold[$i]->fiat][] = $currlistold[$i]->price; 
        $mclist[$currlistold[$i]->fiat]['cbprice'] = $currlistold[$i]->cbankprice;
    }
     foreach($mascurr as $title => $vv){
        $s = $i = 0;
        foreach($vv as $v){$i++;$s += $v;}
	    $mclist[$title]['price'] = round($s / $i, 3);
    }
 }
 
 $dataUSDT = array();
 $CBprice = array();
 $P2Pprice = array();
 $pricedate = array();
 $spreadRUB = array();
 
 foreach($currusdt as $t){
        $dataUSDT[$t->date]['p2p'][] = $t->price;
        $dataUSDT[$t->date]['cb'][] = $t->cbankprice;
    }
 foreach($dataUSDT as $t => $vv){
        $pricedate[] = $t; //записывам дату в массив
        $CBprice[] = $vv['cb'][0]; //записывам цену ЦБ
        
        $s = $i = 0;
        foreach($vv['p2p'] as $v){$i++;$s += $v;}
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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2.2.1/dist/chartjs-plugin-annotation.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>
	
</head>
<body style="background-attachment: fixed!important;background: linear-gradient(0deg,rgb(27 56 44 / 13%),rgb(242 242 255));">
    <style>
    .container {
    padding-right: 6px;
    padding-left: 6px;}
    
.top-0{top:0!important}.top-50{top:50%!important}.top-100{top:100%!important}.top-line{background:#fff;border-bottom:1px solid #ccc;overflow:hidden}.top-line2{background:#fff;border-bottom:1px solid #ccc;cursor:default;opacity:.7;overflow-x: scroll;transition:all .2s}.top-line2 ._inner{align-items:center;display:flex;padding:15px 15px}.top-line2 ._inner>div{align-items:center;display:flex;font-size:13px;margin-right:30px;white-space:nowrap}.top-line2 ._inner>div ._t{color:#58667e;font-weight:600;margin-right:15px}.top-line2 ._inner>div ._v{background:#f9f9f9;color:#58667e;margin:0 15px 0 0;padding:0 11px}.top-line2 ._inner>div ._v i{color:#384252;font-size:10px;font-style:normal}.top-line2 ._inner>div:last-child{margin-right:0}.top-line2:hover{opacity:1}.top-line2:hover ._inner>div ._t{color:#0a2882}
    
    @media (max-width:768px){nav .logo{font-size:19px!important;} .table_sort_div{padding: 16px 10px 20px!important;}}
        .container0{max-width: 1150px; align-items: center; display: flex; justify-content: space-between; padding: 0 15px; 
        margin-left: auto; margin-right: auto; padding-left: var(--bs-gutter-x,.75rem); padding-right: var(--bs-gutter-x,.8rem); width: 100%;}
        .nav{display:flex;flex-wrap:wrap;list-style:none;margin-bottom:0;padding-left:0}
        nav{background:#fff;border-bottom:1px solid #ccc}
        .container0>div{font-size:14px}
        
        nav .logo{color:#000;display:inline-block;font-size:25px;font-weight:700;line-height:30px;padding:10px 5px;text-decoration:none}
        .top-buttons{display:flex}.top-buttons>a{background:#536aad;border-radius:10px;color:#fff;margin-left:10px;padding:5px 20px;text-decoration:none}
        .login-req-btn:hover, .btn-primary:hover {color: #fff;background-color: #286090!important;border-color: #204d74;}
        .btn-primary {background-color: #536aad;margin-right: 5px;}
    </style>
    
    <div style="min-height: calc(100vh - 180px);padding: 0 0 50px;">
        
    <div class="top-line2" id="top-line2"><div class="_inner"><div><div class="_t">USDT:</div><div class="_v" title="Средняя цена за 1 USDT"><?php echo $mclist['usd']['price']; ?> <i>USD</i></div><div class="_v" title="Средняя цена за 1 USDT"><?php echo $mclist['eur']['price']; ?> <i>EUR</i></div><div class="_v" title="Средняя цена метода TINKOFF за 1 USDT"><?php echo $mclist['rub']['price']; ?> <i>RUB</i></div><div class="_v" title="Средняя цена за 1 USDT"><?php echo $mclist['uah']['price']; ?> <i>UAH</i></div><div class="_v" title="Средняя цена метода Kaspi Bank за 1 USDT"><?php echo $mclist['kzt']['price']; ?> <i>KZT</i></div><div class="_v" title="Средняя цена метода Ziraat за 1 USDT"><?php echo $mclist['try']['price']; ?> <i>TRY</i>
    </div></div>
    <div><div class="_t">USD (ЦБ <?php echo date("d.m.Y") ?>):</div><div class="_v" title="Курс ЦБ"><?php echo $mclist['eur']['cbprice']; ?> <i>EUR</i></div><div class="_v" title="Курс ЦБ РФ"><?php echo $mclist['rub']['cbprice']; ?> <i>RUB</i></div><div class="_v" title="Курс ЦБ"><?php echo $mclist['uah']['cbprice']; ?> <i>UAH</i></div><div class="_v" title="Курс ЦБ РК"><?php echo $mclist['kzt']['cbprice']; ?> <i>KZT</i></div><div class="_v" title="Курс ЦБ"><?php echo $mclist['try']['cbprice']; ?> <i>TRY</i></div>
    </div>
    </div></div>
    
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
    
	<div class="container" style="padding-top: 55px;">
		<div class="panel panel-primary" style="border-color: #b9b9b9;">
			<div class="panel-heading" style="padding: 15px 20px;background-color: #5a5a5a;border-color: #6b6c6c;">Статитика отслеживаемых курсов ЦБ и бирж P2P</div>
			<div class="panel-body">
		
		    <canvas id="myChart"></canvas>
        @if (Auth::check())<?php if (!empty($portfoliouser)) { ?> 
        <div class="table_sort_div" style=" margin-top: 15px;line-height: 1.7;padding: 16px 20px 20px;">
        <table class="table_sort" style="background: whitesmoke;">
        <tbody id="table-logs">
        <tr><td style="width: 400px;">Ваша средняя цена покупки <br>(на графике горизонтальная красная черта)</td><td style="width: 145px;"> <?php echo round($old_port / $value_port, 2) . ' $'; ?> </td></tr>
        <tr><td style="width: 400px;">Размер портфеля:</td><td style="width: 145px;"> <?php echo round($value_port * $mclist['rub']['price'], 2) . ' руб. ('. $value_port .' $)'; ?> </td></tr>
        <tr><td style="width: 400px;">Итоговый(-ая) прибыль/убыток:</td><td style="width: 145px;"> <?php echo round($value_port * $mclist['rub']['price'] - $old_port, 2); ?> руб. 
        <br><?php $color_port = ($current_port - $old_port > 0) ? 'green' : 'red'; echo '(<b style="color:'. $color_port .'">' . round(($current_port / $old_port - 1) * 100, 2) . ' %</b>)'; ?> </td></tr>
        </tbody>
        </table>
        <style>.table_sort table {border-collapse: collapse;}.table_sort td, .table_sort th {height: 50px;text-align: center;border: 1px solid #846868;padding: 0 2px;}</style>
        </div>
        <?php } ?>@endif
			</div>
			<div class="panel-footer" style="font-style: italic;">На графике выводятся два вида курсов USD/RUB — официальный от Центрального Банка России и фактический на биржевых P2P обменниках. <br>Наблюдение ведется (т.е. собираются статистические данные) с 13.02.2023. 
			</div>
		</div>
		
	</div>
	
		<div class="container" style="padding-top: 25px;">
		<div class="panel panel-primary" style="border-color: #b9b9b9;">
			<div class="panel-heading" style="padding: 15px 20px;background-color: #5a5a5a;border-color: #6b6c6c;">Разница курсов (спред) пары USD/RUB</div>
			<div class="panel-body">
		
		    <canvas id="myChart1"></canvas>

			</div>
			<div class="panel-footer" style="font-style: italic;">На графике выводится разница в % между официальным курсом от Центрального Банка России и фактическим на биржевых P2P обменниках.
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
            borderWidth: 2,
        pointBackgroundColor: function(context) {
        var index = context.dataIndex;
        return index === 3 ? '#0c0c0c' : 'rgba(255, 99, 132, 1)';
      },    
      pointRadius: function(context) {
        var index = context.dataIndex;
        return index === 3 ? 3 : 1;
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
            borderWidth: 1,
            pointRadius: 1,
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

	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	

</div>

<div class="footer">
    <style>a{color:#0d6efd;text-decoration:underline}a:hover{color:#0a58ca}a:not([href]):not([class]),a:not([href]):not([class]):hover{color:inherit;text-decoration:none}[role=button]{cursor:pointer}.footer{background: #ededed;height: 180px;padding: 40px 30px 0;text-align:center}.footer .mini-links{display:flex;flex-wrap:wrap;justify-content:center}.footer .mini-links a{color:#777;font-size:15px;margin:5px 20px;text-decoration:none}.footer .mini-links a:hover{text-decoration:underline}</style>
    <div class="mini-links">
    <a href="/">Главная</a>
    <a href="/faq">FAQ</a>
    <a href="/stat">Статистика</a>
    </div>
    <div style="font-style: italic; color: #c6c9c9; padding-top: 11px;">Александр Зайченко | Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</div>
    </div>
    
</body>
</html>