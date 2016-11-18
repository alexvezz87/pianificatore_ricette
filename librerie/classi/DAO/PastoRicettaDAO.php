<?php

/**
 * Description of PastoRicettaDAO
 *
 * @author Alex
 */
class PastoRicettaDAO extends ObjectDAO{
    
    //costruttore
    function __construct() {
        global $DB_TABLE_PASTI_RICETTE;
        parent::__construct($DB_TABLE_PASTI_RICETTE);
    }

    /**
     * La funzione salva un associazione di pasto riccetta nel database
     * @param type $idPasto
     * @param type $idRicetta
     * @return type
     */
    public function savePastoRicetta($idPasto, $idRicetta){
        $campi = array(
            'id_pasto'      => $idPasto,
            'id_ricetta'    => $idRicetta
        );
        $formato = array('%d', '%d');
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * La funzione restituisce un array di idPasto e idRicetta
     * @param type $where
     * @return array
     */
    public function getPastiRicette($where){
        $result = null;
        $temp = parent::getObjects(null, $where);
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){ 
                $value['idPasto'] = $item->id_pasto;
                $value['idRicetta'] = $item->id_ricetta;
                array_push($result, $value);                
            }
        }
        return $result;
    }
    
    /**
     * La funzione elimina dal database istanze di pasto ricette
     * @param type $array
     * @return type
     */
    public function deletePastiRicette($array){
        return parent::deleteObject($array);
    }
}
