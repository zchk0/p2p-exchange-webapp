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
        foreach($vv as $v){$i++;$s += floatval($v);}
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
        foreach($vv as $v){$i++;$s += floatval($v);}
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
        foreach($vv['p2p'] as $v){$i++;$s += floatval($v);}
	    $p2pprr = round($s / $i, 3);
	    
	    $P2Pprice[] = $p2pprr;
	    $spreadRUB[] = abs(($vv['cb'][0] - $p2pprr) / (($vv['cb'][0] + $p2pprr) / 2))*100;
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
    padding-right: 8px;
    padding-left: 8px;}
    
.top-0{top:0!important}.top-50{top:50%!important}.top-100{top:100%!important}.top-line{background:#fff;border-bottom:1px solid #ccc;overflow:hidden}.top-line2{background:#fff;border-bottom:1px solid #ccc;cursor:default;opacity:.7;overflow-x: scroll;transition:all .2s}.top-line2 ._inner{align-items:center;display:flex;padding:15px 15px}.top-line2 ._inner>div{align-items:center;display:flex;font-size:13px;margin-right:30px;white-space:nowrap}.top-line2 ._inner>div ._t{color:#58667e;font-weight:600;margin-right:15px}.top-line2 ._inner>div ._v{background:#f9f9f9;color:#58667e;margin:0 15px 0 0;padding:0 11px}.top-line2 ._inner>div ._v i{color:#384252;font-size:10px;font-style:normal}.top-line2 ._inner>div:last-child{margin-right:0}.top-line2:hover{opacity:1}.top-line2:hover ._inner>div ._t{color:#0a2882}
    
    @media (max-width:768px){nav .logo{font-size:19px!important;} .table_sort_div{padding: 16px 10px 20px!important;}
    .section_padding_130 {padding-top: 55px!important;padding-bottom: 60px!important;}.col-12 {-ms-flex: 0 0 98%;flex: 0 0 98%;max-width: 98%;}}
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
    
	
<div class="faq_area section_padding_130" id="faq">
    <div class="container">
        <div class="justify-content-center">
            <div class="col-12 col-sm-8 col-lg-6">
                <!-- Section Heading-->
                <div class="section_heading text-center wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                    <h3><span>Ответы на часто задаваемые вопросы </span></h3>
                    <p>В этом разделе собраны ответы на часто задаваемые вопросы по приложению.</p>
                    <div class="line"></div>
                </div>
            </div>
        </div>
        <div class="justify-content-center">
            <!-- FAQ Area-->
            <div class="col-12 col-sm-10 col-lg-8">
                <div class="accordion faq-accordian" id="faqAccordion">
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                        <div class="card-header" id="headingOne">
                            <h6 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">На чем построенно данное веб-приложение?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="collapseOne" aria-labelledby="headingOne" data-parent="#faqAccordion">
                            <div class="card-body">
                                <p>Данное веб-приложение построеннно на стеке LEMP (Linux+NGINX+MySQL+PHP) и PHP-фреймворке Laravel.</p>
                                <p>Для визуального оформления использовался в том числе CSS-фреймворк Bootstrap.</p>
                                <p>Данные о заявках из P2P обменников подтягиваются посредство открытых API бирж.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;">
                        <div class="card-header" id="headingTwo">
                            <h6 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">Что дает наличие аккаунта в приложении?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="collapseTwo" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                            <div class="card-body">
                                <p>Наличие аккаунта позволяет сохранять свои позиции (покупки) в паре USD/RUB.</p>
                                <p>Их можно отследить на графике на странице — <a href="/stat">Статистика</a>, визуально отобразив средний вход и покупки начиная с даты отслеживания курсов.</p>
                                <p>В дальнейшем для зарегистрированных пользователей появится функция поиска лучшего спреда между биржами (в разработке).</p>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.4s" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;">
                        <div class="card-header" id="headingThree">
                            <h6 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">Какие статистические данные собираются о курсах?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="collapseThree" aria-labelledby="headingThree" data-parent="#faqAccordion">
                            <div class="card-body">
                                <p>Веб-приложение собирает данные о курсах доллара с P2P обменников и Центрального Банка.</p>
                                <p>Сравнение данные можно отследить на графике на странице — <a href="/stat">Статистика</a>. Также на графике выводится разница в % между официальным курсом от Центрального Банка России и фактическим на биржевых P2P обменниках.</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

	

	<style>
	.h6, h6 {font-size: 15px;}
.section_padding_130 {
    padding-top: 90px;
    padding-bottom: 90px;
}
.faq_area {
    position: relative;
    z-index: 1;
    
}


.justify-content-center {
    -ms-flex-pack: center!important;
    justify-content: center!important;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
}
.faq-accordian {
    position: relative;
    z-index: 1;
}
.faq-accordian .card {
    position: relative;
    z-index: 1;
    margin-bottom: 1.5rem;
}
.faq-accordian .card:last-child {
    margin-bottom: 0;
}
.faq-accordian .card .card-header {
    background-color: #ffffff;
    padding: 0;
    border-bottom-color: #ebebeb;
}
.faq-accordian .card .card-header h6 {
    cursor: pointer;
    padding: 1.75rem 2rem;
    color: #3f43fd;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -ms-grid-row-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
}
.faq-accordian .card .card-header h6 span {
    font-size: 1.5rem;
}
.faq-accordian .card .card-header h6.collapsed {
    color: #070a57;
}
.faq-accordian .card .card-header h6.collapsed span {
    -webkit-transform: rotate(-180deg);
    transform: rotate(-180deg);
}
.faq-accordian .card .card-body {
    padding: 1.75rem 2rem;
}
.faq-accordian .card .card-body p:last-child {
    margin-bottom: 0;
}

@media only screen and (max-width: 575px) {
    .support-button p {
        font-size: 14px;
    }
}

.support-button i {
    color: #3f43fd;
    font-size: 1.25rem;
}
@media only screen and (max-width: 575px) {
    .support-button i {
        font-size: 1rem;
    }
}


@media only screen and (max-width: 575px) {
    .support-button a {
        font-size: 13px;
    }
}</style>

	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

	

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