<?php


namespace pianificatore_ricette;

/**
 * Description of TemplateAgendaController
 *
 * @author Alex
 */
class TemplateAgendaController {
    private $taDAO;    
        
    function __construct() {
        $this->taDAO = new TemplateAgendaDAO();        
    }
    
    
    public function saveTemplateAgenda(TemplateAgenda $ta){
        return $this->taDAO->saveTemplateAgenda($ta);
    }
    
    public function getTemplateAgenda(){
        return $this->taDAO->getTemplateAgenda();
    }
    
    public function getTemplateAgendaByID($ID){
        $query = array(
            array(
                'campo'     => 'ID',
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        $temp = $this->taDAO->getTemplateAgenda($query);
        if($temp != null){
            return $temp[0];
        }
        return null;
    }

    
    public function updateTemplateAgenda(TemplateAgenda $ta){
        return $this->taDAO->updateTemplateAgenda($ta);
    }
    
    public function deleteTipologiaAgenda($ID){
        return $this->taDAO->deleteTemplateAgenda($ID);
    }
}
