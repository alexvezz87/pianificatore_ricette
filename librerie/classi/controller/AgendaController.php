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
    private $tpC; //tipologia pasto controller
    private $iC;
    private $pV; //classe printerView
    
    function __construct() {        
        //DAO
        $this->aDAO = new AgendaDAO();
        $this->gDAO = new GiornoDAO();        
        $this->gpDAO = new GiornoPastoDAO();
        $this->prDAO = new PastoRicettaDAO();
        $this->pDAO = new PastoDAO();
        //controller
        $this->rC = new RicettaController();  
        $this->tpC = new TipologiaPastoController();
        $this->iC = new IngredienteController();
        //view
        $this->pV = new PrinterView();
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
        return $idAgenda;
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
    private function saveRicettaPasto($idPasto, $idRicetta){
        //salvo l'associazione pasto ricetta        
        if($this->prDAO->savePastoRicetta($idPasto, $idRicetta)==false){
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
     * La funzione restituisce tutte le agende associate ad un determinato utente
     * @param type $idUtente
     * @return type
     */
    public function getAgendaByUtente($idUtente){
        $query = array(
            array(
                'campo'     => 'id_utente',
                'valore'    => $idUtente,
                'formato'   => 'INT'
            )
        );
        
        return $this->getAgende($query);
    }
    
    /**
     * La funzione restituisce un agenda conoscendone il suo id, null in caso di errore
     * @param type $idAgenda
     * @return type
     */
    public function getAgendaById($idAgenda){
        $query = array(
            array(
                'campo'     => 'ID',
                'valore'    => $idAgenda,
                'formato'   => 'INT'
            )
        );
        
        $temp = $this->getAgende($query);
        if($temp != null){
            return $temp[0];
        }
        return null;
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
            array(
                'campo'     => 'id_pasto',
                'valore'    => $idPasto,
                'formato'   => 'INT'
            )
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
    
    /**
     * La funzione cancella un agenda e tutti gli elementi associati nel database
     * @param type $ID
     * @return boolean
     */
    public function deleteAgenda($ID){
        //per eliminare l'agenda devo eliminare in successione
        //1. pasti-ricette
        //2. giorno-pasto
        //3. pasto
        //4. giorno
        //5. agenda
        
        //RETURN 
        // -1 errore nel cancellare pasti ricette
        // -2 errore nel cancellare giorni pasto
        // -3 errore nel cancellare i pasti
        // -4 errore nel cancellare i giorni
        // -5 errore nel cancellare l'agenda
        // true successo
        
        //da idAgenda ottengo gli ID dei giorni
        $query1 = array(
            array(
                'campo'     => 'id_agenda',
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        $giorni = $this->gDAO->getGiorni($query1);
        foreach($giorni as $giorno){
            //dai giorni ottengo gli id dei pasti
            $g = new Giorno();
            $g = $giorno;
            $query2 = array(
                array(
                    'campo'     => 'id_giorno',
                    'valore'    => $g->getID(),
                    'formato'   => 'INT'
                )
            );
            $giorniPasti = $this->gpDAO->getGiorniPasti($query);
            
            //al momento ho id dei pasti relativi al giorno
            foreach($giorniPasti as $giornoPasto){                
                //cancello pasti ricette
                $delete1 = array('id_pasto' => $giornoPasto['idPasto']);
                if($this->prDAO->deletePastiRicette($delete1)==true){
                    //cancello i giorni pasto
                    $delete2 = array('id_giorno' => $g->getID());
                    if($this->gpDAO->deleteGiorniPasti($delete2) == true){
                        //cancello i pasti
                        if($this->pDAO->deletePastoByID($giornoPasto['idPasto']) == true){
                            //non faccio nulla                           
                        }
                        else{
                            return -3;
                        }
                    }
                    else{
                        return -2;
                    }                    
                }
                else{
                    return -1;
                }
            }
        }        
        
        //cancello i giorni associati all'agenda
        $delete4 = array('id_agenda' => $ID);
        if($this->gDAO->deleteGiorno($delete4) == true){
            //cancello l'agenda
            if($this->aDAO->deleteAgendaByID($ID)==true){
                return true;
            }
            return -5;
        }
        return -4;
        
    }
    
    
    /**
     * La funzione aggiorna il campo pdf di un'agenda
     * @param Agenda $a
     * @return boolean
     */
    public function updatePDF(Agenda $a){
        if($a != null){
            return $this->aDAO->updatePDF($a);
        }        
        return false;
    }
    
    /**
     * La funzione genera un array di giorniAgenda composti in modo da agevolare la stampa su pdf
     * @param Agenda $a
     * @return array
     */
    public function createAgenda(Agenda $a){
        $result = array();
        //ciclo nei giorni
        foreach($a->getGiorni() as $giorno){
            $g = new Giorno();
            $g = $giorno;            
            $oggi = $g->getData();
            
            //ciclo nei pasti
            foreach($g->getPasti() as $pasto){
                $p = new Pasto();
                $p = $pasto;
                //ciclo nelle ricette se ci sono
                if(count($p->getRicette())>0){
                    //se trovo ricette, allora posso creare l'oggetto
                    $ga = new GiornoAgenda();
                    $ga->setData($oggi);
                    $ga->setNomeGiorno($g->getNome());                    
                    $ga->setTipo($this->tpC->getNomeTipologiaPasto($p->getIdTipologiaPasto()));
                    $html="";
                    foreach($p->getRicette() as $ricetta){
                        $r = new Ricetta();
                        $r = $ricetta;
                        $html.=$r->getNome().';';
                        
                        //ciclo gli ingredienti ricetta
                        foreach($r->getIngredienti() as $ingRic){
                            $ir = new IngredienteRicetta();
                            $ir = $ingRic;
                            
                            //trovo l'ingrediente dall'idIngrediente
                            $i = new Ingrediente();
                            $i = $this->iC->getIngredienteByID($ir->getIdIngrediente());
                            
                            if(count($i->getPreparazioni()) > 0){
                                //creo un ulteriore giorno che compare prima degli altri
                                foreach($i->getPreparazioni() as $preparazione){
                                    $pr = new Preparazione();
                                    $pr = $preparazione;
                                    $gap = new GiornoAgenda();
                                    $day = date('Y-m-d H:i:s', strtotime($oggi.' - '.$pr->getGiorniAnticipo().' day' ));
                                    $dayNome = date('N-j-m', strtotime($day)); 
                                    $nomeDay = $this->pV->translateDate($dayNome);
                                    $gap->setData($day);
                                    $gap->setTipo('Preparazione');
                                    $gap->setNomeGiorno($nomeDay);
                                    $gap->setDescrizione($i->getNome().': '.$pr->getDescrizione());
                                    //aggiungo il giorno ai result
                                    array_push($result, $gap);
                                }
                            }
                        }
                        
                    }                    
                    $ga->setDescrizione($html);
                    //aggiungo il giorno ai result
                    array_push($result, $ga);
                }
            }            
        }
        
        usort($result, array($this, "cmp"));
        
        $result = array_unique($result);
        
        $result2 = array();
        
        
        foreach($result as $giorno){
            $g = new GiornoAgenda();
            $g = $giorno;
            if(isset($result2[$g->getNomeGiorno()])){               
                array_push($result2[$g->getNomeGiorno()], $g);
            }
            else{               
                $result2[$g->getNomeGiorno()] = array();
                array_push($result2[$g->getNomeGiorno()], $g);
            }
        }
        
        foreach($result2 as $key => $value){
            $count = 0;
            foreach($result2[$key] as $item){
                $g = new GiornoAgenda();
                $g = $item;
                
                $descrizione = array();
                $temp = explode(';', $g->getDescrizione());
                if( count($temp) > 0){
                    foreach($temp as $item){
                        if(trim($item) != ''){
                            array_push($descrizione, $item);
                        }
                    }                     
                }
                else{
                    array_push($descrizione, $g->getDescrizione());
                }
                
                $descrizione = array_unique($descrizione);
                
                if(isset($result2[$key][$g->getTipo()])){
                    array_push($result2[$key][$g->getTipo()], $descrizione);
                }
                else{
                    $result2[$key][$g->getTipo()] = array();
                    array_push($result2[$key][$g->getTipo()], $descrizione);
                }
                unset($result2[$key][$count]);
                $count++;                
            }            
        }

        
        return $result2;
    }
    
    private function cmp(GiornoAgenda $a, GiornoAgenda $b){
        return strcmp($a->getData(), $b->getData());
    }
    
        
    

}
