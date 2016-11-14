<?php

/**
 * Description of Tipologia
 *
 * @author Alex
 */
class Tipologia {
    private $ID;
    private $nome;
    private $descrizione;
    
    function __construct() {
        
    }

    function getID() {
        return $this->ID;
    }

    function getNome() {
        return $this->nome;
    }

    function setID($ID) {
        $this->ID = $ID;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }
    
    function getDescrizione() {
        return $this->descrizione;
    }

    function setDescrizione($descrizione) {
        $this->descrizione = $descrizione;
    }



}
