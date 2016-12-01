<?php
namespace pianificatore_ricette;
/**
 * Description of Preparazione
 *
 * @author Alex
 */
class Preparazione {
    private $ID;
    private $idIngrediente;
    private $giorniAnticipo;
    private $descrizione;
    
    function __construct() {
        
    }
    
    function getID() {
        return $this->ID;
    }

    function getIdIngrediente() {
        return $this->idIngrediente;
    }

    function getGiorniAnticipo() {
        return $this->giorniAnticipo;
    }

    function getDescrizione() {
        return $this->descrizione;
    }

    function setID($ID) {
        $this->ID = $ID;
    }

    function setIdIngrediente($idIngrediente) {
        $this->idIngrediente = $idIngrediente;
    }

    function setGiorniAnticipo($giorniAnticipo) {
        $this->giorniAnticipo = $giorniAnticipo;
    }

    function setDescrizione($descrizione) {
        $this->descrizione = $descrizione;
    }



}
