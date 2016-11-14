<?php


/**
 * Description of Ingrediente
 *
 * @author Alex
 */
class Ingrediente {
    private $ID;
    private $nome;
    
    //elemento esterno --> array di oggetti preparazione
    private $preparazioni;
    
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
    
    function getPreparazioni() {
        return $this->preparazioni;
    }

    function setPreparazioni($preparazioni) {
        $this->preparazioni = $preparazioni;
    }




    
}

