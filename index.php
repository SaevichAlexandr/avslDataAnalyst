<?php


// An example of using php-webdriver.
// Do not forget to run composer install before. You must also have Selenium server started and listening on port 4444.

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Exception;

require_once('vendor/autoload.php');

$time_pre = microtime(true);
// starting selenium server
// java -Dwebdriver.gecko.driver="geckodriver.exe" -jar selenium-server-standalone-3.141.59.jar
// This is where Selenium server 2/3 listens by default. For Selenium 4, Chromedriver or Geckodriver, use http://localhost:4444/
// for geckodriver on selenium server
$host = 'http://localhost:4444/wd/hub';
// for clear geckodriver
//$host = 'http://localhost:4444';
// for clear chromedriver
//$host = 'http://localhost:9515';

$capabilities = DesiredCapabilities::firefox();
// включение "безголового" режима
$capabilities->setCapability('moz:firefoxOptions', ['args' => ['-headless']]);

$driver = RemoteWebDriver::create($host, $capabilities);

$departurePoint = 'AER';
$arrivalPoint = 'MMK';
$firstDepartureDay = '05';
$firstDepartureMonth = '06';
$secondDepartureDay = null;
$secondDepartureMonth = null;
/**
 * Возможные значения для $reservationClass:
 * '' - эконом;
 * 'w' - комфорт;
 * 'c' - бизнес;
 * 'f' - первый класс.
 */
$reservationClass = '';
$adults = 1;
$children = 1;
$infants = 1;

// TODO: хорошая идея сохранять сырые данные, а потом уже их обрабатывать. Таким образом не будет задержки у парсера страницы.

$continueButtonClicks = 10;

$driver->get('https://www.aviasales.ru/search/'.
    $departurePoint.
    $firstDepartureDay.
    $firstDepartureMonth.
    $arrivalPoint.
    $secondDepartureDay.
    $secondDepartureMonth.
    $reservationClass.
    $adults.
    $children.
    $infants.
    '?back=true'
);

$driver->findElement(WebDriverBy::className('theme-switcher'))->click();

$driver->wait()->until(WebDriverExpectedCondition::not(WebDriverExpectedCondition::titleIs(
    $firstDepartureDay.
    '.'.
    $firstDepartureMonth.
    ', '.
    $departurePoint.
    ' → '.
    $arrivalPoint
)));

$showMoreButton = $driver->findElement(WebDriverBy::cssSelector('div.show-more-products > button'));

$continueButtonClicks = showMoreResults(
    $continueButtonClicks,
    $showMoreButton
);

// проверка на совпадение количества элементов возвращаемых при поиске с количеством элементов загруженных в браузере
// TODO: тут пизда, надо придумать что-то для случая если элементов больше на число отличное от 10
$driver->wait()->until(
    function () use ($driver, $continueButtonClicks)
    {
        $listElements = $driver->findElements(WebDriverBy::cssSelector('div.fade-enter-done'));

        return count($listElements) >= ($continueButtonClicks + 1) * 10;
    },
    'Count of listElements must be another'
);

sleep(5);

// почему-то через этот класс у div всё работает
$listElements = $driver->findElements(WebDriverBy::cssSelector('div.fade-enter-done'));
echo 'listElements_third = ';
var_dump(count($listElements));


foreach ($listElements as $element)
{
    $element->click();
}

// TODO: стоит подумать, возможно стоит брать данные по кускам (отдельно цены, отдельно инфа о рейсе)
$dataArray = $driver->findElements(WebDriverBy::className('ticket-desktop'));

for ($i = 1; $i < count($dataArray); $i++)
{
    echo $i.".".$dataArray[$i]->getText();
    echo '<br><br>';
}

/**
 * Функция возвращает количество совершенных нажатий на кнопку "Показать еще 10 результатов"
 *
 * @param int $continueButtonClicks
 * @param RemoteWebElement $driverItem
 * @return int
 */
function showMoreResults(int $continueButtonClicks, RemoteWebElement $driverItem): int
{
    for ($i = 0; $i < $continueButtonClicks; $i++)
    {
        try {
            $driverItem->click();
        }
        catch (Exception $ex) {
            echo 'No more show-more buttons!';
            return --$i;
        }
    }
    return $continueButtonClicks;
}

$time_post = microtime(true);

echo 'Execution time = '.($time_post - $time_pre);

$driver->quit();