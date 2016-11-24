<?php

/**
 * Description of PastoDAO
 *
 * @author Alex
 */
class PastoDAO extends ObjectDAO {
    
    //costruttore
    function __construct() {
        global $DB_TABLE_PASTI;
        parent::__construct($DB_TABLE_PASTI);
    }
    
    /**
     * La funzione salva un pasto nel database
     * @param Pasto $p
     * @return type
     */
    public function savePasto(Pasto $p){
        $campi = array(
            'id_tipologia' => $p->getIdTipologiaPasto()
        );
        $formato = array('%d');
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * La funzione restituisce un array di oggetti pasto
     * @param type $where
     * @return array
     */
    public function getPasti($where){
        $result = null;
        $temp = parent::getObjects(null, $where);
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $p = new Pasto();
                $p->setID($item->ID);
                $p->setIdTipologiaPasto($item->id_tipologia);
                array_push($result, $p);                
            }
        }
        return $result;
    }
    
    
    /**
     * La funzione cancella un pasto dal database
     * @param type $ID
     * @return type
     */
    public function deletePastoByID($ID){
        return parent::deleteObjectByID($ID);
    }
    
    

}
