<?php
namespace pianificatore_ricette;
/**
 * Description of RicettaController
 *
 * @author Alex
 */
class RicettaController {
    private $tDAO;
    private $rDAO;
    private $irDAO;
    private $rtDAO;
    private $pDAO;
    private $iC;
    
    function __construct() {
        //assegno le classi DAO
        $this->tDAO = new TipologiaDAO();
        $this->rDAO = new RicettaDAO();
        $this->irDAO = new IngredienteRicettaDAO();
        $this->rtDAO = new RicettaTipologiaDAO();
        $this->pDAO = new PreferiteDAO();
        //assegno la classe Controller di ingrediente
        $this->iC = new IngredienteController();
    }
    
    //METODI INERENTI A TIPOLOGIA    
    
    /**
     * La funzione salva una tipologia nel database
     * @param Tipologia $t
     * @return boolean
     */
    public function saveTipologia(Tipologia $t){
        if($this->tDAO->saveTipologia($t) == false){
            return false;
        }
        return true;
    }
    
    /**
     * La funzione restituisce tutte le tipologie presenti nel database
     * @return type
     */
    public function getTipologie(){
        return $this->tDAO->getTipologie();        
    }
    
    /**
     * La funzione restituisce una tipologia ricetta per id passato
     * @param type $ID
     * @return type
     */
    public function getTipologiaByID($ID){
        $query = array(
            array(
                'campo'     => 'ID',
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        $temp = $this->tDAO->getTipologie($query);
        if($temp != null){
            return $temp[0];
        }
        return null;
    }
    
    /**
     * La funzione aggiorna una determinata tipologia
     * @param Tipologia $t
     * @return type
     */
    public function updateTipologia(Tipologia $t){
        return $this->tDAO->updateTipologia($t);
    }
    
    /**
     * La funzione elimina dal database una determinata tipologia
     * @param type $ID
     * @return type
     */
    public function deleteTipologia($ID){
        if($this->isTipologiaRicettaInRicetta($ID) == false){
            if($this->tDAO->deleteTipologiaByID($ID)==true){
                return true;
            }
        }
        else{
            return -1;
        }
        return false;
    }

    
    public function isTipologiaRicettaInRicetta($ID){
        $query = array(
            array(
                'campo'     => 'id_tipologia',
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        if($this->rtDAO->getRicetteTipoologie($query) == null){
            return false;
        }
        return true;
    }
    
    //METODI INERENTI A RICETTA
    
    /**
     * La funzione salva una ricetta composta da ingredienti nel database
     * @param Ricetta $r
     * @param type $arrayIR
     * @return boolean
     */
    public function saveRicetta(Ricetta $r, $arrayIR, $arrayT){    
        //arrayIR deve essere un array di oggetti IngredienteRicetta  
        //arrayRT deve essere un array di idTipologia      
        //1. Salvo la ricetta
        $idRicetta = $this->rDAO->saveRicetta($r);
        
        if($idRicetta != false){
            //associo gli ingredienti alla ricetta      
            //print_r($arrayIR);
            foreach($arrayIR as $item){
                $ir = new IngredienteRicetta();
                $ir = $item;
                $ir->setIdRicetta($idRicetta);                
                if($this->irDAO->saveIngredienteRicetta($ir) == false){
                    //cancello la ricetta salvata precedentemente
                    $this->rDAO->deleteRicettaByID($idRicetta);
                    return false;
                }
            }
            //associo le tipologie alla ricetta
            foreach($arrayT as $idTipologia){
                if($this->rtDAO->saveRicettaTipologia($idRicetta, $idTipologia) == false){
                    //cancello gli ingredienti associati alla ricetta
                    $query = array('id_ricetta' => $idRicetta);
                    $this->irDAO->deleteIngredientiRicette($query);
                    //cancello la ricetta
                    $this->rDAO->deleteRicettaByID($idRicetta);
                    return false;
                }
            }
            return true;
        }
        return false;
    }
    
    /**
     * La funzione restituisce una ricetta per ID passato
     * @param type $ID
     * @return type
     */
    public function getRicettaByID($ID){
        $query = array(
            array(
                'campo'     => 'ID',
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        
        $temp = $this->getRicette($query);
        if($temp != null){
            return $temp[0];
        }
        return null;
    }
    
    /**
     * Restituisce tutte le ricette presenti nel sistema
     * @return type
     */
    public function getAllRicette(){
        return $this->getRicette();
    }
    
    
    public function getIdRicettaByNome($nome){
        $query = array(
            array(
                'campo'     => 'nome',
                'valore'    => addslashes(trim($nome)),
                'formato'   => null
            )
        );        
               
        $temp = $this->getRicette($query);
        if($temp != null){
            $r = new Ricetta();
            $r = $temp[0];
            return $r->getID();
        }
        return null;
    }
    
    
    /**
     * La funzione restituisce tutte le ricette di un determinato utente
     * @param type $idUtente
     * @return type
     */
    public function getRicetteByUtente($idUtente){        
        $query = array(
            array(
                'campo'     => 'id_utente',
                'valore'    => $idUtente,
                'formato'   => 'INT'
            )
        );
        
        return $this->getRicette($query);        
    }
            
    /**
     * La funzione restituisce tutte le ricette dati determinati parametri in ingresso
     * @param type $param
     * @return type
     */
    public function getRicetteByParameters($query, $view=false, $limit = null){        
        return $this->getRicette($query, $view, $limit);
    }
    
    /**
     * Funzione generica che restituisce un array di ricette con gli ingredienti associati
     * @param type $where
     * @return array
     */
    private function getRicette($where = null, $view = false, $limit = null){
        //view indica se la visualizzazione:
        //false per lato amministrativo
        //true per lato pubblico
        
        $result = array();
        
        //ottengo le ricette dalla query
        if($view == false){
            $ricette = $this->rDAO->getRicette($where);
        }
        else{
            $ricette = $this->rDAO->getRicetteForPublic($where, $limit);
        }
        
        //ho le ricette, per ogni ricetta devo associare gli ingredienti
        if($ricette != null){
            foreach($ricette as $item){
                $r = new Ricetta();
                $r = $item;
                $query = array(
                    array(
                        'campo'     => 'id_ricetta',
                        'valore'    => $r->getID(),
                        'formato'   => 'INT'
                    )
                );
                $temp = $this->irDAO->getIngredientiRicette($query);

                if($temp != null){
                    //salvo gli ingredienti Ricetta nella ricettta
                    $r->setIngredienti($temp);
                }
                
                //salvo le tipologie ricetta nella ricetta
                $temp2 = $this->rtDAO->getRicetteTipoologie($query);
                if($temp2 != null){
                    $tipologie = array();
                    foreach($temp2 as $item){
                        
                        $tp = $this->tDAO->getTipologiaById($item['idTipologia']);
                        if($tp!=null){
                            array_push($tipologie, $tp);
                        }
                    }
                    //salvo le tipologie nella ricetta
                    $r->setTipologie($tipologie);
                }
                
                //salvo la ricetta aggiornata con gli ingredienti, nell'array result
                array_push($result, $r);
            }        
        }
        return $result;
    }
    
    /**
     * La funzione aggiorna una ricetta nel 
     * @param Ricetta $r
     * @param type $arrayIR
     * @return boolean
     */
    public function updateRicetta(Ricetta $r, $arrayIR, $arrayT){
        //l'aggiornamento di una ricetta, consiste non solo nell'aggiornare gli attributi dell'elemento Ricetta,
        //ma consiste anche nell'aggiornare la tabella IngredientiRicette a seconda dei nuovi ingredienti aggiunti
        
        //RETURN
        //TRUE - in caso di successo
        //-1 in caso di errore nell'update sulla ricetta
        //-2 in caso di errore nella cancellazione degli ingredienti associati alla ricetta
        //-3 in caso di errore nel salvare un ingrediente nella ricetta
        //-4 in caso di errore nella cancellazione di tutte le tipologie
        //-5 in caso di errore nel salvare la tipoolgia nella ricetta
        
        if($this->rDAO->updateRicetta($r) == true){
            //"aggiorno" anche la lista degli ingredienti
            //1. elimino tutti gli ingredienti associati alla ricetta
            $query = array('id_ricetta' => $r->getID());
            //2. salvo ogni nuovo ingrediente passato nel database
            if($this->irDAO->deleteIngredientiRicette($query) == true){
                foreach($arrayIR as $item){                   
                    $ir = new IngredienteRicetta();
                    $ir = $item;
                    $ir->setIdRicetta($r->getID());
                    if($this->irDAO->saveIngredienteRicetta($ir) == false){
                        //errore nel salvare un ingrediente nella ricetta
                        return -3;
                    }
                }
            }
            else{
                //errore nel cancellare gli ingredienti dalla ricetta
                return -2;
            }
            //3. elimino tutte le tipologie
            if($this->rtDAO->deleteRicetteTipologie($query) == true){
                foreach($arrayT as $idTipologia){
                    if($this->rtDAO->saveRicettaTipologia($r->getID(), $idTipologia) == false){
                        return -5;
                    }
                }
            }
            else{
                return -4;
            }
            
            
            return true;
        }
        //errore nell'aggiornare la ricetta
        return -1;
    }
    
    
    /**
     * Funzione che elimina un singolo ingrediente da una ricetta
     * @param type $idRicetta
     * @param type $idIngrediente
     * @return type
     */
    public function deleteIngredienteFromRicetta($idRicetta, $idIngrediente){
        //per eliminare un ingrediente da una ricetta, devo andare ad eliminare un record della tabella ingredientiRicette
        $query = array(
            'id_ricetta'        => $idRicetta,
            'id_ingrediente'    => $idIngrediente
        );
        return $this->irDAO->deleteIngredientiRicette($query);
    }
    
    /**
     * La funzione elimina una ricetta dal database
     * @param type $idRicetta
     * @return boolean
     */
    public function deleteRicetta($idRicetta){
        //per eliminare una ricetta, prima devo eliminare la lista di ingredienti associata
        //update--> devo eliminare anche tutte le preferenze su quella ricetta prima di eliminarla
        //update 2 --> devo controllare se la ricetta non faccia parte di un template di agenda
        
        if($this->isRicettaInAgenda($idRicetta) == false){        
            $query = array('id_ricetta' => $idRicetta);
            if($this->irDAO->deleteIngredientiRicette($query) == true){
                //elimino le preferite sulla ricetta
                if($this->pDAO->deletePreferite($query) == true){
                    //elimino la ricetta tipologia
                    if($this->rtDAO->deleteRicetteTipologie($query) == true){                
                        //elimino la ricetta
                        if($this->rDAO->deleteRicettaByID($idRicetta) == true){
                            return true;
                        }
                    }
                }
            }
            return false;
        }
        else{
            return -1;
        }
        
    }
    
    /**
     * La funzione salva una ricetta come preferita per un determinato utente
     * @param type $idUtente
     * @param type $idPreferita
     * @return boolean
     */
    public function savePreferita($idUtente, $idRicetta){
        if($this->pDAO->savePreferita($idUtente, $idRicetta)==false){
            return false;
        }
        return true;
    }
    
    /**
     * La funzione elimina un associazione preferita tra un utente e una ricetta
     * @param type $idUtente
     * @param type $idRicetta
     * @return type
     */
    public function removePreferita($idUtente, $idRicetta){
        $query = array(
            'id_utente'  => $idUtente,
            'id_ricetta' => $idRicetta 
        );
        return $this->pDAO->deletePreferite($query);
    }
    
    /**
     * La funzione restituisce le ricette ricercate per determinati parametri
     * @param type $param
     * @return array
     */
    public function searchRicette($param, $mode){
        global $IMG_NOT_FOUND;
        $temp = $this->rDAO->searchRicette($param, $mode);       
        if($temp != null){
            $result = array();
            foreach($temp as $item){
               $r = new Ricetta();
               $r = $this->getRicettaByID($item->ID);
               $temp2['ID'] = $r->getID();
               $temp2['nome'] = $r->getNome();
               $urlFoto = $IMG_NOT_FOUND;
               if($r->getFoto() != null){
                   $urlFoto = $r->getFoto();
               }
               $temp2['foto'] = $urlFoto;
               $tipologie = $this->getTipologieByIdRicetta($r->getID());
               if($tipologie != null){
                   $temp2['tipologie'] = array();
                   $temp2['tipologie'] = $tipologie;
               }
               $temp2['durata'] = $r->getDurata();               
               
               array_push($result, $temp2);
            }
            return $result;
        }
        return null;
    }
    
    /**
     * La funzione restituise un array di nomi tipologia appartenenti ad una ricetta
     * @param type $idRicetta
     * @return array
     */
    protected function getTipologieByIdRicetta($idRicetta){
        $query = array(
            array(
                'campo'     => 'id_ricetta',
                'valore'    => $idRicetta,
                'formato'   => 'INT'
            )
        );
        
        $temp = $this->rtDAO->getRicetteTipoologie($query);
        if($temp != null){
            $result = array();
            foreach($temp as $item){
                $t = new Tipologia();
                $t = $this->tDAO->getTipologiaById($item['idTipologia']);
                array_push($result, $t->getNome());
            }
            return $result;
        }
        return null;
    }
    
    /**
     * La funzione controlla se la ricetta è contenuta in un agenda
     * @param type $idRicetta
     * @return boolean
     */
    protected function isRicettaInAgenda($idRicetta){
        $aC = new AgendaController();
        $taC = new TemplateAgendaController();
        $tagende = $taC->getTemplateAgenda();
                
        foreach($tagende as $tagenda){            
            $ta = new TemplateAgenda();
            $ta = $tagenda;
            $a = new Agenda();
            $a = $aC->getAgendaById($ta->getIdAgenda());
            foreach($a->getGiorni() as $giorno){
                $g = new Giorno();
                $g = $giorno;
                foreach($g->getPasti() as $pasto){
                    $p = new Pasto();
                    $p = $pasto;
                    if(count($p->getRicette())>0){
                        foreach($p->getRicette() as $ricetta){
                            $r = new Ricetta();
                            $r = $ricetta;
                            if($r->getID() == $idRicetta){
                                //se trovo la ricetta, restituisco true
                                return true;
                            }
                        }
                        
                    }
                }
            }
        }        
        return false;
       
    }
}
