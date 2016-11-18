<?php

/**
 * Description of IngredienteRicetta
 * Classe di supporto per indicare la quantità di ingredienti con unità di misura 
 * presenti in una ricetta
 *
 * @author Alex
 */
class IngredienteRicetta {
    private $ID;
    private $idIngrediente;
    private $idRicetta;
    private $quantita;
    private $unitaMisura;
    
    function __construct() {
        
    }
    
    function getIdIngrediente() {
        return $this->idIngrediente;
    }

    function getIdRicetta() {
        return $this->idRicetta;
    }

    function getQuantita() {
        return $this->quantita;
    }

    function getUnitaMisura() {
        return $this->unitaMisura;
    }

    function setIdIngrediente($idIngrediente) {
        $this->idIngrediente = $idIngrediente;
    }

    function setIdRicetta($idRicetta) {
        $this->idRicetta = $idRicetta;
    }

    function setQuantita($quantita) {
        $this->quantita = $quantita;
    }

    function setUnitaMisura($unitaMisura) {
        $this->unitaMisura = $unitaMisura;
    }
    
    function getID() {
        return $this->ID;
    }

    function setID($ID) {
        $this->ID = $ID;
    }




}
