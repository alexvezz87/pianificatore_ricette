<?php
namespace pianificatore_ricette;
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
        //imposto il timezone
        date_default_timezone_set('Europe/Rome');
        $timestamp = date('Y-m-d H:i:s', strtotime("now")); 
        
        $campi = array(           
            'settimana' => $a->getSettimana(),
            'id_utente' => $a->getIdUtente(),
            'data'      => $timestamp            
        );       
        $formato = array('%d', '%d', '%s');
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
                $a->setData($item->data);
                $a->setPdf($item->pdf);
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
     * La funzione aggiorna solamente il campo PDF di un'agenda
     * @param Agenda $a
     * @return type
     */
    public function updatePDF(Agenda $a){
        $update = array(
            'pdf' => $a->getPdf()
        );
        $formatUpdate = array('%s');
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
