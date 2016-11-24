<?php

/**
 * Description of PastoController
 *
 * @author Alex
 */
class TipologiaPastoController {
    private $tpDAO;
    private $gpDAO;
    
    function __construct() {
        $this->tpDAO = new TipologiaPastoDAO();
        $this->gpDAO = new GiornoPastoDAO() ;
    }
    
    /**
     * La funzione salva un pasto 
     * @param TipologiaPasto $tp
     * @return type
     */
    public function saveTipologiaPasto(TipologiaPasto $tp){
        return $this->tpDAO->saveTipologiaPasto($tp);
    }
    
    /**
     * La funzione restituisce tutti i pasti presenti nel db
     * @return type
     */
    public function getTipologiaPasti(){
        return $this->tpDAO->getTipologiaPasti();
    }
    
    /**
     * La funzione restituisce un pasto, conoscendone l'ID
     * @param type $ID
     * @return type
     */
    public function getTipologiaPastoByID($ID){
        $query = array(
            array(
                'campo'     => 'ID',
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        
        $temp = $this->tpDAO->getTipologiaPasti($query);
        if($temp != null){
            return $temp[0];
        }
        return null;
    }
    
    public function getNomeTipologiaPasto($idTipologiaPasto){
        $result = $this->getTipologiaPastoByID($idTipologiaPasto);
        if($result != null){
            $tp = new TipologiaPasto();
            $tp = $result;
            return $tp->getNome();
        }
        return null;
    }
    
    /**
     * La funzione aggiorna un pasto
     * @param TipologiaPasto $tp
     * @return type
     */
    public function updateTipologiaPasto(TipologiaPasto $tp){
        return $this->tpDAO->updateTipologiaPasto($tp);
    }
    
    /**
     * La funzione controlla se un pasto è già contenuto in un giorno dell'agenda
     * @param type $ID
     * @return boolean
     */
    public function isTipologiaPastoInAgenda($ID){
        $query = array(
            array(
                'campo'     => 'id_pasto',
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        
        if($this->gpDAO->getGiorniPasti($query) == null){
            return false;
        }
        return true;
    }
    
    /**
     * La funzione cancella un pasto se questo non è assegnato a nessuna agenda
     * @param type $ID
     * @return boolean
     */
    public function deleteTipologiaPasto($ID){
        if($this->isTipologiaPastoInAgenda($ID) == false){
            
            if($this->tpDAO->deleteTipologiaPastoByID($ID)==true){
                return true;
            }
        }
        else{
            return -1;
        }
        return false;
    }

}
