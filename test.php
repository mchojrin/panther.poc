<?php

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Symfony\Component\Panther\Client;

require __DIR__.'/vendor/autoload.php'; // Composer's autoloader

if ($argc < 2) {
	die("The url needs to be specified");
}

if ($argc < 3) {
	$argv[2] = "chrome";
}

if ($argv[2] == "chrome") {
	echo "Creating Chrome Client".PHP_EOL;
	$client = Client::createChromeClient();
} elseif ($argv[2] == "firefox") {
	echo "Creating FireFox Client".PHP_EOL;
	$client = Client::createFirefoxClient();
} else {
	die("Only 'chrome' and 'firefox' are available at the moment".PHP_EOL);
}

$url = $argv[1]; 

echo "Getting Mantis home page from '$url'".PHP_EOL;
try {
	$crawler = $client->request('GET', $url);
} catch (Exception $e){
	die("Couldn't load the homepage".PHP_EOL);
}
echo "Got the home page. Waiting for the form to appear...".PHP_EOL;
try {
    $crawler = $client->waitFor('#username');
} catch (NoSuchElementException|TimeoutException $e) {
    $client->takeScreenshot("username-error.png");
    echo "Screenshot taken at ".__DIR__.DIRECTORY_SEPARATOR."username-error.png".PHP_EOL;
    die("No form found :(".PHP_EOL);
}
echo "Got the form!".PHP_EOL;
$client->takeScreenshot("login.png");
echo "Screenshot taken at ".__DIR__.DIRECTORY_SEPARATOR."login.png".PHP_EOL;
$form = $crawler->filter('input[type="submit"]')->form();
$form['username'] = 'administrator';
echo "Submitting the form...".PHP_EOL;
$client->submit($form);
echo "Waiting for next page...".PHP_EOL;
try {
    $crawler = $client->waitFor('#password');
} catch (NoSuchElementException|TimeoutException $e) {
    $client->takeScreenshot("password-error.png");
    echo "Screenshot taken at ".__DIR__.DIRECTORY_SEPARATOR."password-error.png".PHP_EOL;
    die("No form found :(".PHP_EOL);
}
echo "Got the next page. Filling the form".PHP_EOL;
$form = $crawler->filter('input[type="submit"]')->form();
$form['password'] = 'root';
echo "Filled the password, submitting".PHP_EOL;
$client->submit($form);
echo "Waiting for logged in homepage".PHP_EOL;
try {
    $crawler = $client->waitFor('div.page-content');
} catch (NoSuchElementException|TimeoutException $e) {
    $client->takeScreenshot("logged-in-error.png");
    echo "Screenshot taken at ".__DIR__.DIRECTORY_SEPARATOR."logged-in-error.png".PHP_EOL;
    die("Logged in homepage failed to load".PHP_EOL);
}
echo "Got the logged in homepage.".PHP_EOL;
$alerts = $crawler->filter('div.alert-danger')->count();
echo "$alerts alerts found".PHP_EOL;

if ($alerts > 0) {
    echo "Correct!".PHP_EOL;
} else {
    $client->takeScreenshot("no-alerts-error.png");
    echo "Screenshot taken at ".__DIR__.DIRECTORY_SEPARATOR."no-alerts-error.png".PHP_EOL;
    echo "Where's my alert??".PHP_EOL;
}

