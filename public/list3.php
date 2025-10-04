<?php

require_once('/var/www/zchk-app/vendor/autoload.php');

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;

// Запустите Selenium и ChromeDriver перед выполнением этого скрипта
$host = 'http://localhost:4444'; // адрес сервера Selenium
$capabilities = DesiredCapabilities::chrome();

// Создаем экземпляр headless Chrome
$driver = RemoteWebDriver::create($host, $capabilities);

// Загружаем страницу
$url = 'https://alstrive.ru/about'; // замените на нужный адрес
$driver->get($url);

// Ждем, пока JS отработает и загрузит контент
$driver->wait(10)->until(
    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('your-css-selector')) // замените на нужный селектор
);

// Получаем HTML-код страницы
$html = $driver->getPageSource();

// Выводим HTML-код на экран или сохраняем его для дальнейшей обработки
echo $html;

// Закрываем браузер и завершаем работу с драйвером
$driver->quit();
