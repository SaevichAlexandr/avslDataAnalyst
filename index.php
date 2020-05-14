<?php


// An example of using php-webdriver.
// Do not forget to run composer install before. You must also have Selenium server started and listening on port 4444.

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

require_once('vendor/autoload.php');

// This is where Selenium server 2/3 listens by default. For Selenium 4, Chromedriver or Geckodriver, use http://localhost:4444/
// for geckodriver
$host = 'http://localhost:4444/wd/hub';
// for chromedriver
//$host = 'http://localhost:9515';

// starting selenium server
//java -Dwebdriver.gecko.driver="geckodriver.exe" -jar selenium-server-standalone-3.141.59.jar
$capabilities = DesiredCapabilities::firefox();

$driver = RemoteWebDriver::create($host, $capabilities);

$departurePoint = 'AER';
$arrivalPoint = 'MMK';
$firstDepartureDay = '05';
$firstDepartureMonth = '06';
$secondDepartureDay = null;
$secondDepartureMonth = null;
$adults = 1;
$children = null;
$infants = null;

$continueButtonPresses = 3;

//$driver->manage()->window()->maximize();
$driver->get('https://www.aviasales.ru/search/'.
    $departurePoint.
    $firstDepartureDay.
    $firstDepartureMonth.
    $arrivalPoint.
    $secondDepartureDay.
    $secondDepartureMonth.
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

for ($i = 0; $i < $continueButtonPresses; $i++)
{
    $driver->findElement(WebDriverBy::cssSelector('div.show-more-products > button'))->click();
}

$driver->wait()->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('div.product-list')));

// почему-то через этот класс у div всё работает
$listElements = $driver->findElements(WebDriverBy::cssSelector('div.fade-enter-done'));

foreach ($listElements as $element)
{
    $element->click();
}

// TODO: стоит подумать, возможно стоит брать данные по кускам (отдельно цены, отдельно инфа о рейсе)
$dataArray = $driver->findElements(WebDriverBy::className('ticket-desktop'));

foreach ($dataArray as $item)
{
    echo $item->getText();
    echo '<br>';
}
