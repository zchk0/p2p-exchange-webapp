<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Индексы цен на P2P</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body style="padding-top: 50px;">
    
	<h1>Тест обращения к БД</h1>
	
	<?php $options = ['crypto' => [
        'USDT',
        'BTC',
        'BNB',
        'BUSD',
        'ETH',
        'DAI'
    ],
    'fiat' => [
        'ARS',
        'EUR',
        'USD',
        'AED',
        'AUD',
        'BDT',
        'BHD',
        'BOB',
        'BRL',
        'CAD',
        'CLP',
        'CNY',
        'COP',
        'CRC',
        'CZK',
        'DOP',
        'DZD',
        'EGP',
        'GBP',
        'GEL',
        'GHS',
        'HKD',
        'IDR',
        'INR',
        'JPY',
        'KES',
        'KHR',
        'KRW',
        'KWD',
        'KZT',
        'LAK',
        'LBP',
        'LKR',
        'MAD',
        'MMK',
        'MXN',
        'MYR',
        'NGN',
        'OMR',
        'PAB',
        'PEN',
        'PHP',
        'PKR',
        'PLN',
        'PYG',
        'QAR',
        'RON',
        'RUB',
        'SAR',
        'SDG',
        'SEK',
        'SGD',
        'THB',
        'TND',
        'TRY',
        'TWD',
        'UAH',
        'UGX',
        'UYU',
        'VES',
        'VND',
        'ZAR'
    ],
    'exchange' => [
        'BUY',
        'SELL'
    ]
];	?> 
	
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Binance P2P BOT</div>
			<div class="panel-body">
				<label for="crypto">* Select Crypto:</label>
				<select id="crypto" class="form-control" >
					<?php foreach ($options['crypto'] as $value) {
						echo '<option value="'.$value.'">'.$value.'</option>';
					}?> 
				</select>
				<br>
				<label for="fiat">* Select Fiat:</label>
				<select id="fiat" class="form-control" >
					<?php foreach ($options['fiat'] as $value) {
							echo '<option value="'.$value.'">'.$value.'</option>';
						}?> 
				</select>
				<br>
				<label for="exchange">* Select exchange:</label>
				<select id="exchange" class="form-control" >
					<?php foreach ($options['exchange'] as $value) {
							echo '<option value="'.$value.'">'.$value.'</option>';
						}?> 
				</select>
				<br>
			</div>
			<div class="panel-footer">
				<div class="text-center">
					<button class="btn btn-primary" id="load" data-loading-text="Loading ...">Load</button>
				</div>
			</div>
		</div>

		<textarea class="form-control" id="logs" rows="10"></textarea><br>
	</div>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			var crypto;
			var fiat;
			var exchange;
			$("#load").click(function() {
				crypto = $("#crypto").val();
				fiat = $("#fiat").val();
				exchange = $("#exchange").val();
				$(this).button('loading');
				$.get('/post-bot.php', {
					asset: crypto,
					fiat: fiat,
					tradeType: exchange
				}).done(e => {
					e.forEach(function (item) {
						$('#logs').append('⚠️ Rate: ' + item.price + ' | Min Price: ' + item.minSingleTransAmount + ' | Max Price: ' + item.dynamicMaxSingleTransAmount + ' | Trader: ' + item.nickName + '\n');
					})
					$("#load").button('reset');
				});
			});
		});
	</script>
	
	
	
	
	
	
	
	<h2>Places I'd Like to Visit</h2>
	<ul>
	  @foreach ($togo as $newplace)
		<li>{{ $newplace->name }}</li>
	  @endforeach
	</ul>

	<h2>Places I've Already Been To</h2>
	<ul>
	<?php 
	    
	    $mascurr = ["usd" => [], "eur" => [], "rub" => [], "kzt" => [], "try" => [], "uah" => []];
	    $mascurrlist = ["usd" => ["price", "cbprice"], "eur" => ["price", "cbprice"], "rub" => ["price", "cbprice"], "kzt" => ["price", "cbprice"], "try" => ["price", "cbprice"], "uah" => ["price", "cbprice"]];

	    
	    for($i = 0; $i < count($currlist); $i++){
	        $mascurr[$currlist[$i]->fiat][] = $currlist[$i]->price; 
	        $mascurrlist[$currlist[$i]->fiat]['cbprice'] = $currlist[$i]->cbankprice;
	    }
	    
	    
	        foreach($mascurr as $title => $vv){
	           $s = $i = 0;
	         
	           foreach($vv as $v){
	               $i++;
	               $s += $v;
	           }
	           $mascurrlist[$title]['price'] = $s / $i;
	        }
	    
	    echo $mascurrlist['usd']['cbprice']; 
	    ?>
	</ul>
	
	<div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
</body>
</html>
