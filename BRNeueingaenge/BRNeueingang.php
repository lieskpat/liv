<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BRNeueingang
 *
 * @author Lieske
 */
class BRNeueingang {

    private $title;
    private $link;
    private $pubDate;
    private $author;
    private $drsNumber;

    /**
     * 
     * @param type $title
     * @param type $link
     * @param type $pubDate
     * @param type $author
     */
    function __construct($title, $link, $pubDate, $author) {
        $this->title = $title;
        $this->link = $link;
        $this->pubDate = $pubDate;
        $this->author = $author;
        $this->drsNumber = $this->getDrsNumberFromTitle(
            '/[0-9]{1,4}\/[0-9]{1,3}\(?[a-zA-Z]*\)?/'
            , $this->getTitle());
    }

    /**
     * 
     * @return type
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * 
     * @return type
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * 
     * @return type
     */
    public function getPubDate() {
        return $this->pubDate;
    }

    /**
     * 
     * @return type
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * 
     * @param type $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * 
     * @param type $link
     */
    public function setLink($link) {
        $this->link = $link;
    }

    /**
     * 
     * @param type $pubDate
     */
    public function setPubDate($pubDate) {
        $this->pubDate = $pubDate;
    }

    /**
     * 
     * @param type $author
     */
    public function setAuthor($author) {
        $this->author = $author;
    }

    /**
     * 
     * @return type
     */
    public function getDrsNumber() {
        return $this->drsNumber;
    }

    /**
     * 
     * @param type $drsNumber
     */
    public function setDrsNumber($drsNumber) {
        $this->drsNumber = $drsNumber;
    }

    /**
     * 
     * @param type $pattern
     * @param type $subject
     * @return type
     */
    private function getDrsNumberFromTitle($pattern, $subject) {
        preg_match($pattern, $subject, $match);
        return $match[0];
    }

    /**
     * 
     */
    public function toString() {
        echo 'Titel: ' . ' ' . $this->getTitle() . "\n";
        echo 'Link: ' . ' ' . $this->getLink() . "\n";
        echo 'PubDate: ' . ' ' . $this->getPubDate() . "\n";
        echo 'Author: ' . ' ' . $this->getAuthor() . "\n";
        echo 'DRS: ' . ' ' . $this->getDrsNumber() . "\n";
    }

}
