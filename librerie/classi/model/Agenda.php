<?php

/**
 * Description of Agenda
 *
 * @author Alex
 */
class Agenda {
    private $ID;
    private $settimana;
    private $idUtente;
    
    //attributo esterno
    private $giorni;
    
    function __construct() {
        
    }
    
    function getID() {
        return $this->ID;
    }

    function getSettimana() {
        return $this->settimana;
    }
    
    function getGiorni() {
        return $this->giorni;
    }

    function setID($ID) {
        $this->ID = $ID;
    }

    function setSettimana($settimana) {
        $this->settimana = $settimana;
    }

    function setGiorni($giorni) {
        $this->giorni = $giorni;
    }

    function getIdUtente() {
        return $this->idUtente;
    }

    function setIdUtente($idUtente) {
        $this->idUtente = $idUtente;
    }



}
