<?php

    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    require_once('/var/www/zchk-app/vendor/autoload.php');

use ChromePhp\Chrome;
use HeadlessChromium\BrowserFactory;

$url = 'https://atomscan.com/accounts/cosmos164dpffm974ayj7j9r33t4yhv7a6wu59q6d2c6k'; // Замените на URL-адрес сайта, который вам нужен

$browserFactory = new BrowserFactory();

// Создайте экземпляр браузера
$browser = $browserFactory->createBrowser([
    'windowSize' => [100, 100],
    'noSandbox' => true,
    'additionalArguments' => ['--disable-dev-shm-usage', '--no-sandbox'],
]);

try {
    // Создайте новую вкладку
    $page = $browser->createPage();

    // Загрузите страницу
    $page->navigate($url)->waitForNavigation();

    // Выполните JavaScript-код, если необходимо (опционально)
    // $page->evaluate('document.querySelector("button").click();');
    // $page->waitForNavigation();

    // Получите сгенерированный HTML
    $html = $page->getHtml();

    // Выведите HTML-код
    echo $html;
} finally {
    // Закройте браузер
    $browser->close();
}

?>