<?php
namespace pianificatore_ricette;
/**
 * Description of GiornoAgenda
 * Classe specifica per la creazione dei giorni nel pdf
 *
 * @author Alex
 */
class GiornoAgenda {
    private $data;
    private $nomeGiorno;
    private $tipo;
    private $descrizione;
    
    function __construct() {
        
    }
    
    function getData() {
        return $this->data;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getDescrizione() {
        return $this->descrizione;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setDescrizione($descrizione) {
        $this->descrizione = $descrizione;
    }
    
    function getNomeGiorno() {
        return $this->nomeGiorno;
    }

    function setNomeGiorno($nomeGiorno) {
        $this->nomeGiorno = $nomeGiorno;
    }

    public function __toString() {
        return $this->data.' '.$this->nomeGiorno.' '.$this->tipo.' '.$this->descrizione;
    }



}
