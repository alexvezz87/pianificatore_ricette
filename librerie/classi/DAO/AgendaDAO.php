<?php

/**
 * Description of AgendaDAO
 *
 * @author Alex
 */
class AgendaDAO extends ObjectDAO {
   
    //costruttore
    function __construct() {
        global $DB_TABLE_AGENDE;
        parent::__construct($DB_TABLE_AGENDE);
    }
    
    /**
     * La funzione salva un agenda nel database
     * @param Agenda $a
     * @return type
     */
    public function saveAgenda(Agenda $a){
        $campi = array(           
            'settimana' => $a->getSettimana(),
            'id_utente' => $a->getIdUtente()            
        );       
        $formato = array('%d', '%d');
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * La funzione restituisce un array di agende
     * @param type $where
     * @return array
     */
    public function getAgende($where = null){
        $result = null;
        $temp = parent::getObjects(null, $where);
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $a = new Agenda();
                $a->setID($item->ID);
                $a->setSettimana($item->settimana);
                $a->setIdUtente($item->id_utente);
                array_push($result, $a);
            }
        }
        return $result;
    }
    
    /**
     * La funzione aggiorna un agenda ne database
     * @param Agenda $a
     * @return type
     */
    public function updateAgenda(Agenda $a){
        $update = array(
            'settimana' => $a->getSettimana(),
            'id_utente' => $a->getIdUtente()
        );
        $formatUpdate = array('%s', '%d');
        $where = array('ID' => $a->getID());
        $formatWhere = array('%d');
        return parent::updateObject($update, $formatUpdate, $where, $formatWhere);
    }
    
    /**
     * La funzione cancella un'agenda dal database
     * @param type $ID
     * @return type
     */
    public function deleteAgendaByID($ID){
        return parent::deleteObjectByID($ID);
    }

}
