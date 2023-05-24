<?php

require_once('/var/www/zchk-app/resources/views/p2p-app/options.php');
header('Access-Control-Allow-Origin: *');

 $mascurr = ["usd" => [], "eur" => [], "rub" => [], "kzt" => [], "try" => [], "uah" => []];
 $mclist = ["usd" => ["price", "cbprice"], "eur" => ["price", "cbprice"], "rub" => ["price", "cbprice"], "kzt" => ["price", "cbprice"], "try" => ["price", "cbprice"], "uah" => ["price", "cbprice"]];
 
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
	    
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Агрегатор наилучших курсов P2P | p2p.zchk.ru</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body style="background-attachment: fixed!important;background: linear-gradient(0deg,rgb(27 56 44 / 13%),rgb(242 242 255));">
    <style>
.top-0{top:0!important}.top-50{top:50%!important}.top-100{top:100%!important}.top-line{background:#fff;border-bottom:1px solid #ccc;overflow:hidden}.top-line2{background:#fff;border-bottom:1px solid #ccc;cursor:default;opacity:.7;overflow-x: scroll;transition:all .2s}.top-line2 ._inner{align-items:center;display:flex;padding:15px 15px}.top-line2 ._inner>div{align-items:center;display:flex;font-size:13px;margin-right:30px;white-space:nowrap}.top-line2 ._inner>div ._t{color:#58667e;font-weight:600;margin-right:15px}.top-line2 ._inner>div ._v{background:#f9f9f9;color:#58667e;margin:0 15px 0 0;padding:0 11px}.top-line2 ._inner>div ._v i{color:#384252;font-size:10px;font-style:normal}.top-line2 ._inner>div:last-child{margin-right:0}.top-line2:hover{opacity:1}.top-line2:hover ._inner>div ._t{color:#0a2882}
    
    @media (max-width:768px){nav .logo{font-size:19px!important;}}
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
        
    <div class="top-line2" id="top-line2"><div class="_inner"><div><div class="_t">USDT:</div><div class="_v" title="Средняя цена за 1 USDT"><?php echo $mclist['usd']['price']; ?> <i>USD</i></div><div class="_v" title="Средняя цена за 1 USDT"><?php echo $mclist['eur']['price']; ?> <i>EUR</i></div><div class="_v" title="Средняя цена метода TINKOFF за 1 USDT"><span class="als-usd"><?php echo $mclist['rub']['price']; ?></span> <i>RUB</i></div><div class="_v" title="Средняя цена за 1 USDT"><?php echo $mclist['uah']['price']; ?> <i>UAH</i></div><div class="_v" title="Средняя цена метода Kaspi Bank за 1 USDT"><?php echo $mclist['kzt']['price']; ?> <i>KZT</i></div><div class="_v" title="Средняя цена метода Ziraat за 1 USDT"><?php echo $mclist['try']['price']; ?> <i>TRY</i>
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
			<div class="panel-heading" style="padding: 15px 20px;background-color: #5a5a5a;border-color: #6b6c6c;">Binance / OKX / Huobi / Bybit P2P</div>
			<div class="panel-body">
				<label for="crypto">* Выберите Актив:</label>
				<select id="crypto" class="form-control" >
					<?php foreach ($options['crypto'] as $value) {
						echo '<option value="'.$value.'">'.$value.'</option>';
					}?> 
				</select>
				<br>
				<label for="fiat">* Выберите фиатную валюту:</label>
				<select id="fiat" class="form-control" >
					<?php foreach ($options['fiat'] as $value) {
							echo '<option value="'.$value.'">'.$value.'</option>';
						}?> 
				</select>
				<br>
				<label for="exchange">* Выберите тип обмена:</label>
				<select id="exchange" class="form-control" >
					<?php foreach ($options['exchange'] as $key => $v) {
							echo '<option value="'.$v.'">'.$key.'</option>';
						}?> 
				</select>
				<br>
				<label for="payment">* Выберите метод оплаты:</label>
				<select id="payment" class="form-control" >
				</select>
				<br>
				<label for="_value">Введите искомое количество (необязательно):</label>
                    <input type="number" id="_value" name="value" class="form-control">
				<br>
			</div>
			<div class="panel-footer">
				<div class="text-center">
					<button class="btn btn-primary" id="load" data-loading-text="Загружаем...">Загрузить</button> <button class="btn btn-primary" id="load" data-loading-text="Загружаем...">Лучший спред</button>
				</div>
			</div>
		</div>

 <script>
var firstList = document.getElementById("fiat");
var secondList = document.getElementById("payment");
var options = {
  'USD': ['Все методы','Advcash','ALIPAY','AltynBank','Банковский перевод','Cashapp','Forte Bank','GoMoney','GoPay','GPay','HalykBank','JysanBank','KaspiBank','Monobank','Payeer','Payme','Paysend','Paysera','Paytm','PerfectMoney','PiPay','QIWI','RaiffeisenBank Aval','Revolut','SEPA','SWIFT','Ukrsibbank','UNIBANK','UniCreditEU','WECHAT','WesternUnion','Wise'],
  'USDv': ['all-methods','Advcash','ALIPAY','AltynBank','BANK','Cashapp','ForteBank','GoMoney','GoPay','GPay','HalykBank','JysanBank','KaspiBank','Monobank','Payeer','Payme','Paysend','Paysera','Paytm','PerfectMoney','PiPay','QIWI','RaiffeisenBankAval','Revolut','SEPA','SWIFT','Ukrsibbank','UNIBANK','UniCreditEU','WECHAT','WesternUnion','Wise'],
  'EUR': ['Все методы','Advcash','ALIPAY','AltynBank','ApplePay','BANK','Cashapp','ForteBank','GPay','HalykBank','JysanBank','KaspiBank','Monobank','OTPBank','Payeer','Payme','Paysend','Paysera','Paytm','PerfectMoney','RaiffeisenBankAval','Revolut','SEPA','SWIFT','Ukrsibbank','UNIBANK','UniCreditEU','WECHAT','WesternUnion','Wise'],
  'EURv': ['all-methods','Advcash','ALIPAY','AltynBank','ApplePay','BANK','Cashapp','ForteBank','GPay','HalykBank','JysanBank','KaspiBank','Monobank','OTPBank','Payeer','Payme','Paysend','Paysera','Paytm','PerfectMoney','RaiffeisenBankAval','Revolut','SEPA','SWIFT','Ukrsibbank','UNIBANK','UniCreditEU','WECHAT','WesternUnion','Wise'],
  'CNY': ['Все методы','ALIPAY','BANK','WECHAT'],
  'CNYv': ['all-methods','ALIPAY','BANK','WECHAT'],
  'GBP': ['Все методы','Advcash','ALIPAY','BANK','Cashapp','Gcash','Monobank','Payeer','Paysera','Revolut','SEPA','SWIFT','WECHAT','WesternUnion','Wise'],
  'GBPv': ['all-methods','Advcash','ALIPAY','BANK','Cashapp','Gcash','Monobank','Payeer','Paysera','Revolut','SEPA','SWIFT','WECHAT','WesternUnion','Wise'],
  'KZT': ['Все методы','Advcash','AltynBank','Банковский перевод','ForteBank','HalykBank','JysanBank','KaspiBank','KoronaPay','QIWI'],
  'KZTv': ['all-methods','Advcash','AltynBank','BANK','ForteBank','HalykBank','JysanBank','KaspiBank','KoronaPay','QIWI'],
  'RUB': ['Все методы','Advcash','Банковский перевод','HomeCreditBank','ОТП Банк','Payeer','QIWI','Райффайзенбанк','Росбанк','Тинькофф Банк','ЮMoney','Юникредит банк','СБП (Система быстрых платежей)','МТС-Банк'],
  'RUBv': ['all-methods','Advcash','BANK','HomeCreditBank','OTPBankRussia','Payeer','QIWI','RaiffeisenBank','RosBank','Tinkoff','YandexMoneyNew','UniCreditRussia','SBP','MTSBank'],
  'TRY': ['Все методы','Advcash','BANK','Revolut','Wise'],
  'TRYv': ['all-methods','Advcash','BANK','Revolut','Wise'],
  'UAH': ['Все методы','Advcash','BANK','Monobank','OTPBank','Paysend','SettlePay','RaiffeisenBankAval','Revolut','SEPA','Ukrsibbank','Wise'],
  'UAHv': ['all-methods','Advcash','BANK','Monobank','OTPBank','Paysend','SettlePay','RaiffeisenBankAval','Revolut','SEPA','Ukrsibbank','Wise']
};

// Функция, которая будет вызываться при изменении первого списка
function updateSecondList() {
  var selectedValue = firstList.value;
  secondList.innerHTML = "";
  
  for (var i = 0; i < options[selectedValue].length; i++) {
    var option = document.createElement("option");
    option.text = options[selectedValue][i];
    option.value = options[selectedValue+"v"][i];
    secondList.add(option);
  }
}
// Вызываем функцию при загрузке страницы и при изменении первого списка
updateSecondList();
firstList.addEventListener("change", updateSecondList);
    </script>

		<br>
		
	<table class="table_sort">
    <thead>
        <tr>
            <th>ТИП</th>
            <th>ЦЕНА</th>
            <th>MIN-MAX</th>
            <th>ИНФО</th>
        </tr>
    </thead>
    <tbody id="table-logs"></tbody>
    </table>
		
	</div>
	<script src="/jquery-3.6.4.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			var crypto;
			var fiat;
			var exchange;
			var _method = "";
			var _value = "0";
			$("#load").click(function() {
			    document.getElementById("table-logs").innerHTML = '';
				crypto = $("#crypto").val();
				fiat = $("#fiat").val();
				exchange = $("#exchange").val();
				_method = $("#payment").val();
				_value = $("#_value").val();
				$(this).button('loading');
				$.get('/api/p2p', {
					asset: crypto,
					fiat: fiat,
					tradeType: exchange,
					tmethod: _method,
					tvalue: _value
				}).done(e => {
					e.forEach(function (item) {
						$('#table-logs').append('<tr><td>' + item.typecoin + '</td><td>' + Number(item.price).toFixed(2) + '</td><td>' + item.minSingleTransAmount + ' - ' + item.dynamicMaxSingleTransAmount + '</td><td>' + item.nickName + ' ('+ item.exchange +')<hr>' + item.identifier + '</td></tr>');
					})
					$("#load").button('reset');
				});
			});
		});
	</script>
	
	<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', () => {
    const getSort = ({ target }) => {
        const order = (target.dataset.order = -(target.dataset.order || -1));
        const index = [...target.parentNode.cells].indexOf(target);
        const collator = new Intl.Collator(['en', 'ru'], { numeric: true });
        const comparator = (index, order) => (a, b) => order * collator.compare(
            a.children[index].innerHTML,
            b.children[index].innerHTML
        );
        for(const tBody of target.closest('table').tBodies)
            tBody.append(...[...tBody.rows].sort(comparator(index, order)));
        for(const cell of target.parentNode.cells)
            cell.classList.toggle('sorted', cell === target);
    };
    document.querySelectorAll('.table_sort thead').forEach(tableTH => tableTH.addEventListener('click', () => getSort(event)));
});
	</script>
	
	<style>
hr {
    border-top: 1px solid #9b9898;margin: 5px 0;
}
.table_sort table {border-collapse: collapse;}
.table_sort th {
    color: #000000;
    background: #ffffffad;
    cursor: pointer;
}
.table_sort td,
.table_sort th {
    width: 150px;
    height: 40px;
    text-align: center;
    border: 2px solid #846868;
}
.table_sort tbody tr:nth-child(even) {
    background: #e3e3e3;
}
th.sorted[data-order="1"],
th.sorted[data-order="-1"] {
    position: relative;
}
th.sorted[data-order="1"]::after,
th.sorted[data-order="-1"]::after {
    right: 8px;
    position: absolute;
}
th.sorted[data-order="-1"]::after {content: "▼"}
th.sorted[data-order="1"]::after {content: "▲"}
</style>

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