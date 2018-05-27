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
    
    function getNewestMailFromMailBox() {
        
    }
    
    function getAllMailsFromMailBox() {
        
    }
    
    function getMailBodyTextFromMail($mail) {
        return $bodyText;
    }
    
    /**
     * 
     * @param string $pattern
     * @return array
     */
    function getAllSearchStringsFromAllMailBodyText(string $pattern) {
        $searchResult = array();
        foreach ($this->getAllMailsFromMailBox() as $mail) {
            $searchResult[] = $this->getSearchString($pattern
                    , $this->getMailBodyTextFromMail($mail));
        }
        return $searchResult;
    }
    
    /**
     * 
     * @param string $pattern
     * @param string $subject
     * @return string the string to search
     */
    function getSearchString(string $pattern, string $subject) {
        return $this->searchBehavior->searchStrategy($pattern, $subject);
    }

}
