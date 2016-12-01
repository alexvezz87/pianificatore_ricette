<?php
namespace pianificatore_ricette;
/**
 * Description of Giorni
 *
 * @author Alex
 */
class Giorno {
    private $ID;
    private $nome;
    private $data;
    private $idAgenda;
    
    //attributo esterno
    private $pasti;
    
    function __construct() {
        
    }
    
    function getID() {
        return $this->ID;
    }

    function getNome() {
        return $this->nome;
    }

    function getData() {
        return $this->data;
    }

    
    function getPasti() {
        return $this->pasti;
    }

    function setID($ID) {
        $this->ID = $ID;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setData($data) {
        $this->data = $data;
    }
    
    function setPasti($pasti) {
        $this->pasti = $pasti;
    }

    function getIdAgenda() {
        return $this->idAgenda;
    }

    function setIdAgenda($idAgenda) {
        $this->idAgenda = $idAgenda;
    }




}
