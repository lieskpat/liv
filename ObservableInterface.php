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
interface ObservableInterface {
    
    public function addObserver($observer);
    
    public function deleteObserver($observer);
    
    public function notify();
}
