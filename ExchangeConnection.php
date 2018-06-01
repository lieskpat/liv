<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'Connection.php';

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