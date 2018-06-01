<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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