<?php
    require_once('/var/www/zchk-app/resources/views/p2p-app/options.php');
    header('Access-Control-Allow-Origin: *');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Агрегатор наилучших курсов P2P | p2p.zchk.ru</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .top-0 {
            top: 0 !important
        }

        .top-50 {
            top: 50% !important
        }

        .top-100 {
            top: 100% !important
        }

        .top-line {
            background: #fff;
            border-bottom: 1px solid #ccc;
            overflow: hidden
        }

        .top-line2 {
            background: #fff;
            border-bottom: 1px solid #ccc;
            cursor: default;
            opacity: .7;
            overflow-x: scroll;
            transition: all .2s
        }

        .top-line2 ._inner {
            align-items: center;
            display: flex;
            padding: 15px 15px
        }

        .top-line2 ._inner>div {
            align-items: center;
            display: flex;
            font-size: 13px;
            margin-right: 30px;
            white-space: nowrap
        }

        .top-line2 ._inner>div ._t {
            color: #58667e;
            font-weight: 600;
            margin-right: 15px
        }

        .top-line2 ._inner>div ._v {
            background: #f9f9f9;
            color: #58667e;
            margin: 0 15px 0 0;
            padding: 0 11px
        }

        .top-line2 ._inner>div ._v i {
            color: #384252;
            font-size: 10px;
            font-style: normal
        }

        .top-line2 ._inner>div:last-child {
            margin-right: 0
        }

        .top-line2:hover {
            opacity: 1
        }

        .top-line2:hover ._inner>div ._t {
            color: #0a2882
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

<body style="background-attachment: fixed!important;background: linear-gradient(0deg,rgb(27 56 44 / 13%),rgb(242 242 255));">
    <div style="min-height: calc(100vh - 180px);padding: 0 0 50px;">
        @include('components.price-line')

        <nav>
            <div class="container0">
                <div><a href="/" class="logo" title="p2p">p2p.zchk.ru</a></div>
                <div>
                    <div class="top-buttons">
                        <a class="login-req-btn" href="/">На главную</a>
                        <a class="login-req-btn" href="/logout">Выйти</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container" style="padding-top: 55px;">
            <div class="panel panel-primary" style="border-color: #b9b9b9;">
                <div class="panel-heading" style="padding: 15px 20px;background-color: #5a5a5a;border-color: #6b6c6c;">
                    Веб-панель</div>
                <div class="panel-body">
                    <div style="padding: 16px 16px 20px;background: whitesmoke;line-height: 1.8;">
                        <span style="font-weight: 700;">Ваш ID:</span> {{ Auth::user()->id }}
                        <br>
                        <span style="font-weight: 700;">Email:</span> {{ Auth::user()->email }}
                        <br>
                        <span style="font-weight: 700;">Логин:</span> {{ Auth::user()->login }}
                        <br>
                        <span style="font-weight: 700;">Дата регистрации:</span> {{ Auth::user()->created_at }}
                    </div>
                    <div>
                        <h2 style="padding-left: 15px;">Сменить пароль</h2>

                        <form class="panel-body" action="{{ route('user.changepassword') }}" method="post" name="form1">
                            @csrf
                            <label for="password">Старый пароль:</label>
                            <input class="form-control" id="password" name="old_password" type="password" value=""
                                placeholder="Старый пароль">
                            <br>
                            <label for="password">Новый пароль:</label>
                            <input class="form-control" id="password" name="password" type="password" value=""
                                placeholder="Новый пароль">
                            @if (session('textt'))
                            <br>
                            <div class="alert alert-success">{{ session('textt') }}</div>
                            @endif
                            @if (session('error'))
                            <br>
                            <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <br>
                            <div style="text-align: right;">
                                <button class="btn btn-primary" type="submit" name="sendMe" value="1"
                                    style=" padding: 8px 25px;font-size: 14px;">Изменить</button>
                            </div>
                        </form>
                    </div>

                    <div>
                        <h2 style="padding-left: 15px;">Добавить цену покупки в паре USD/RUB</h2>

                        <div style="padding-left: 15px;">Добавив свои уровни покупки, можно их отследить на графике на
                            странице — <a href="/stat">Статистика</a>, визуально отобразив средний вход и покупки
                            начиная с даты отслеживания курсов.</div>

                        <form class="panel-body" action="{{ route('user.addprice') }}" method="post" name="form2">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <label for="date">Дата:</label>
                            <input required class="form-control" id="password" name="datet" class="password" type="date"
                                value="" placeholder="Дата покупки">
                            <br>
                            <label for="number">Цена:</label>
                            <input required class="form-control" min="0" step="0.01" id="password" name="pricet"
                                type="number" value="" placeholder="Цена покупки">
                            <br>
                            <label for="number">Количество:</label>
                            <input required class="form-control" min="0" step="0.01" id="password" name="valuet"
                                type="number" value="" placeholder="Количество">

                            @if (session('othert'))
                            <br>
                            <div class="alert alert-success">
                                {{ session('othert') }}
                            </div>
                            @endif
                            <br>
                            <div style="text-align: right;">
                                <button class="btn btn-primary" type="submit" name="sendMe" value="1"
                                    style=" padding: 8px 25px;font-size: 14px;">Добавить</button>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="panel-footer">
                </div>
            </div>

            <br>
            @if (session('tabledel'))
            <div class="alert alert-success">{{ session('tabledel') }}</div>
            @endif
            <table class="table_sort">
                <thead>
                    <tr>
                        <th>ДАТА</th>
                        <th>ЦЕНА</th>
                        <th>КОЛ-ВО</th>

                    </tr>
                </thead>
                <tbody id="table-logs">
                    <?php foreach($pricelistt as $t){
       echo '<tr><td>' . $t->date . '</td><td>' . $t->price . ' $</td><td>' . $t->value . '</td><td style="border: none;text-align: left;background: none;">
       <form action="' . route('user.deleteprice', $t->id) . '" method="POST">
       <input type="hidden" name="_token" value="' . csrf_token() . '">
       <button class="btn btn-primary" type="submit" name="deleteprice" value="' . $t->id . '" style="padding: 0px 12px;margin: 6px 6px 6px 18px;font-size: 25px;font-weight: 700;background-color: #d54444;border-color: #d54444;">×</button>
       </form></td></tr>';
       }?>
                </tbody>
            </table>

        </div>
        <script src="/jquery-3.6.4.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

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

            .table_sort tbody tr:nth-child(even) td {
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