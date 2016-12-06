<?php

namespace pianificatore_ricette;

/**
 * Description of TemplateAgendaDAO
 *
 * @author Alex
 */
class TemplateAgendaDAO extends ObjectDAO {
    
    //costruttore
    function __construct() {
        global $DB_TABLE_TEMPLATE_AGENDE;
        parent::__construct($DB_TABLE_TEMPLATE_AGENDE);
    }
    
    public function saveTemplateAgenda(TemplateAgenda $ta){        
        $campi = array(
            'id_agenda'   => $ta->getIdAgenda(),
            'nome'        => $ta->getNome(),
            'descrizione' => $ta->getDescrizione()
        );
        $formato = array('%d', '%s', '%s');
        return parent::saveObject($campi, $formato);        
    }
    
    
    public function getTemplateAgenda($where = null){
        $result = null;
        $temp = parent::getObjects(null, $where);
        
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $ta = new TemplateAgenda();
                $ta->setID($item->ID);
                $ta->setNome(stripslashes($item->nome));
                $ta->setIdAgenda($item->id_agenda);
                $ta->setDescrizione(stripslashes($item->descrizione));  
                array_push($result, $ta);
            }
        }
        
        return $result;
    }
    
    public function deleteTemplateAgenda($ID){
        return parent::deleteObjectByID($ID);
    }
    
    public function updateTemplateAgenda(TemplateAgenda $ta){
        $update = array(
            'nome'          => $ta->getNome(),
            'id_agenda'     => $ta->getIdAgenda(),
            'descrizione'   => $ta->getDescrizione()
        );
        $formatUpdate = array('%s', '%d', '%s');
        $where = array('ID' => $ta->getID());
        $formatWhere = array('%d');
        return parent::updateObject($update, $formatUpdate, $where, $formatWhere);
    }
    
}
