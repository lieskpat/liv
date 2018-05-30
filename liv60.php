<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$exchangeConnection = new ExchangeConnection('{exchange3.lt.lsa-net.de:993/imap/ssl/novalidate-cert}INBOX'
    , 'lt\liv60'
    , 'liv60');
$exchangeMailBox = new ExchangeMailBox($exchangeConnection, new SearchDrsNumberStrategy());
$drsNumberArray = $exchangeMailBox->getAllSearchStringsFromAllMailBodyText($pattern);
foreach ($drsNumberArray as $drsNumber) {
    echo '$drsNumber';
}


