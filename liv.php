<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author lies
 */
interface Connection {

    public function openConnection();

    public function closeConnection();
}

/**
 * Description of ExchangeConnection
 *
 * @author lies
 */
class ExchangeConnection implements Connection {

    private $imapStream;
    private $connectionAddress;
    private $userName;
    private $password;

    function __construct($connectionAddress, $userName, $password) {
        $this->connectionAddress = $connectionAddress;
        $this->userName = $userName;
        $this->password = $password;
    }

    function getImapStream() {
        return $this->imapStream;
    }

    function getConnectionAddress() {
        return $this->connectionAddress;
    }

    function getUserName() {
        return $this->userName;
    }

    function getPassword() {
        return $this->password;
    }

    function setConnectionAddress($connectionAddress) {
        $this->connectionAddress = $connectionAddress;
    }

    function setUserName($userName) {
        $this->userName = $userName;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    public function openConnection() {
        //hier muss exception Abfrage rein
        $this->imapStream = imap_open($this->getConnectionAddress()
            , $this->getUserName()
            , $this->getPassword());
    }

    public function closeConnection() {
        imap_close($this->getImapStream());
    }

}

/**
 * Description of ExchangeMailBox
 *
 * @author lies
 */
class ExchangeMailBox {

    private $exchangeConnection;
    private $searchBehavior;
    private $imapStream;

    function __construct($exchangeConnection, $searchBehavior) {
        $this->exchangeConnection = $exchangeConnection;
        $this->searchBehavior = $searchBehavior;
        $this->exchangeConnection->openConnection();
        $this->imapStream = $this->exchangeConnection->getImapStream();
    }

    function getExchangeConnection() {
        return $this->exchangeConnection;
    }

    function getSearchBehavior() {
        return $this->searchBehavior;
    }

    function setExchangeConnection($exchangeConnection) {
        $this->exchangeConnection = $exchangeConnection;
    }

    function setSearchBehavior($searchBehavior) {
        $this->searchBehavior = $searchBehavior;
    }

    /**
     * 
     * @return array
     */
    function getAllMailBodysFromMailBox() {
        $mailBodyArray = array();
        for ($index = 0; $index < imap_num_msg($this->imapStream); $index++) {
            $mailBodyArray[] = imap_body($this->imapStream, $index);
        }
        return $mailBodyArray;
    }

    /**
     * 
     * @param string $fromDate
     */
    function getAllMailBodysFromMailBoxFromMailSendingDate($fromDate) {
        $mailBodyArray = array();
        for ($index = 1; $index <= $this->getAmountOfAllMessagesFromMailBox(); $index++) {
            $headerObject = imap_header($this->imapStream, $index);
            if ($headerObject && (strtotime($headerObject->date) > $fromDate)) {
                $mailBodyArray[] = imap_body($this->imapStream, $index);
            }
        }
        return $mailBodyArray;
    }

    /**
     * 
     * @param timestamp $fromDate
     */
    function printAllMailBodysFromMailBox($fromDate) {
        foreach ($this->getAllMailBodysFromMailBoxFromMailSendingDate($fromDate) as $mailBody) {
            echo $mailBody;
        }
    }

    function getAmountOfAllMessagesFromMailBox() {
        return imap_num_msg($this->imapStream);
    }

    /**
     * 
     * @param string $pattern
     * @param string $fromDate
     * @return array
     */
    function getAllSearchStringsFromAllMailBodyText($pattern, $fromDate) {
        $searchResult = array();
        foreach ($this->getAllMailBodysFromMailBoxFromMailSendingDate($fromDate) as $mailBody) {
            if ($this->getSearchString($pattern, $mailBody) != "") {
                $searchResult[] = $this->getSearchString($pattern, $mailBody);
            }
        }
        return $searchResult;
    }

    /**
     * 
     * @param string $pattern
     * @param string $subject
     * @return string the string to search
     */
    function getSearchString($pattern, $subject) {
        return $this->searchBehavior->searchStrategy($pattern, $subject);
    }

}

/**
 *
 * @author lies
 */
interface SearchBehavior {

    public function searchStrategy($pattern, $subject);
}

/**
 * Description of SearchDrsNumber
 *
 * @author lies
 */
class SearchDrsNumberStrategy implements SearchBehavior {

    /**
     * 
     * @param string $pattern to search a DRS-Number
     * @param string $subject
     * @return string
     */
    public function searchStrategy($pattern, $subject) {
        if (preg_match($pattern, $subject, $matches)) {
            return $matches[0];
        }
    }

}

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

