<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace pianificatore_ricette;

class TemplateAgenda {
    private $ID;
    private $idAgenda;
    private $descrizione;
    private $nome;
    private $inizio;
    private $fine;
    
    function __construct() {
        
    }

    function getID() {
        return $this->ID;
    }

    function getDescrizione() {
        return $this->descrizione;
    }

    function setID($ID) {
        $this->ID = $ID;
    }
    
    function setDescrizione($descrizione) {
        $this->descrizione = $descrizione;
    }

    function getIdAgenda() {
        return $this->idAgenda;
    }

    function setIdAgenda($idAgenda) {
        $this->idAgenda = $idAgenda;
    }

    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getInizio() {
        return $this->inizio;
    }

    function getFine() {
        return $this->fine;
    }

    function setInizio($inizio) {
        $this->inizio = $inizio;
    }

    function setFine($fine) {
        $this->fine = $fine;
    }



    
}
