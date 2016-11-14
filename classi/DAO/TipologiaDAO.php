<?php

/**
 * Description of TipologiaDAO
 *
 * @author Alex
 */
class TipologiaDAO extends ObjectDAO {
    
    //costruttore
    function __construct() {
        global $DB_TABLE_TIPOLOGIE;
        parent::__construct($DB_TABLE_TIPOLOGIE);
    }
    
    /**
     * La funzione salva una tipologia nel database
     * @param Tipologia $t
     * @return type
     */
    public function saveTipologia(Tipologia $t){
        $campi = array(
            'nome'          => $t->getNome(),
            'descrizione'   => $t->getDescrizione()
        );
        $formato = array('%s', '%s');
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * La funzione restituisce un array di oggetti tipologia 
     * @return array
     */
    public function getTipologie($where = null){
        $result = null;
        $temp = parent::getObjects(null, $where);
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $t = new Tipologia();
                $t->setID($item->ID);
                $t->setNome($item->nome);
                $t->setDescrizione($item->descrizione);
                array_push($result, $t);
            }
        }
        return $result;
    }
    
    /**
     * La funzione aggiorna una tipologia nel database
     * @param Tipologia $t
     * @return type
     */
    public function updateTipologia(Tipologia $t){
        $update = array(
            'nome'          => $t->getNome(),
            'descrizione'   => $t->getDescrizione()
        );
        $formatUpdate = array('%s', '%s');
        $where = array('ID' => $t->getID());
        $formatWhere = array('%d');
        return parent::updateObject($update, $formatUpdate, $where, $formatWhere);
    }
    
    /**
     * La funzione cancella una tipologia dal database
     * @param type $ID
     * @return type
     */
    public function deleteTipologiaByID($ID){
        return parent::deleteObjectByID($ID);
    }

}
