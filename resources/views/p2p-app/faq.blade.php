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

        @media (max-width:768px) {
            nav .logo {
                font-size: 19px !important;
            }

            .table_sort_div {
                padding: 16px 10px 20px !important;
            }

            .section_padding_130 {
                padding-top: 55px !important;
                padding-bottom: 60px !important;
            }

            .col-12 {
                -ms-flex: 0 0 98%;
                flex: 0 0 98%;
                max-width: 98%;
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

<body>
    <div style="min-height: calc(100vh - 180px);padding: 0 0 50px;">
        <x-price-line />

        @include('layouts.nav')

        <div class="faq_area section_padding_130" id="faq">
            <div class="container">
                <div class="justify-content-center">
                    <div class="col-12 col-sm-8 col-lg-6">
                        <!-- Section Heading-->
                        <div class="section_heading text-center wow fadeInUp" data-wow-delay="0.2s"
                            style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
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
                            <div class="card border-0 wow fadeInUp" data-wow-delay="0.2s"
                                style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                                <div class="card-header" id="headingOne">
                                    <h6 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseOne"
                                        aria-expanded="true" aria-controls="collapseOne">На чем построенно данное
                                        веб-приложение?<span class="lni-chevron-up"></span></h6>
                                </div>
                                <div class="collapse" id="collapseOne" aria-labelledby="headingOne"
                                    data-parent="#faqAccordion">
                                    <div class="card-body">
                                        <p>Данное веб-приложение построеннно на стеке LEMP (Linux+NGINX+MySQL+PHP) и
                                            PHP-фреймворке Laravel.</p>
                                        <p>Для визуального оформления использовался в том числе CSS-фреймворк Bootstrap.
                                        </p>
                                        <p>Данные о заявках из P2P обменников подтягиваются посредство открытых API
                                            бирж.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card border-0 wow fadeInUp" data-wow-delay="0.3s"
                                style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;">
                                <div class="card-header" id="headingTwo">
                                    <h6 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseTwo"
                                        aria-expanded="true" aria-controls="collapseTwo">Что дает наличие аккаунта в
                                        приложении?<span class="lni-chevron-up"></span></h6>
                                </div>
                                <div class="collapse" id="collapseTwo" aria-labelledby="headingTwo"
                                    data-parent="#faqAccordion">
                                    <div class="card-body">
                                        <p>Наличие аккаунта позволяет сохранять свои позиции (покупки) в паре USD/RUB.
                                        </p>
                                        <p>Их можно отследить на графике на странице — <a href="/stat">Статистика</a>,
                                            визуально отобразив средний вход и покупки начиная с даты отслеживания
                                            курсов.</p>
                                        <p>В дальнейшем для зарегистрированных пользователей появится функция поиска
                                            лучшего спреда между биржами (в разработке).</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card border-0 wow fadeInUp" data-wow-delay="0.4s"
                                style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;">
                                <div class="card-header" id="headingThree">
                                    <h6 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseThree"
                                        aria-expanded="true" aria-controls="collapseThree">Какие статистические данные
                                        собираются о курсах?<span class="lni-chevron-up"></span></h6>
                                </div>
                                <div class="collapse" id="collapseThree" aria-labelledby="headingThree"
                                    data-parent="#faqAccordion">
                                    <div class="card-body">
                                        <p>Веб-приложение собирает данные о курсах доллара с P2P обменников и
                                            Центрального Банка.</p>
                                        <p>Сравнение данные можно отследить на графике на странице — <a
                                                href="/stat">Статистика</a>. Также на графике выводится разница в %
                                            между официальным курсом от Центрального Банка России и фактическим на
                                            биржевых P2P обменниках.</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <style>
            .h6,
            h6 {
                font-size: 15px;
            }

            .section_padding_130 {
                padding-top: 90px;
                padding-bottom: 90px;
            }

            .faq_area {
                position: relative;
                z-index: 1;

            }

            .justify-content-center {
                -ms-flex-pack: center !important;
                justify-content: center !important;
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
            }
        </style>

        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    </div>

    @include('layouts.footer')
</body>
</html>