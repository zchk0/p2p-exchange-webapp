<?php

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    require_once('/var/www/zchk-app/vendor/autoload.php');

    use GuzzleHttp\Client;
    use GuzzleHttp\Psr7;
    use GuzzleHttp\Exception\RequestException;
    use Illuminate\Http\Request;

    $db_name     = 'zchk_app';
    $db_user     = 'root';
    $db_password = 'igOgEclOKShJOJY';
    $db_host     = 'localhost';
    
    $fiatcoinslist = ['usd','eur','rub','kzt','uah','try'];
    $tradetypeslist = ['buy','sell'];
    $cblist = [
                'usd' => '1.00',
                'rub' => '0',
                'kzt' => '0',
                'eur' => '0',
                'try' => '0',
                'uah' => '0',
    ];
    
    $asset = strtolower("USDt");
    $fiat = strtolower("rub");
    $tradeType = strtolower("BUY");
    $t_method = "all-methods";
    $t_value = "0";
    
    $conn = new mysqli("localhost", $db_user, $db_password, $db_name);
    if ($conn->connect_error){die("Ошибка: " . $conn->connect_error);}
    
    $url_binance  = "https://p2p.binance.com/bapi/c2c/v2/friendly/c2c/adv/search";
    $url_okx      = "https://www.okx.com/v3/c2c/tradingOrders/books";
    $url_huobi    = "https://otc-api.trygofast.com/v1/data/trade-market";
    $url_bybit    = "https://api2.bybit.com/spot/api/otc/item/list";
    
    $coinsUSD = ['usdt','usdd','busd','dai','usdc','tusd'];
    $tradeTypeBybit = ['buy' => 1, 'sell' => 0];
    $iffiatBinance = "usdt btc eth bnb dai busd";
    $iffiatOKX = "usdt btc eth usdc tusd dai";
    $iffiatHuobi = "usdt btc eth usdd ltc trx";
    $iffiatBybit = "usdt btc eth usdc";
            
    $coinsHuobi = [
                    'usdt' => 2,
                    'btc' => 1,
                    'eth' => 3,
                    'usdd' => 5,
                    'ltc' => 8,
    ];
    $currHuobi = [
                    'rub' => 11,
                    'usd' => 2,
                    'kzt' => 0,
                    'eur' => 0,
                    'try' => 0,
    ];
    
    //$options_bybit = "?userId=&tokenId=" . strtoupper($uu) . "&currencyId=" . strtoupper($fiat) . "&payment={$banksBybitSet[$t_method]}&side={$tradeTypeBybit[$tradeType]}&size=10&page=1&amount=";
    //$options_huobi = "?coinId={$coinsHuobi[$uu]}&currency={$currHuobi[$fiat]}&tradeType={$tradeTypeOKX}&currPage=1&amount=0&payMethod={$banksHuobiSet[$t_method]}&blockType=general&online=1&range=0&onlyTradable=false";
    //$options_okx = "?quoteCurrency={$fiat}&baseCurrency={$uu}&side={$tradeTypeOKX}&paymentMethod={$t_method}&quoteMinAmountPerOrder=0&sortType={$sortTypeOKX}";
    //https://otc-api.trygofast.com/v1/data/trade-market?coinId=2&currency=11&tradeType=buy&currPage=1&amount=0&payMethod=9&blockType=general&online=1&range=0&onlyTradable=false
    
    $url_cb  = "https://www.cbr-xml-daily.ru/daily_json.js";
    $headers = [
                    'clienttype' => 'android',
                    'lang' => 'vi',
                    'versioncode' => 14004,
                    'versionname' => '1.40.4',
                    'BNC-App-Mode' => 'pro',
                    'BNC-Time-Zone' => 'Asia/Ho_Chi_Minh',
                    'BNC-App-Channel' => 'play',
                    'BNC-UUID' => '067042cf79631252f1409a9baf052e1a',
                    'referer' => 'https://p2p.binance.com/',
                    'Cache-Control' => 'no-cache, no-store',
                    'Content-Type' => 'application/json',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'okhttp/4.9.0'
    ];
    $client = new Client(['verify' => false, 'headers' => $headers]);
    $logger;
    //$e_time = date("d.m.Y");
    
    if ($_SERVER['REQUEST_URI'] == "/statt.php?tm_add_db=0") {
        try {
                $ii = 0;
                $tradeTypeOKX = ($tradeType=="buy") ? "sell" : "buy";
                $sortTypeOKX = ($tradeType=="buy") ? "price_asc" : "price_desc";
                //$t_method = ($t_method=="all-methods") ? "" : $t_method;
                $coinsUSD = ['usdt','usdd','busd','dai','usdc','tusd'];
                $tradeTypeBybit = ['buy' => 1, 'sell' => 0];
                $iffiatBinance = "usdt btc eth bnb dai busd";
                
                $response_cb = $client->request('GET', $url_cb);
                $data_cb = json_decode($response_cb->getBody());
                
                $cblist['rub'] = $data_cb->Valute->USD->Value;
                
                //echo $data_cb->Valute->USD->Value;
                $result = [];
                
                foreach($fiatcoinslist as $uu) {
                    
                    if ($uu=="rub") continue;

                    $qqq = ($uu=="rub") ? ["TinkoffNew"] : [];
                    $options = ['json' => [
                        'asset' => 'usdt',
                        'tradeType' => $tradeType,
                        'fiat' => $uu,
                        'transAmount' => 0,
                        'order' => '',
                        'page' => 1,
                        'rows' => 10,
                        'payTypes' => $qqq,
                        ]
                    ];
                    $response_binance = $client->request('POST', $url_binance, $options);
                    $data_binance = json_decode($response_binance->getBody());
                    
                    if($uu!="rub" && $uu!="usd"){$cblist[$uu] = $data_binance->data[0]->adv->price;}
                    
                    $sqlBINANCE = "INSERT INTO zchk_app.binance (asset, fiat, price, cbankprice, date, time, tradetype, tmethod) VALUES ('usdt', '" . $uu . "', '" . $data_binance->data[0]->adv->price . "', '" . $cblist[$uu] . "', '" . date("d.m.Y") . "', '" . date("H:i") . "', '" . $tradeType . "', '".(($uu=='rub') ? 'Tinkoff' : '-')."')";
                    if ($conn->query($sqlBINANCE)) { 
                        //echo "успешно добавлены";
                    } 
                    else { echo "Ошибка: " . $conn->error;}
                
                    echo $data_binance->data[0]->adv->price . " ";
                    //if($asset != "all-usd") {break;}
                }
                
                
                $optionsBybit = ['json' => [
                        "userId" => "",
                        "tokenId" => "USDT",
                        "currencyId" => "RUB",
                        "payment" => ["581"],
                        "side" => "0",
                        "size" => "5",
                        "page" => "1",
                        "amount" => "",
                        "authMaker" => false,
                        "canTrade" => false
                        ]
                    ];
                $response_bybit = $client->request('POST', "https://api2.bybit.com/fiat/otc/item/online", $optionsBybit);
                $data_bybit = json_decode($response_bybit->getBody());
            
                // перестал раюотать get запрос
                //$response_bybit =  $client->request('GET', "https://api2.bybit.com/spot/api/otc/item/list/?userId=&tokenId=USDT&currencyId=RUB&payment=581&side=1&size=10&page=1&amount=");
                //$data_bybit = json_decode($response_bybit->getBody());
                
                $priceBybit = ($data_bybit->result->items[1]->price + $data_bybit->result->items[2]->price + $data_bybit->result->items[3]->price
                + $data_bybit->result->items[4]->price) / 4;
                
                $sqlBYBIT = "INSERT INTO zchk_app.binance (asset, fiat, price, cbankprice, date, time, tradetype, tmethod) VALUES ('usdt', 'rub', '" . $priceBybit . "', '" . $cblist['rub'] . "', '" . date("d.m.Y") . "', '" . date("H:i") . "', 'buy', 'Tinkoff (bybit)')";
                if ($conn->query($sqlBYBIT)) { 
                    echo "(rub bybit): " . $priceBybit;
                } 
                else { echo "Ошибка: " . $conn->error;}
        } catch (RequestException $e) {
            $response = json_decode($e->getResponse()->getBody());
            $logger->error($e->getResponse()->getBody());
            return (object)  array(
                'status' => 'error',
                'message' => $response->error->message
            );
        }
        $conn->close();
    }
    else {
        echo date("d.m.Y") . "//";
        echo date("d.m.Y", strtotime("yesterday"));
        
        echo "фыр фыр фыр";
    }
    

    
    
    


    

