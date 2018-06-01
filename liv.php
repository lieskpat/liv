<?php

require_once 'Logger.php';
require_once 'FileConnection.php';
require_once 'FileOperation.php';
require_once 'ExchangeConnection.php';
require_once 'ExchangeMailBox.php';
require_once 'SearchDrsNumberStrategy.php';

$fileConnection = new FileConnection('liv.log', 'r+');
$logger = new Logger(new FileOperation($fileConnection));
$logger->writeInLogFile('************************************' . "\n")->writeInLogFile('BR_Neueingaenge MAIL-Verarbeitung' . "\n");
$exchangeConnection = new ExchangeConnection('{exchange3.lt.lsa-net.de:993/imap/ssl/novalidate-cert}INBOX'
    , 'lt\liv60'
    , 'liv60');
$exchangeMailBox = new ExchangeMailBox($exchangeConnection, new SearchDrsNumberStrategy());
//regEx 111/18(neu), 71/18(neu), 600/17(neu), 77/18(B), 23/18(B), 
$drsNumberArray = $exchangeMailBox->getAllSearchStringsFromAllMailBodyText(
    '/[1-9]{1,4}\/[1-9]{1,3}\(?[a-zA-Z]*\)?/'
    , 1514761200);
//$exchangeMailBox->printAllMailBodysFromMailBox(1514761200);
echo $exchangeMailBox->getAmountOfAllMessagesFromMailBox() . " Nachrichten im Posteingang" . "\n";
foreach ($drsNumberArray as $drsNumber) {
    echo $drsNumber . "\n";
}
$exchangeConnection->closeConnection();

