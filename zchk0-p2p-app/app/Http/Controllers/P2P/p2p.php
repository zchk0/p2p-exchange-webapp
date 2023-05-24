<?php

namespace App\Http\Controllers\P2P;
require_once('/var/www/zchk-app/vendor/autoload.php');

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class p2p extends Controller
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
    
    public function exchange($asset, $fiat = "usd", $tradeType = "buy", $t_method = "all-methods", $t_value)
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
            
            
            
            $binance_bank_list = [
                'all-methods' => 'all-payments',
                'Advcash' => 'Advcash',
                'ALIPAY' => 'ALIPAY',
                'AltynBank' => 'AltynBank',
                'ApplePay' => 'ApplePay',
                'BANK' => 'BANK',
                'Cashapp' => 'Cashapp',
                'ForteBank' => 'ForteBank',
                'GoMoney' => 'GoMoney',
                'GoPay' => 'GoPay',
                'GPay' => 'GPay',
                'Gcash' => 'Gcash',
                'HalykBank' => 'HalykBank',
                'HomeCreditBank' => 'HomeCreditBank',
                'JysanBank' => 'JysanBank',
                'KaspiBank' => 'KaspiBank',
                'KoronaPay' => 'KoronaPay',
                'Monobank' => 'Monobank',
                'OTPBankRussia' => 'OTPBankRussia',
                'OTPBank' => 'OTPBank',
                'Payeer' => 'Payeer',
                'Payme' => 'Payme',
                'Paysend' => 'Paysend',
                'Paysera' => 'Paysera',
                'Paytm' => 'Paytm',
                'PerfectMoney' => 'PerfectMoney',
                'PiPay' => 'PiPay',
                'PrivatBank' => 'PrivatBank',
                'QIWI' => 'QIWI',
                'RaiffeisenBank' => 'RaiffeisenBank',
                'RaiffeisenBankAval' => 'RaiffeisenBankAval',
                'Revolut' => 'Revolut',
                'RosBank' => 'RosBankNew',
                'SEPA' => 'SEPA',
                'SWIFT' => 'SWIFT',
                'SettlePay' => 'SettlePay',
                'Tinkoff' => 'TinkoffNew',
                'Ukrsibbank' => 'Ukrsibbank',
                'UNIBANK' => 'UNIBANK',
                'UniCreditEU' => 'UniCreditEU',
                'UniCreditRussia' => 'UniCreditRussia',
                'WECHAT' => 'WECHAT',
                'WesternUnion' => 'WesternUnion',
                'Wise' => 'Wise',
                'YandexMoneyNew' => 'YandexMoneyNew',
                'Sberbank' => 'Sberbank', // нет
                'MTSBank' => 'MTSBank',
                'PostBankNew' => 'PostBankNew'
            ];
            
            $okx_bank_list = [
                'all-methods' => '',
                'Advcash' => 'advcash',
                //'ALIPAY' => '', // нет
                'AltynBank' => 'Altyn%20Bank',
                //'ApplePay' => '', // нет
                'BANK' => 'bank',
                'Cashapp' => 'Cash%20App',
                'ForteBank' => 'Forte%20Bank',
                //'GoMoney' => '', // нет
                //'GoPay' => '', // нет
                //'GPay' => '', // нет
                //'Gcash' => '', // нет
                'HalykBank' => 'Halyk%20Bank',
                'HomeCreditBank' => 'Home%20Credit',
                'JysanBank' => 'Jysan%20Bank',
                'KaspiBank' => 'Kaspi%20Bank',
                //'KoronaPay' => '', // нет
                'Monobank' => 'Monobank',
                //'OTPBankRussia' => '', // нет
                //'OTPBank' => '', // нет
                'Payeer' => 'Payeer',
                //'Payme' => '', // нет
                //'Paysend' => '', // нет
                'Paysera' => 'Paysera',
                //'Paytm' => '', // нет
                'PerfectMoney' => 'Perfect%20Money',
                //'PiPay' => '', // нет
                'PrivatBank' => 'PrivatBank',
                'QIWI' => 'qiwi',
                'RaiffeisenBank' => 'Raiffaizen',
                'RaiffeisenBankAval' => '', // их там дофига
                'Revolut' => 'Revolut',
                'RosBank' => 'RosBank',
                'SEPA' => 'SEPA%20Instant',
                'SWIFT' => 'SWIFT',
                //'SettlePay' => '', // нет
                'Tinkoff' => 'tinkoff',
                'Ukrsibbank' => 'Ukrsibbank',
                //'UNIBANK' => '',  // нет
                //'UniCreditEU' => '',  // их там дофига
                'UniCreditRussia' => 'UniCredit%20Bank',
                'WECHAT' => 'wechat',
                'WesternUnion' => 'WesternUnion',
                'Wise' => 'wise',
                'YandexMoneyNew' => 'Yandex.Money',
                'Sberbank' => 'Sberbank',
                'MTSBank' => 'MTS%20Bank',
                'SBP' => 'SBP%20Fast%20Bank%20Transfer'
            ];
            
            $huobi_bank_list = [
                'all-methods' => '0',
                'Advcash' => '20',
                '20' => 'Advcash',
                'ALIPAY' => '2',
                '2' => 'ALIPAY',
                'AltynBank' => '138',
                '138' => 'AltynBank',
                //'ApplePay' => '', 
                'BANK' => '1',
                '1' => 'BANK',
                //'Cashapp' => '',
                'ForteBank' => '355',
                '355' => 'ForteBank',
                //'GoMoney' => '', 
                //'GoPay' => '', 
                'GPay' => '86',
                '86' => 'GPay',
                //'Gcash' => '', 
                'HalykBank' => '354',
                '354' => 'HalykBank',
                'HomeCreditBank' => '172',
                '172' => 'HomeCreditBank',
                'JysanBank' => '127',
                '127' => 'JysanBank',
                'KaspiBank' => '353',
                '353' => 'KaspiBank',
                //'KoronaPay' => '',
                'Monobank' => '49',
                '49' => 'Monobank',
                'OTPBankRussia' => '45', 
                '45' => 'OTPBankRussia',
                'OTPBank' => '259', 
                '259' => 'OTPBank',
                'Payeer' => '24',
                '24' => 'Payeer',
                'Payme' => '23', 
                '23' => 'Payme',
                'Paysend' => '407', 
                '407' => 'Paysend',
                'Paysera' => '112',
                '112' => 'Paysera',
                'Paytm' => '8',
                '8' => 'Paytm',
                'PerfectMoney' => '43',
                '43' => 'PerfectMoney',
                //'PiPay' => '', 
                'PrivatBank' => '33',
                '33' => 'PrivatBank',
                'QIWI' => '9',
                '9' => 'QIWI',
                'RaiffeisenBank' => '36',
                '36' => 'RaiffeisenBank',
                'RaiffeisenBankAval' => '155', 
                '155' => 'RaiffeisenBankAval',
                'Revolut' => '41',
                '41' => 'Revolut',
                'RosBank' => '358',
                '358' => 'RosBank',
                'SEPA' => '303',
                '303' => 'SEPA',
                //'SWIFT' => '',
                //'SettlePay' => '', 
                'Tinkoff' => '28',
                '28' => 'Tinkoff',
                'Ukrsibbank' => '154',
                '154' => 'Ukrsibbank',
                //'UNIBANK' => '',  
                //'UniCreditEU' => '',  
                //'UniCreditRussia' => '', // нет заявок
                'WECHAT' => '3',
                '3' => 'WECHAT',
                'WesternUnion' => '5',
                '5' => 'WesternUnion',
                'Wise' => '34',
                '34' => 'Wise',
                'YandexMoneyNew' => '19',
                '19' => 'ЮMoney',
                'Sberbank' => '29',
                '29' => 'Sberbank',
                'Alfabank' => '25',
                '25' => 'Alfabank',
                'SBP' => '69',
                '69' => 'SBP',
                'MTSBank' => '356',
                '356' => 'MTSBank'
            ];
            $coinsHuobi = [
                    'usdt' => 2, 'btc' => 1, 'eth' => 3, 'usdd' => 5, 'ltc' => 8,
            ];
            $currHuobi = [
                    'rub' => 11,
                    'usd' => 2,
                    'kzt' => 57,
                    'eur' => 14,
                    'try' => 23,
                    'uah' => 45,
                    'cny' => 1, // не выгружает
                    'gpb' => 12,
                    'sgd' => 3,
                    'inr' => 4,
                    'vnd' => 5,
                    'cad' => 6,
                    'aud' => 7,
                    'chf' => 9,
                    'twd' => 10,
                    'hkd' => 13,
                    'ngn' => 15,
                    'idr' => 16,
                    'php' => 17,
                    'khr' => 18,
                    'brl' => 19,
                    'sar' => 20,
                    'aed' => 21,
                    'myr' => 22,
                    'nzd' => 24,
                    'zar' => 26,
                    'nok' => 27,
                    'dkk' => 28,
                    'sek' => 29,
                    'ars' => 30,
                    'thb' => 31,
                    'cop' => 32,
                    'ves' => 33,
                    'kes' => 34,
                    'czk' => 37,
                    'huf' => 38,
                    'ils' => 39,
                    'mad' => 41,
                    'mxn' => 42,
                    'pln' => 43,
                    'ron' => 44,
                    'all' => 46,
                    'clp' => 53,
                    'dop' => 55,
                    'gel' => 56,
                    'qar' => 59,
                    'byn' => 72,
                    'kgs' => 80,
                    'mnt' => 84,
                    'mop' => 85,
            ];

            $bybit_bank_list = [
                'all-methods' => '',
                'Advcash' => '5',
                '5' => 'Advcash',
                'ALIPAY' => '2',
                '2' => 'ALIPAY',
                'AltynBank' => '280',
                '280' => 'AltynBank',
                'ApplePay' => '10', 
                '10' => 'Apple Pay', 
                'BANK' => '14',
                '14' => 'BANK',
                'Cashapp' => '96',
                '96' => 'Cash App',
                'ForteBank' => '144',
                '144' => 'ForteBank',
                //'GoMoney' => '', 
                'GoPay' => '30',
                '30' => 'GoPay', 
                'GPay' => '166',
                '166' => 'Google Pay',
                'Gcash' => '28',
                '28' => 'Gcash',
                'HalykBank' => '203',
                '203' => 'HalykBank',
                'HomeCreditBank' => '263',
                '263' => 'HomeCreditBank KAZ',
                'JysanBank' => '149',
                '149' => 'JysanBank',
                'KaspiBank' => '150',
                '150' => 'Kaspi Bank',
                'KoronaPay' => '551',
                '551' => 'KoronaPay',
                'Monobank' => '43',
                '43' => 'Monobank',
                'OTPBankRussia' => '49', 
                '49' => 'OTPBankRussia',
                'OTPBank' => '48', 
                '48' => 'OTPBank',
                'Payeer' => '51',
                '51' => 'Payeer',
                'Payme' => '53', 
                '53' => 'Payme',
                'Paysend' => '157', 
                '157' => 'Paysend',
                'Paysera' => '158',
                '158' => 'Paysera',
                'Paytm' => '55',
                '55' => 'Paytm',
                'PerfectMoney' => '56',
                '56' => 'PerfectMoney',
                //'PiPay' => '', 
                'PrivatBank' => '60',
                '60' => 'PrivatBank',
                'QIWI' => '62',
                '62' => 'QIWI',
                'RaiffeisenBank' => '64',
                '64' => 'RaiffeisenBank',
                'RaiffeisenBankAval' => '63', 
                '63' => 'RaiffeisenBankAval',
                'Revolut' => '303',
                '303' => 'Revolut',
                'RosBank' => '185',
                '185' => 'RosBank',
                'SEPA' => '65',
                '65' => 'SEPA',
                'SWIFT' => '73',
                //'SettlePay' => '', 
                'Tinkoff' => '75',
                '75' => 'Tinkoff',
                'Ukrsibbank' => '80',
                '80' => 'Ukrsibbank',
                'UNIBANK' => 'UNIBANK',
                '238' => '238',
                //'UniCreditEU' => '',  
                'UniCreditRussia' => '166',
                '166' => 'UniCreditRussia',
                'WECHAT' => '3',
                '3' => 'WECHAT',
                'WesternUnion' => '87',
                '87' => 'WesternUnion',
                'Wise' => '78',
                '78' => 'Wise',
                'YandexMoneyNew' => '274',
                '274' => 'ЮMoney',
                'Sberbank' => '377',
                '377' => 'Sberbank',
                'Alfabank' => '379',
                '379' => 'Alfabank',
                'SBP' => '382',
                '382' => 'СБП',
                'MTSBank' => '44',
                '44' => 'МТС-Банк'
            ];
           
            $result = [];
            
            foreach($coinsUSD as $uu) {
                $uu = ($asset=="all-usd") ? $uu : $asset;
            
            if(stripos($iffiatBybit, $uu) !== false && array_key_exists($t_method, $bybit_bank_list)){
                $t_value_bybit = $t_value == 0 ? "" : $t_value;
                $options_bybit = "?userId=&tokenId=" . strtoupper($uu) . "&currencyId=" . strtoupper($fiat) . "&payment={$bybit_bank_list[$t_method]}&side={$tradeTypeBybit[$tradeType]}&size=10&page=1&amount=" . $t_value_bybit;
                $response_bybit =  $this->client->request('GET', $this->url_bybit . $options_bybit);
                $data_bybit = json_decode($response_bybit->getBody());
                $t_type_url_bybit = "https://www.bybit.com/fiat/trade/otc/?actionType={$tradeTypeBybit[$tradeType]}&token=".strtoupper($uu)."&fiat=".strtoupper($fiat)."&paymentMethod=" . $bybit_bank_list[$t_method];
            foreach ($data_bybit->result->items as $value) {
                $idn = array();
                foreach($value->payments as $v){ if(array_key_exists($v, $bybit_bank_list)) {$idn[] = $bybit_bank_list[$v];}; }
                $t_type_url_bybit_user = "https://www.bybit.com/fiat/trade/otc/profile/".$value->userId."/".strtoupper($uu)."/".strtoupper($fiat)."/item";
                $details = array(
                    'typecoin' => $uu,
                    'price' => $value->price, // price
                    'minSingleTransAmount' => $value->minAmount, 
                    'dynamicMaxSingleTransAmount' => $value->maxAmount, 
                    'nickName' => "<a href='{$t_type_url_bybit_user}' style='font-weight: 200;' target='_blank'>$value->nickName</a>",
                    'identifier' => implode(', ', $idn) . "<br><a href='{$t_type_url_bybit}' style='font-weight: 600;' target='_blank'>ПЕРЕЙТИ</a>",
                    'exchange' => 'BYBIT',
                );
                array_push($result, $details);
                }
            }
            
            //return $result;
            if(stripos($iffiatHuobi, $uu) !== false && array_key_exists($t_method, $huobi_bank_list)){
                $options_huobi = "?coinId={$coinsHuobi[$uu]}&currency={$currHuobi[$fiat]}&tradeType={$tradeTypeOKX}&currPage=1&amount={$t_value}&payMethod={$huobi_bank_list[$t_method]}&blockType=general&online=1&range=0&onlyTradable=false";
                $response_huobi =  $this->client->request('GET', $this->url_huobi . $options_huobi);
                $data_huobi = json_decode($response_huobi->getBody());
            foreach ($data_huobi->data as $value) {
                $details = array(
                    'typecoin' => $uu,
                    'price' => $value->price, // price
                    'minSingleTransAmount' => $value->minTradeLimit, 
                    'dynamicMaxSingleTransAmount' => $value->maxTradeLimit, 
                    'nickName' => $value->userName,
                    'identifier' => implode(", ", array_column($value->payMethods, "name")). "<br><a href='https://www.huobi.com/ru-ru/fiat-crypto/trade/{$tradeTypeOKX}-{$uu}-{$fiat}/' style='font-weight: 600;' target='_blank'>ПЕРЕЙТИ</a>",
                    'exchange' => 'HUOBI',
                );
                array_push($result, $details);
                }
            }
            //return $result;

            if(stripos($iffiatOKX, $uu) !== false && array_key_exists($t_method, $okx_bank_list)){
                $options_okx = "?quoteCurrency={$fiat}&baseCurrency={$uu}&side={$tradeTypeOKX}&paymentMethod={$okx_bank_list[$t_method]}&quoteMinAmountPerOrder={$t_value}&sortType={$sortTypeOKX}";
                $response_okx =  $this->client->request('GET', $this->url_okx . $options_okx);
                $data_okx = json_decode($response_okx->getBody());
            foreach ($data_okx->data->{$tradeTypeOKX} as $value) {
                $details = array(
                    'typecoin' => $uu,
                    'price' => $value->price, // price
                    'minSingleTransAmount' => $value->quoteMinAmountPerOrder, // min trans amount limit
                    'dynamicMaxSingleTransAmount' => $value->quoteMaxAmountPerOrder, // max trans amount limit
                    'nickName' => $value->nickName,
                    'identifier' => join(", ", $value->paymentMethods) ." <br><a href='https://www.okx.com/ru/p2p-markets/{$fiat}/{$tradeType}-{$uu}' style='font-weight: 600;' target='_blank'>ПЕРЕЙТИ</a>",
                    'exchange' => 'OKX',
                );
                array_push($result, $details);
                $ii++;
                if ($ii>15){break;}
                }
            }
            //return $result;
            
            if(stripos($iffiatBinance, $uu) !== false && array_key_exists($t_method, $binance_bank_list)){
                //dd($t_value);
                $t_type_url = ($tradeType=="buy") ? "https://p2p.binance.com/ru/trade/{$binance_bank_list[$t_method]}/" . mb_strtoupper($uu) . "?fiat=" . mb_strtoupper($fiat) : "https://p2p.binance.com/ru/trade/sell/" . mb_strtoupper($uu) . "?fiat=" . mb_strtoupper($fiat) . "&payment={$binance_bank_list[$t_method]}";
                
                $options = ['json' => [
                    'asset' => $uu,
                    'tradeType' => $tradeType,
                    'fiat' => $fiat,
                    'transAmount' => $t_value,
                    'order' => '',
                    'page' => 1,
                    'rows' => 15,
                    'payTypes' => ($t_method=="all-methods") ? [] : [$binance_bank_list[$t_method]],
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
                    'identifier' => implode(", ", array_column($value->adv->tradeMethods, "identifier")) . "<br><a href='{$t_type_url}' style='font-weight: 600;' target='_blank'>ПЕРЕЙТИ</a>",
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
    


    public function _p2p (Request $request) {
        
        $fiat = strtolower($request->fiat);
        $asset = strtolower($request->asset);
        $tradeType = strtolower($request->tradeType); 
        $t_method = ($request->tmethod == "Все методы") ? "all-methods" : $request->tmethod; 
        $t_value = empty($request->tvalue) ? 0 : $request->tvalue;
        
        if(empty($fiat . $asset . $tradeType . $t_method)) {
            $asset = strtolower("USDt");
            $fiat = strtolower("USD");
            $tradeType = strtolower("BUY");
            $t_method = "all-methods";
            $t_value = "0";
        }
        return response()->json($this->exchange($asset, $fiat, $tradeType, $t_method, $t_value), 200);
    }
}
