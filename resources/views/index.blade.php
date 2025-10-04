<?php
/**
*  Copyright (C) 1412dev, Inc - All Rights Reserved
*  @author      1412dev <me@1412.io>
*  @site        https://1412.dev
*  @date        5/22/21, 5:120 AM
*  Please don't edit, respect me, if you want to be appreciated.
*/

header("Content-Type: application/json");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once('/var/www/zchk-app/vendor/autoload.php');
//include '/Binance.php';
//use API\BinanceApi;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
//use Monolog\Logger;
//use Monolog\Handler\StreamHandler;

class Binance
{
    protected $url_binance  = "https://p2p.binance.com/bapi/c2c/v2/friendly/c2c/adv/search";
    protected $url_okx      = "https://www.okx.com/v3/c2c/tradingOrders/books";
    protected $url_huobi    = "https://otc-api.trygofast.com/v1/data/trade-market";
    protected $url_bybit    = "https://api2.bybit.com/spot/api/otc/item/list";
    protected $url_bitpapa  = "https://bitpapa.com/api/v1/pro/search";
    protected $headers      =  [
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
    protected $client;
    protected $logger;


    public function __construct() {
        $this->client = new Client(['verify' => false, 'headers' => $this->headers]);
        //create log
        //$this->logger = new Logger('[Binanace_P2P]');
        //$this->logger->pushHandler(new StreamHandler(__DIR__ . '/logs.log', Logger::INFO));
    }
    
    public function exchange($asset, $fiat = "usd", $tradeType = "buy", $t_method = "all-methods", $t_value = "")
    {
        try {
            $ii = 0;
            $tradeTypeOKX = ($tradeType=="buy") ? "sell" : "buy";
            $sortTypeOKX = ($tradeType=="buy") ? "price_asc" : "price_desc";
            //$t_method = ($t_method=="all-methods") ? "" : $t_method;
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
            $banksBybitGet = [
                    '75' => 'Tinkoff',
                    '185' => 'RosBank',
                    '64' => 'RaiffeisenBank',
                    '62' => 'QIWI',
                    '88' => 'YandexMoney',
                    '44' => 'MTSBank',
                    '51' => 'Payeer',
                    '5' => 'Advcash',
                    '212' => 'KapitalBank',
                    '197' => '---',
                    '198' => '---',
                    '199' => '---',
            ];
            $banksHuobiSet = [
                    'all-methods' => '0',
                    'Tinkoff' => '28',
                    'RosBank' => '29',
                    'RaiffeisenBank' => '356',
                    'QIWI' => '9',
                    'YandexMoney' => '19',
                    'MTSBank' => '24',
                    'Payeer' => '36',
                    'Advcash' => '20',
                    'KapitalBank' => '0',
                    'ALIPAY' => '0',
                    'AltynBank' => '0',
                    'ApplePay' => '0',
                    'BANK' => '0',
                    'Cashapp' => '0',
                    'EurasianBank' => '0',
                    'ForteBank' => '0',
                    'GoMoney' => '0',
                    'GoPay' => '0',
                    'GPay' => '0',
                    'HalykBank' => '0',
                    'HomeCreditKazakhstan' => '0',
                    'JysanBank' => '0',
                    'KaspiBank' => '0',
                    'KoronaPay' => '0',
                    'Monobank' => '0',
                    'OTPBankRussia' => '0',
                    'Payme' => '0',
                    'Paysend' => '0',
                    'Paysera' => '0',
                    'Paytm' => '0',
                    'PerfectMoney' => '0',
                    'PiPay' => '0',
                    'PipolPay' => '0',
                    'PrivatBank' => '0',
                    'Revolut' => '0',
                    'SEPA' => '0',
                    'SWIFT' => '0',
                    'Ukrsibbank' => '0',
                    'UNIBANK' => '0',
                    'UniCreditRussia' => '0',
                    'Vodafonecash' => '0',
                    'WECHAT' => '0',
                    'WesternUnion' => '0',
                    'Wise' => '0',
            ];
            
            $banksBybitSet = [
                    'all-methods' => '',
                    'Tinkoff' => '75',
                    'RosBank' => '185',
                    'RaiffeisenBank' => '64',
                    'QIWI' => '62',
                    'YandexMoney' => '88',
                    'MTSBank' => '44',
                    'Payeer' => '51',
                    'Advcash' => '5',
                    'KapitalBank' => '212',
                    'ALIPAY' => '0',
                    'AltynBank' => '0',
                    'ApplePay' => '0',
                    'BANK' => '0',
                    'Cashapp' => '0',
                    'EurasianBank' => '0',
                    'ForteBank' => '0',
                    'GoMoney' => '0',
                    'GoPay' => '0',
                    'GPay' => '0',
                    'HalykBank' => '0',
                    'HomeCreditKazakhstan' => '0',
                    'JysanBank' => '0',
                    'KaspiBank' => '0',
                    'KoronaPay' => '0',
                    'Monobank' => '0',
                    'OTPBankRussia' => '0',
                    'Payme' => '0',
                    'Paysend' => '0',
                    'Paysera' => '0',
                    'Paytm' => '0',
                    'PerfectMoney' => '0',
                    'PiPay' => '0',
                    'PipolPay' => '0',
                    'PrivatBank' => '0',
                    'Revolut' => '0',
                    'SEPA' => '0',
                    'SWIFT' => '0',
                    'Ukrsibbank' => '0',
                    'UNIBANK' => '0',
                    'UniCreditRussia' => '0',
                    'Vodafonecash' => '0',
                    'WECHAT' => '0',
                    'WesternUnion' => '0',
                    'Wise' => '0',
            ];
           
            $result = [];
            
            foreach($coinsUSD as $uu) {
                $uu = ($asset=="all-usd") ? $uu : $asset;
            
            if(stripos($iffiatBybit, $uu) !== false){
                $options_bybit = "?userId=&tokenId=" . strtoupper($uu) . "&currencyId=" . strtoupper($fiat) . "&payment={$banksBybitSet[$t_method]}&side={$tradeTypeBybit[$tradeType]}&size=10&page=1&amount=";
                $response_bybit =  $this->client->request('GET', $this->url_bybit . $options_bybit);
                $data_bybit = json_decode($response_bybit->getBody());
            foreach ($data_bybit->result->items as $value) {
                $idn = "";
                //foreach($value->payments as $v){i(!empty($idn)) {$idn .= ", ";}f; $idn .= $banksBybitGet[$v]; }
                $details = array(
                    'typecoin' => $uu,
                    'price' => $value->price, // price
                    'minSingleTransAmount' => $value->minAmount, 
                    'dynamicMaxSingleTransAmount' => $value->maxAmount, 
                    'nickName' => $value->nickName,
                    'identifier' => $idn,
                    'exchange' => 'BYBIT',
                );
                array_push($result, $details);
                }
            }
            
            //return $result;
            if(stripos($iffiatHuobi, $uu) !== false){
                $options_huobi = "?coinId={$coinsHuobi[$uu]}&currency={$currHuobi[$fiat]}&tradeType={$tradeTypeOKX}&currPage=1&amount=0&payMethod={$banksHuobiSet[$t_method]}&blockType=general&online=1&range=0&onlyTradable=false";
                $response_huobi =  $this->client->request('GET', $this->url_huobi . $options_huobi);
                $data_huobi = json_decode($response_huobi->getBody());
            foreach ($data_huobi->data as $value) {
                $details = array(
                    'typecoin' => $uu,
                    'price' => $value->price, // price
                    'minSingleTransAmount' => $value->minTradeLimit, 
                    'dynamicMaxSingleTransAmount' => $value->maxTradeLimit, 
                    'nickName' => $value->userName,
                    'identifier' => implode(", ", array_column($value->payMethods, "name")),
                    'exchange' => 'HUOBI',
                );
                array_push($result, $details);
                }
            }
            //return $result;
            
            if(stripos($iffiatOKX, $uu) !== false){
                $options_okx = "?quoteCurrency={$fiat}&baseCurrency={$uu}&side={$tradeTypeOKX}&paymentMethod={$t_method}&quoteMinAmountPerOrder=0&sortType={$sortTypeOKX}";
                $response_okx =  $this->client->request('GET', $this->url_okx . $options_okx);
                $data_okx = json_decode($response_okx->getBody());
            foreach ($data_okx->data->{$tradeTypeOKX} as $value) {
                $details = array(
                    'typecoin' => $uu,
                    'price' => $value->price, // price
                    'minSingleTransAmount' => $value->quoteMinAmountPerOrder, // min trans amount limit
                    'dynamicMaxSingleTransAmount' => $value->quoteMaxAmountPerOrder, // max trans amount limit
                    'nickName' => $value->nickName,
                    'identifier' => join(", ", $value->paymentMethods),
                    'exchange' => 'OKX',
                );
                array_push($result, $details);
                $ii++;
                if ($ii>15){break;}
                }
            }
            
            if(stripos($iffiatBinance, $uu) !== false){
                $options = ['json' => [
                    'asset' => $uu,
                    'tradeType' => $tradeType,
                    'fiat' => $fiat,
                    'transAmount' => $t_value,
                    'order' => '',
                    'page' => 1,
                    'rows' => 15,
                    'payTypes' => ($t_method=="all-methods") ? [] : [$t_method],
                    ]
                ];
                $response_binance =  $this->client->request('POST', $this->url_binance, $options);
                $data_binance = json_decode($response_binance->getBody());
            foreach ($data_binance->data as $value) {
                $details = array(
                    'typecoin' => $uu,
                    'price' => $value->adv->price, // price
                    'minSingleTransAmount' => $value->adv->minSingleTransAmount, // min trans amount limit
                    'dynamicMaxSingleTransAmount' => $value->adv->dynamicMaxSingleTransAmount, // max trans amount limit
                    'nickName' => $value->advertiser->nickName,
                    'identifier' => implode(", ", array_column($value->adv->tradeMethods, "identifier")),
                    'exchange' => 'BINANCE',
                );
                array_push($result, $details);
                }
            }
            if($asset != "all-usd") {break;}
            }
            return $result;

        } catch (RequestException $e) {
            $response = json_decode($e->getResponse()->getBody());
            $this->logger->error($e->getResponse()->getBody());
            return (object)  array(
                'status' => 'error',
                'message' => $response->error->message
            );
        }
    }

}


$binance = new Binance();



if(!empty($_GET['tradeType']) && !empty($_GET['fiat']) && !empty($_GET['tmethod'])) {
    $asset = strtolower($_GET['asset']);
    $fiat = strtolower($_GET['fiat']);
    $tradeType = strtolower($_GET['tradeType']); 
    $t_method = $_GET['tmethod']; 
    $t_value = "0";
}
else {
    $asset = strtolower("USDt");
    $fiat = strtolower("USD");
    $tradeType = strtolower("BUY");
    $t_method = "all-methods";
    $t_value = "0";
}


echo json_encode($binance->exchange($asset, $fiat, $tradeType, $t_method, $t_value));

