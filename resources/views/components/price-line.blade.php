<?php
    $mascurr = ["usd" => [], "eur" => [], "rub" => [], "kzt" => [], "try" => [], "uah" => []];
    $mclist = [
        "usd" => ["price", "cbprice"], 
        "eur" => ["price", "cbprice"], 
        "rub" => ["price", "cbprice"], 
        "kzt" => ["price", "cbprice"], 
        "try" => ["price", "cbprice"], 
        "uah" => ["price", "cbprice"]
    ];

    if (!empty($currlist)) {
        for($i = 0; $i < count($currlist); $i++) {
            $mascurr[$currlist[$i]->fiat][] = $currlist[$i]->price; 
            $mclist[$currlist[$i]->fiat]['cbprice'] = $currlist[$i]->cbankprice;
        }
        foreach($mascurr as $title => $vv) {
            $s = $i = 0;
            foreach($vv as $v){$i++;$s += floatval($v);}
            $mclist[$title]['price'] = empty($i) ? 0 : round($s / $i, 3);;
        }
    }
    else {
        for($i = 0; $i < count($currlistold); $i++) {
            $mascurr[$currlistold[$i]->fiat][] = $currlistold[$i]->price; 
            $mclist[$currlistold[$i]->fiat]['cbprice'] = $currlistold[$i]->cbankprice;
        }
        foreach($mascurr as $title => $vv) {
            $s = $i = 0;
            foreach($vv as $v){$i++;$s += floatval($v);}
            $mclist[$title]['price'] = empty($i) ? 0 : round($s / $i, 3);
        }
    }	    
?>
<div class="top-line2" id="top-line2">
  <div class="_inner">
    <div>
      <div class="_t">USDT:</div>
      <div class="_v" title="Средняя цена за 1 USDT"><?php echo $mclist['usd']['price']; ?> <i>USD</i>
      </div>
      <div class="_v" title="Средняя цена за 1 USDT"><?php echo $mclist['eur']['price']; ?> <i>EUR</i>
      </div>
      <div class="_v" title="Средняя цена метода TINKOFF за 1 USDT"><span
          class="als-usd"><?php echo $mclist['rub']['price']; ?></span> <i>RUB</i></div>
      <div class="_v" title="Средняя цена за 1 USDT"><?php echo $mclist['uah']['price']; ?> <i>UAH</i>
      </div>
      <div class="_v" title="Средняя цена метода Kaspi Bank за 1 USDT">
        <?php echo $mclist['kzt']['price']; ?> <i>KZT</i></div>
      <div class="_v" title="Средняя цена метода Ziraat за 1 USDT"><?php echo $mclist['try']['price']; ?>
        <i>TRY</i>
      </div>
    </div>
    <div>
      <div class="_t">USD (ЦБ <?php echo date("d.m.Y") ?>):</div>
      <div class="_v" title="Курс ЦБ"><?php echo $mclist['eur']['cbprice'] ?? 0; ?> <i>EUR</i></div>
      <div class="_v" title="Курс ЦБ РФ"><?php echo $mclist['rub']['cbprice'] ?? 0; ?> <i>RUB</i></div>
      <div class="_v" title="Курс ЦБ"><?php echo $mclist['uah']['cbprice'] ?? 0; ?> <i>UAH</i></div>
      <div class="_v" title="Курс ЦБ РК"><?php echo $mclist['kzt']['cbprice'] ?? 0; ?> <i>KZT</i></div>
      <div class="_v" title="Курс ЦБ"><?php echo $mclist['try']['cbprice'] ?? 0; ?> <i>TRY</i></div>
    </div>
  </div>
</div>