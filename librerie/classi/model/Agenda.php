<?php
namespace pianificatore_ricette;
/**
 * Description of Agenda
 *
 * @author Alex
 */
class Agenda {
    private $ID;
    private $settimana;
    private $idUtente;
    private $data;
    private $pdf;
    
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

    function getData() {
        return $this->data;
    }

    function setData($data) {
        $this->data = $data;
    }

    function getPdf() {
        return $this->pdf;
    }

    function setPdf($pdf) {
        $this->pdf = $pdf;
    }



}
