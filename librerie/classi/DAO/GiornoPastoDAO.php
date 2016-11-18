<?php

/**
 * Description of GiornoPastoDAO
 *
 * @author Alex
 */
class GiornoPastoDAO extends ObjectDAO {
    
    //costruttore
    function __construct() {
        global $DB_TABLE_GIORNI_PASTI;
        parent::__construct($DB_TABLE_GIORNI_PASTI);
    }
    
    /**
     * La funzione salva nel database un associazione giorno pasto
     * @param type $idGiorno
     * @param type $idPasto
     * @return type
     */
    public function saveGiornoPasto($idGiorno, $idPasto){
        $campi = array(
            'id_giorno' => $idGiorno,
            'id_pasto'  => $idPasto
        );
        $formato = array('%d', '%d');
        return parent::saveObject($campi, $formato);        
    }
    
    /**
     * La funzione restituisce un array di idGiorno e idPasto
     * @param type $where
     * @return array
     */
    public function getGiorniPasti($where){
        $result = null;
        $temp = parent::getObjects(null, $where);
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $value['idGiorno'] = $item->id_giorno;
                $value['idPasto'] = $item->id_pasto;
                array_push($result, $value);
            }
        }
        return $result;
    }
    
    /**
     * La funzione elimina dal database istanze di giorno pasto
     * @param type $array
     * @return type
     */
    public function deleteGiorniPasti($array){
        return parent::deleteObject($array);
    }

}
