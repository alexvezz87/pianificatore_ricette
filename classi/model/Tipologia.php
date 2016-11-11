<?php

/**
 * Description of Tipologia
 *
 * @author Alex
 */
class Tipologia {
    private $ID;
    private $nome;
    
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


}
