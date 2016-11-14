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
    private $id_tipologia;
    private $id_utente;
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

    function getId_tipologia() {
        return $this->id_tipologia;
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

    function setId_tipologia($id_tipologia) {
        $this->id_tipologia = $id_tipologia;
    }

    function getId_utente() {
        return $this->id_utente;
    }

    function getData() {
        return $this->data;
    }

    function setId_utente($id_utente) {
        $this->id_utente = $id_utente;
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
