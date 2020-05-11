<?php


// An example of using php-webdriver.
// Do not forget to run composer install before. You must also have Selenium server started and listening on port 4444.

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

require_once('vendor/autoload.php');

// This is where Selenium server 2/3 listens by default. For Selenium 4, Chromedriver or Geckodriver, use http://localhost:4444/
$host = 'http://localhost:9515';

$capabilities = DesiredCapabilities::chrome();

$driver = RemoteWebDriver::create($host, $capabilities);

$departurePoint = 'MOW';
$arrivalPoint = 'LED';



$driver->get('https://www.aviasales.ru/search/AER0506BAX1?back=true');

/*
 *  TODO: нужно заменить конкретный текст заголовка на regex
 */


$pattern = "| ";

$driver->wait()->until(WebDriverExpectedCondition::titleIs('₴2 863 | 05.06, AER → BAX'));

$driver->findElement(WebDriverBy::className('locale-selector'))->click();

$driver->findElement(WebDriverBy::className('select-list__item'))->click();

// TODO: стоит подумать, возможно стоит брать данные по кускам (отдельно цены, отдельно инфа о рейсе)
$dataArray = $driver->findElements(WebDriverBy::className('ticket-desktop'));

foreach ($dataArray as $item)
{
    echo $item->getText();
    echo '<br>';
}

//$driver->quit();

//$driver->wait()->until(
//    WebDriverExpectedCondition::
//);
//
//$driver->findElement(WebDriverBy::id('origin'))
//    ->clear();
//$driver->findElement(WebDriverBy::id('origin'))
//    ->sendKeys('MOW');
//
//$driver->findElement(WebDriverBy::id('destination'))
//    ->clear();
//$driver->findElement(WebDriverBy::id('destination'))
//    ->sendKeys('LED');

//$driver->findElement(WebDriverBy::className('trip-duration__input-wrapper --departure'))->click();

//$capabilities = DesiredCapabilities::chrome();
//
//$driver = RemoteWebDriver::create($host, $capabilities);
//
//// navigate to Selenium page on Wikipedia
//$driver->get('https://www.aviasales.ru/');
//
//// write 'PHP' in the search box
//$driver->findElement(WebDriverBy::id('searchInput')) // find search input element
//->sendKeys('PHP') // fill the search box
//->submit(); // submit the whole form
//
//// wait until 'PHP' is shown in the page heading element
//$driver->wait()->until(
//    WebDriverExpectedCondition::elementTextContains(WebDriverBy::id('firstHeading'), 'PHP')
//);
//
//// print title of the current page to output
//echo "The title is '" . $driver->getTitle() . "'\n";
//
//// print URL of current page to output
//echo "The current URL is '" . $driver->getCurrentURL() . "'\n";
//
//// find element of 'History' item in menu
//$historyButton = $driver->findElement(
//    WebDriverBy::className('footer')
//);
//
//// read text of the element and print it to output
//echo "About to click to button with text: '" . $historyButton->getText() . "'\n";
//
//// click the element to navigate to revision history page
//$historyButton->click();
//
//// wait until the target page is loaded
//$driver->wait()->until(
//    WebDriverExpectedCondition::titleContains('Revision history')
//);
//
//// print the title of the current page
//echo "The title is '" . $driver->getTitle() . "'\n";
//
//// print the URI of the current page
//
//echo "The current URI is '" . $driver->getCurrentURL() . "'\n";
//
//// delete all cookies
//$driver->manage()->deleteAllCookies();
//
//// add new cookie
//$cookie = new Cookie('cookie_set_by_selenium', 'cookie_value');
//$driver->manage()->addCookie($cookie);
//
//// dump current cookies to output
//$cookies = $driver->manage()->getCookies();
//print_r($cookies);
//
//// close the browser
//$driver->quit();