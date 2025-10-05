<?php
    require_once('/var/www/zchk-app/resources/views/p2p-app/options.php');
    header('Access-Control-Allow-Origin: *');    	    
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Агрегатор наилучших курсов P2P | p2p.zchk.ru</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-attachment: fixed!important;
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

        .btn-primary:hover {
            color: #fff;
            background-color: #286090 !important;
            border-color: #204d74;
        }

        .btn-primary {
            background-color: #536aad;
            margin-right: 5px;
            border-radius: 8px;
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
                    Binance / OKX / Huobi / Bybit P2P</div>
                <div class="panel-body">
                    <label for="crypto">* Выберите Актив:</label>
                    <select id="crypto" class="form-control">
                        <?php foreach ($options['crypto'] as $value) {
						echo '<option value="'.$value.'">'.$value.'</option>';
					}?>
                    </select>
                    <br>
                    <label for="fiat">* Выберите фиатную валюту:</label>
                    <select id="fiat" class="form-control">
                        <?php foreach ($options['fiat'] as $value) {
							echo '<option value="'.$value.'">'.$value.'</option>';
						}?>
                    </select>
                    <br>
                    <label for="exchange">* Выберите тип обмена:</label>
                    <select id="exchange" class="form-control">
                        <?php foreach ($options['exchange'] as $key => $v) {
							echo '<option value="'.$v.'">'.$key.'</option>';
						}?>
                    </select>
                    <br>
                    <label for="payment">* Выберите метод оплаты:</label>
                    <select id="payment" class="form-control">
                    </select>
                    <br>
                    <label for="_value">Введите искомое количество (необязательно):</label>
                    <input type="number" id="_value" name="value" class="form-control">
                    <br>
                </div>
                <div class="panel-footer">
                    <div class="text-center">
                        <button class="btn btn-primary" id="load" data-loading-text="Загружаем..."
                            style="padding: 7px 20px;">Загрузить</button>
                        <!-- <button class="btn btn-primary" id="load" data-loading-text="Загружаем...">Лучший спред</button> -->
                    </div>
                </div>
            </div>

            <script>
                var firstList = document.getElementById("fiat");
                var secondList = document.getElementById("payment");
                var options = {
                    'USD': ['Все методы', 'Advcash', 'ALIPAY', 'AltynBank', 'Банковский перевод', 'Cashapp',
                        'Forte Bank', 'GoMoney', 'GoPay', 'GPay', 'HalykBank', 'JysanBank', 'KaspiBank',
                        'Monobank', 'Payeer', 'Payme', 'Paysend', 'Paysera', 'Paytm', 'PerfectMoney', 'PiPay',
                        'QIWI', 'RaiffeisenBank Aval', 'Revolut', 'SEPA', 'SWIFT', 'Ukrsibbank', 'UNIBANK',
                        'UniCreditEU', 'WECHAT', 'WesternUnion', 'Wise'
                    ],
                    'USDv': ['all-methods', 'Advcash', 'ALIPAY', 'AltynBank', 'BANK', 'Cashapp', 'ForteBank',
                        'GoMoney', 'GoPay', 'GPay', 'HalykBank', 'JysanBank', 'KaspiBank', 'Monobank', 'Payeer',
                        'Payme', 'Paysend', 'Paysera', 'Paytm', 'PerfectMoney', 'PiPay', 'QIWI',
                        'RaiffeisenBankAval', 'Revolut', 'SEPA', 'SWIFT', 'Ukrsibbank', 'UNIBANK',
                        'UniCreditEU', 'WECHAT', 'WesternUnion', 'Wise'
                    ],
                    'EUR': ['Все методы', 'Advcash', 'ALIPAY', 'AltynBank', 'ApplePay', 'BANK', 'Cashapp',
                        'ForteBank', 'GPay', 'HalykBank', 'JysanBank', 'KaspiBank', 'Monobank', 'OTPBank',
                        'Payeer', 'Payme', 'Paysend', 'Paysera', 'Paytm', 'PerfectMoney', 'RaiffeisenBankAval',
                        'Revolut', 'SEPA', 'SWIFT', 'Ukrsibbank', 'UNIBANK', 'UniCreditEU', 'WECHAT',
                        'WesternUnion', 'Wise'
                    ],
                    'EURv': ['all-methods', 'Advcash', 'ALIPAY', 'AltynBank', 'ApplePay', 'BANK', 'Cashapp',
                        'ForteBank', 'GPay', 'HalykBank', 'JysanBank', 'KaspiBank', 'Monobank', 'OTPBank',
                        'Payeer', 'Payme', 'Paysend', 'Paysera', 'Paytm', 'PerfectMoney', 'RaiffeisenBankAval',
                        'Revolut', 'SEPA', 'SWIFT', 'Ukrsibbank', 'UNIBANK', 'UniCreditEU', 'WECHAT',
                        'WesternUnion', 'Wise'
                    ],
                    'CNY': ['Все методы', 'ALIPAY', 'BANK', 'WECHAT'],
                    'CNYv': ['all-methods', 'ALIPAY', 'BANK', 'WECHAT'],
                    'GBP': ['Все методы', 'Advcash', 'ALIPAY', 'BANK', 'Cashapp', 'Gcash', 'Monobank', 'Payeer',
                        'Paysera', 'Revolut', 'SEPA', 'SWIFT', 'WECHAT', 'WesternUnion', 'Wise'
                    ],
                    'GBPv': ['all-methods', 'Advcash', 'ALIPAY', 'BANK', 'Cashapp', 'Gcash', 'Monobank', 'Payeer',
                        'Paysera', 'Revolut', 'SEPA', 'SWIFT', 'WECHAT', 'WesternUnion', 'Wise'
                    ],
                    'KZT': ['Все методы', 'Advcash', 'AltynBank', 'Банковский перевод', 'ForteBank', 'HalykBank',
                        'JysanBank', 'KaspiBank', 'KoronaPay', 'QIWI'
                    ],
                    'KZTv': ['all-methods', 'Advcash', 'AltynBank', 'BANK', 'ForteBank', 'HalykBank', 'JysanBank',
                        'KaspiBank', 'KoronaPay', 'QIWI'
                    ],
                    'RUB': ['Все методы', 'Advcash', 'Банковский перевод', 'HomeCreditBank', 'ОТП Банк', 'Payeer',
                        'QIWI', 'Райффайзенбанк', 'Росбанк', 'Тинькофф Банк', 'ЮMoney', 'Юникредит банк',
                        'СБП (Система быстрых платежей)', 'МТС-Банк', 'Сбербанк'
                    ],
                    'RUBv': ['all-methods', 'Advcash', 'BANK', 'HomeCreditBank', 'OTPBankRussia', 'Payeer', 'QIWI',
                        'RaiffeisenBank', 'RosBank', 'Tinkoff', 'YandexMoneyNew', 'UniCreditRussia', 'SBP',
                        'MTSBank', 'Sberbank'
                    ],
                    'TRY': ['Все методы', 'Advcash', 'BANK', 'Revolut', 'Wise'],
                    'TRYv': ['all-methods', 'Advcash', 'BANK', 'Revolut', 'Wise'],
                    'UAH': ['Все методы', 'Advcash', 'BANK', 'Monobank', 'OTPBank', 'Paysend', 'SettlePay',
                        'RaiffeisenBankAval', 'Revolut', 'SEPA', 'Ukrsibbank', 'Wise'
                    ],
                    'UAHv': ['all-methods', 'Advcash', 'BANK', 'Monobank', 'OTPBank', 'Paysend', 'SettlePay',
                        'RaiffeisenBankAval', 'Revolut', 'SEPA', 'Ukrsibbank', 'Wise'
                    ]
                };
                // Функция, которая будет вызываться при изменении первого списка
                function updateSecondList() {
                    var selectedValue = firstList.value;
                    secondList.innerHTML = "";
                    for (var i = 0; i < options[selectedValue].length; i++) {
                        var option = document.createElement("option");
                        option.text = options[selectedValue][i];
                        option.value = options[selectedValue + "v"][i];
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
                        e.forEach(function(item) {
                            $('#table-logs').append('<tr><td>' + item.typecoin +
                                '</td><td>' + Number(item.price).toFixed(2) +
                                '</td><td>' + item.minSingleTransAmount + ' - ' +
                                item.dynamicMaxSingleTransAmount + '</td><td>' +
                                item.nickName + ' (' + item.exchange + ')<hr>' +
                                item.identifier + '</td></tr>');
                        })
                        $("#load").button('reset');
                    });
                });
            });
        </script>

        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', () => {
                const getSort = ({
                    target
                }) => {
                    const order = (target.dataset.order = -(target.dataset.order || -1));
                    const index = [...target.parentNode.cells].indexOf(target);
                    const collator = new Intl.Collator(['en', 'ru'], {
                        numeric: true
                    });
                    const comparator = (index, order) => (a, b) => order * collator.compare(
                        a.children[index].innerHTML,
                        b.children[index].innerHTML
                    );
                    for (const tBody of target.closest('table').tBodies)
                        tBody.append(...[...tBody.rows].sort(comparator(index, order)));
                    for (const cell of target.parentNode.cells)
                        cell.classList.toggle('sorted', cell === target);
                };
                document.querySelectorAll('.table_sort thead').forEach(tableTH => tableTH.addEventListener(
                    'click', () => getSort(event)));
            });
        </script>

        <style>
            hr {
                border-top: 1px solid #9b9898;
                margin: 5px 0;
            }

            .table_sort table {
                border-collapse: collapse;
            }

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

            th.sorted[data-order="-1"]::after {
                content: "▼"
            }

            th.sorted[data-order="1"]::after {
                content: "▲"
            }
        </style>
    </div>

    @include('layouts.footer')
</body>
</html>