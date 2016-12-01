<?php
namespace pianificatore_ricette;
/**
 * Description of Pasto
 *
 * @author Alex
 */
class Pasto {
    private $ID;
    private $idTipologiaPasto;
    //attributo esterno
    private $ricette;
    
    function __construct() {
        
    }
    
    function getID() {
        return $this->ID;
    }

    function getIdTipologiaPasto() {
        return $this->idTipologiaPasto;
    }

    function getRicette() {
        return $this->ricette;
    }

    function setID($ID) {
        $this->ID = $ID;
    }

    function setIdTipologiaPasto($idTipologiaPasto) {
        $this->idTipologiaPasto = $idTipologiaPasto;
    }

    function setRicette($ricette) {
        $this->ricette = $ricette;
    }


}
