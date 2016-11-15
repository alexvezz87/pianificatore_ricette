<?php

/**
 * Description of AgendaController
 *
 * @author Alex
 */
class AgendaController {
    private $aDAO; //agenda DAO
    private $gDAO; //giorno DAO   
    private $gpDAO; //giorno pasto DAO
    private $prDAO; //pasto ricette DAO
    private $pDAO; //pasto DAO
    private $rC; //ricette controller
    
    function __construct() {
        //DAO
        $this->aDAO = new AgendaDAO();
        $this->gDAO = new GiornoDAO();        
        $this->gpDAO = new GiornoPastoDAO();
        $this->prDAO = new PastoRicettaDAO();
        $this->pDAO = new PastoDAO();
        //controller
        $this->rC = new RicettaController();        
    }
    
    /**
     * La funzione salva un Agenda e i giorni ad essa correlati
     * @param Agenda $a
     * @return boolean
     */
    function saveAgenda(Agenda $a){
        //per salvare il tutto, l'elemento agenda deve avere al suo interno
        //un array di giorni che a loro volta devono avere un array di pasti
        //che a loro volta devono avere un array di ricette.
        //Tre cicli uno dentro l'altro.
        
        //RETURN
        //-1 --> errore nel salvare l'agenda
        //-2 --> errore nel salvare il giorno
        //-3 --> errore nel salvare il pasto
        //-4 --> errore nel salvare l'associazione giorno pasto
        //-5 --> errore nel salvare l'associazione pasto ricetta
        //true --> in caso di successo
        
        //1. Salvo l'agenda
        $idAgenda = $this->aDAO->saveAgenda($a);
        if($idAgenda == false){
            return -1;
        }
        //2. Ciclo i giorni 
        foreach($a->getGiorni() as $giorno){
            $flag = $this->saveGiorno($idAgenda, $giorno);
            if($flag != true){
                return $flag;
            }
        }
        return true;
    }
    
    /**
     * La funzione salva un giorno e tutti i pasti ad esso correlati
     * @param type $idAgenda
     * @param Giorno $g
     * @return boolean
     */
    private function saveGiorno($idAgenda, Giorno $g){
        $g->setIdAgenda($idAgenda);
        $idGiorno = $this->gDAO->saveGiorno($g);        
        if($idGiorno == false){
            return -2;
        }
        foreach($g->getPasti() as $pasto){
            $flag = $this->savePasto($idGiorno, $pasto);
            if($flag != true){
                return $flag;
            }
        }
        return true;       
    }
    
    /**
     * La funzione salva un pasto e tutte le ricette ad esso correlate
     * @param type $idGiorno
     * @param Pasto $p
     * @return boolean
     */
    private function savePasto($idGiorno, Pasto $p){
        //salvo il pasto        
        $idPasto = $this->pDAO->savePasto($p);
        if($idPasto == false){
            return -3;
        }
        //salvo il giorno pasto
        if($this->gpDAO->saveGiornoPasto($idGiorno, $idPasto) == false){
            return -4;
        }
        //salvo le ricette pasto
        foreach($p->getRicette() as $ricetta){
            $flag = $this->saveRicettaPasto($idPasto, $ricetta);
            if($flag != true){
                return $flag;
            }
        
        }
        return true; 
    }
    
    /**
     * La funzione salva un associazione ricetta pasto
     * @param type $idPasto
     * @param Ricetta $r
     * @return boolean
     */
    private function saveRicettaPasto($idPasto, Ricetta $r){
        //salvo l'associazione pasto ricetta        
        if($this->prDAO->savePastoRicetta($idPasto, $r->getID())==false){
            return -5;
        }
        return true;
    }
    
    /**
     * La funzione restituisce tutte le agende di un determinato utente
     * @param type $idUtente
     * @return type
     */
    public function getUserAgende($idUtente){
        $where = array(
            array(
                'campo'     => 'id_utente',
                'valore'    => $idUtente,
                'formato'   => 'INT'
            )
        );
        
        return $this->getAgende($where);
    }
    
    /**
     * La funzione restituisce tutte le agende appartenenti ad una determinata settimana
     * @param type $numSettimana
     * @return type
     */
    public function getAgendeSettimanali($numSettimana){
        $where = array(
            array(
                'campo'     => 'settimana',
                'valore'    => $numSettimana,
                'formato'   => 'INT'
            )
        );
        
        return $this->getAgende($where);
    }
    
    /**
     * La funzione restituisce tutte le agende nel database
     * @return type
     */
    public function getAllAgende(){
        return $this->getAgende();
    }
    
    /**
     * Funzione che resituisce un array di agende, con annessi i giorni correlati
     * @param type $where
     * @return array
     */
    private function getAgende($where = null){
        $result = null;        
        //ottengo le agende dalla query
        $agende = $this->aDAO->getAgende($where);
        if($agende != null){
            $result = array();
            //ho le agende, ora per ciascuna trovo i giorni
            foreach($agende as $agenda){
                $a = new Agenda();
                $a = $agenda;
                $a->setGiorni($this->getGiorni($a->getID()));
                array_push($result, $a);
            }
        }
        
        return $result;
    }
    
    /**
     * Funzione che restituisce un array di giorni di una determinata agenda con annessi i pasti correlati
     * @param type $idAgenda
     * @return array
     */
    private function getGiorni($idAgenda){
        $result = null;
        //ottengo i giorni dall'idAgenda
        $where = array(
            array(
                'campo'     => 'id_agenda',
                'valore'    => $idAgenda,
                'formato'   => 'INT'
            )
        );
        $giorni = $this->gDAO->getGiorni($where);
        if($giorni != null){
            $result = array();
            foreach($giorni as $giorno){
                $g = new Giorno();
                $g = $giorno;
                $g->setPasti($this->getPasti($g->getID()));
                array_push($result, $g);
            }
        }
        return $result;
    }
    
    /**
     * Funzione che restituisce un array di pasti di un determinato giorno con annesse le ricette correlate
     * @param type $idGiorno
     * @return array
     */
    private function getPasti($idGiorno){
        $result = null;
        //devo ottenere la lista dei pasti conoscendo l'id del giorno
        $where = array(
            array(
                'campo'     => 'id_giorno',
                'valore'    => $idGiorno,
                'formato'   => 'INT'
            )
        );
        $temp = $this->gpDAO->getGiorniPasti($where);
        if($temp != null){
            //ho una lista di array idGiorno, idPasto
            $result = array();
            foreach($temp as $item){
                //devo ottenere il pasto 
                $p = new Pasto();
                //ottengo il pasto 
                $p = $this->getPasto($item['idPasto']);
                $p->setRicette($this->getRicette($p->getID()));
                array_push($result, $p);                
            }
        }
        return $result;        
    }
    
    
    /**
     * Funzione che restituisce un array di ricette di un determinato pasto
     * @param type $idPasto
     * @return array
     */
    private function getRicette($idPasto){
        $result = null;
        //devo ottenere la lista delle ricetta conoscendo l'id del pasto
        $where = array(
            'campo'     => 'id_pasto',
            'valore'    => $idPasto,
            'formato'   => 'INT'
        );
        $temp = $this->prDAO->getPastiRicette($where);
        if($temp != null){
            //ho una lista di array idPasto, idRicetta
            $result = array();
            foreach($temp as $item){
                //devo ottenere una ricetta
                $r = new Ricetta();
                //ottengo la ricetta
                $r = $this->rC->getRicettaByID($item['idRicetta']);
                array_push($result, $r);
            }
        }
        return $result;
    }
    
    /**
     * La funzione restituisce un pasto
     * @param type $ID
     * @return type
     */
    private function getPasto($ID){
        $where = array(
            array(
                'campo'     => 'ID',
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        $temp = $this->pDAO->getPasti($where);
        if($temp != null){
            return $temp[0];
        }
        return null;
    }
    
    
    public function deleteAgenda($ID){
        //per eliminare l'agenda devo eliminare in successione
        //1. pasti-ricette
        //2. giorno-pasto
        //3. pasto
        //4. giorno
        //5. agenda
    }
    

}
