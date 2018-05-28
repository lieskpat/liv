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
     * @param string $pattern
     * @return array
     */
    function getAllSearchStringsFromAllMailBodyText($pattern) {
        $searchResult = array();
        foreach ($this->getAllMailBodysFromMailBox() as $mailBody) {
            $searchResult[] = $this->getSearchString($pattern
                , $mailBody);
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
