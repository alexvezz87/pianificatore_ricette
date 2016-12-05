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
    
    public function saveTemplateAgenda($idAgenda, $nomeTemplate){        
        $campi = array(
            'id_agenda' => $idAgenda,
            'nome'      => $nomeTemplate
        );
        $formato = array('%d', '%s');
        return parent::saveObject($campi, $formato);        
    }
    
    
    public function getTemplateAgenda(){
        $result = null;
        $temp = parent::getObjects();
        
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $r['idAgenda'] = $item->id_agenda;
                $r['nome'] = $item->nome;
                array_push($result, $r);
            }
        }
        
        return $result;
    }
    
    public function deleteTemplate($ID){
        return parent::deleteObjectByID($ID);
    }
    

}
