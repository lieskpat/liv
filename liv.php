<?php

require_once 'FileLogger.php';
require_once 'FileConnection.php';
require_once 'FileOperation.php';
require_once 'ExchangeConnection.php';
require_once 'ExchangeMailBox.php';
require_once 'SearchDrsNumberStrategy.php';

$fileConnection = new FileConnection('liv.log', 'a');
$logger = new FileLogger(new FileOperation($fileConnection));
$exchangeConnection = new ExchangeConnection(
    '{exchange3.lt.lsa-net.de:993/imap/ssl/novalidate-cert}INBOX'
    , 'lt\liv60'
    , 'liv60');
$exchangeConnection->addObserver($logger);
$exchangeMailBox = new ExchangeMailBox($exchangeConnection, new SearchDrsNumberStrategy());
$exchangeMailBox->addObserver($logger);
$drsNumberArray = $exchangeMailBox->getAllSearchStringsFromAllMailBodyText(
    '/[0-9]{1,4}\/[0-9]{1,3}\(?[a-zA-Z]*\)?/'
    , 1514761200);
foreach ($drsNumberArray as $drsNumber) {
    echo $drsNumber . "\n";
}
$exchangeConnection->closeConnection();
$fileConnection->closeConnection();

