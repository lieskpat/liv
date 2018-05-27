<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$exchangeConnection = new ExchangeConnection($connectionAddress, $userName, $password);
$searchBehavior = new SearchDrsNumberStrategy();
$exchangeMailBox = new ExchangeMailBox($exchangeConnection, $searchBehavior);
$drsNumberArray = $exchangeMailBox->getAllSearchStringsFromAllMailBodyText($pattern
        , $exchangeMailBox->getMailBodyText());


