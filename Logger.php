<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Logger
 *
 * @author Lieske
 */
class Logger {
    
    private $fileOperation;
    
    /**
     * 
     * @param type $fileOperation
     */
    function __construct($fileOperation) {
        $this->fileOperation = $fileOperation;
    }
    
    /**
     * 
     * @return type
     */
    function getFileOperation() {
        return $this->fileOperation;
    }

    /**
     * 
     * @param String $string
     */
    function writeInLogFile($string) {
        $this->fileOperation->write($string);
        return $this;
    }

}
