<?php

/**
 * Description of Ricetta
 *
 * @author Alex
 */
class Ricetta {
    private $ID;
    private $nome;
    private $preparazione;
    private $durata;
    private $foto;
    private $idTipologia;
    private $idUtente;
    private $data;
    
    //attributo aggiunto 
    private $ingredienti; //array di ingredienti
    
    function __construct() {
        
    }
    
    function getID() {
        return $this->ID;
    }

    function getNome() {
        return $this->nome;
    }

    function getPreparazione() {
        return $this->preparazione;
    }

    function getDurata() {
        return $this->durata;
    }

    function getFoto() {
        return $this->foto;
    }

    function getIdTipologia() {
        return $this->idTipologia;
    }

    function setID($ID) {
        $this->ID = $ID;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setPreparazione($preparazione) {
        $this->preparazione = $preparazione;
    }

    function setDurata($durata) {
        $this->durata = $durata;
    }

    function setFoto($foto) {
        $this->foto = $foto;
    }

    function setIdTipologia($idTipologia) {
        $this->idTipologia = $idTipologia;
    }

    function getIdUtente() {
        return $this->idUtente;
    }

    function getData() {
        return $this->data;
    }

    function setIdUtente($idUtente) {
        $this->idUtente = $idUtente;
    }

    function setData($data) {
        $this->data = $data;
    }
    
    function getIngredienti() {
        return $this->ingredienti;
    }

    function setIngredienti($ingredienti) {
        $this->ingredienti = $ingredienti;
    }




}
