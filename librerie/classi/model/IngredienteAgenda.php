<?php

/**
 * Description of IngredienteAgenda
 * Classe generata per la gestione degli ingredienti all'interno del calendario
 * @author Alex
 */
class IngredienteAgenda {
    private $nome;
    private $qt;
    private $um;
    private $dose;
    
    function __construct() {
        
    }
    
    function getNome() {
        return $this->nome;
    }

    function getQt() {
        return $this->qt;
    }

    function getUm() {
        return $this->um;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setQt($qt) {
        $this->qt = $qt;
    }

    function setUm($um) {
        $this->um = $um;
    }

    function getDose() {
        return $this->dose;
    }

    function setDose($dose) {
        $this->dose = $dose;
    }



}
