<?php

/**
 * Description of PreferiteDAO
 *
 * @author Alex
 */
class PreferiteDAO extends ObjectDAO {
    
    function __construct() {
        global $DB_TABLE_PREFERITE;
        parent::__construct($DB_TABLE_PREFERITE);
    }
    
    /**
     * La funzione salva una ricetta come preferita per un determinato utente
     * @param type $idUtente
     * @param type $idRicetta
     * @return type
     */
    public function savePreferita($idUtente, $idRicetta){
        $campi = array(
            'id_ricetta' => $idRicetta,
            'id_utente'  => $idUtente
        );
        $formato = array('%d', '%d');
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * La funzione restituisce un array di idRicetta/idUtente
     * @param type $where
     * @return array
     */
    public function getPreferite($where){
        $result = null;
        $temp = parent::getObjects(null, $where);
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $value['idRicetta'] = $item->id_ricetta;
                $value['idUtente'] = $item->id_utente;
                array_push($result, $value);
            }
        }
        return $result;
    }
    
    
    public function deletePreferite($array){
        return parent::deleteObject($array);
    }

}
