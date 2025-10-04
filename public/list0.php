<?php

   ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    require_once('/var/www/zchk-app/vendor/autoload.php');

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Chrome\ChromeOptions;


$options = new ChromeOptions();
$options->addArguments(['--headless', '--disable-gpu', '--no-sandbox', '--disable-dev-shm-usage']);


// Замените 'http://localhost:4444/wd/hub' на URL вашего Selenium Server
$url = 'http://62.113.98.97:4444/wd/hub';
//$url = 'http://62.113.98.97:4444/wd/hub/static/resource/hub.html';


$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

$driver = RemoteWebDriver::create($url, $capabilities);

// Замените 'https://example.com' на URL веб-страницы, которую нужно спарсить
$driver->get('https://atomscan.com/accounts/cosmos164dpffm974ayj7j9r33t4yhv7a6wu59q6d2c6k');

// Получение сгенерированного HTML-кода
$html = $driver->getPageSource();

// Сохранение HTML-кода в строковую переменную
$html_string = $html;

// Закрытие браузера и завершение сессии WebDriver
$driver->quit();

// Здесь вы можете использовать строковую переменную с HTML-кодом для дальнейшей обработки, парсинга или сохранения в базу данных

// Пример: Вывод HTML-кода на экран
echo $html_string;