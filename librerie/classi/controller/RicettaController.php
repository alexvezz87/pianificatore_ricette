<?php

/**
 * Description of RicettaController
 *
 * @author Alex
 */
class RicettaController {
    private $tDAO;
    private $rDAO;
    private $irDAO;
    private $iC;
    
    function __construct() {
        //assegno le classi DAO
        $this->tDAO = new TipologiaDAO();
        $this->rDAO = new RicettaDAO();
        $this->irDAO = new IngredienteRicettaDAO();
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
        if($this->rDAO->getRicette($query) == null){
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
    public function saveRicetta(Ricetta $r, $arrayIR){    
        //arrayIR deve essere un array di oggetti IngredienteRicetta        
        //1. Salvo la ricetta
        $idRicetta = $this->rDAO->saveRicetta($r);
        if($idRicetta != false){
            //associo gli ingredienti alla ricetta
            foreach($arrayIR as $item){
                $ir = new IngredienteRicetta();
                $ir = $item;
                $ir->setIdRicetta($idRicetta);
                if($this->irDAO->saveIngredienteRicetta($ir) == false){
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
     * La funzione restituisce tutte le ricette di una determinata tipologia
     * @param type $idTipologia
     * @return type
     */
    public function getRicetteByTipologia($idTipologia){
        $query = array(
            array(
                'campo'     => 'id_tipologia',
                'valore'    => $idTipologia,
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
    public function getRicetteByParameters($query){        
        return $this->getRicette($query);
    }
    
    /**
     * Funzione generica che restituisce un array di ricette con gli ingredienti associati
     * @param type $where
     * @return array
     */
    private function getRicette($where = null){
        $result = array();
        
        //ottengo le ricette dalla query
        $ricette = $this->rDAO->getRicette($where);
        
        //ho le ricette, per ogni ricetta devo associare gli ingredienti
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
            
            //creo un array che accolga gli ingredienti
            $arrayIngredienti = array();
            foreach($temp as $t){
                $ir = new IngredienteRicetta();
                $ir = $t;
                //ottengo un ingrediente
                $i = $this->iC->getIngredienteByID($ir->getIdIngrediente());
                array_push($arrayIngredienti, $i);
            }
            //salvo gli ingredienti nella ricetta
            $r->setIngredienti($arrayIngredienti);
            //salvo la ricetta aggiornata con gli ingredienti, nell'array result
            array_push($result, $r);
        }        
        return $result;
    }
    
    /**
     * La funzione aggiorna una ricetta nel 
     * @param Ricetta $r
     * @param type $arrayIR
     * @return boolean
     */
    public function updateRicetta(Ricetta $r, $arrayIR){
        //l'aggiornamento di una ricetta, consiste non solo nell'aggiornare gli attributi dell'elemento Ricetta,
        //ma consiste anche nell'aggiornare la tabella IngredientiRicette a seconda dei nuovi ingredienti aggiunti
        
        //RETURN
        //TRUE - in caso di successo
        //-1 in caso di errore nell'update sulla ricetta
        //-2 in caso di errore nella cancellazione degli ingredienti associati alla ricetta
        //-3 in caso di errore nel salvare un ingrediente nella ricetta
        
        if($this->rDAO->updateRicetta($r) == true){
            //"aggiorno" anche la lista degli ingredienti
            //1. elimino tutti gli ingredienti associati alla ricetta
            $query = array('id_ricetta' => $r->getID());
            //2. salvo ogni nuovo ingrediente passato nel database
            if($this->irDAO->deleteIngredientiRicette($query) == true){
                foreach($arrayIR as $item){                   
                    $ir = new IngredienteRicetta();
                    $ir = $item;                    
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
        $query = array('id_ricetta' => $idRicetta);
        if($this->irDAO->deleteIngredientiRicette($query) == true){
            //elimino la ricetta
            if($this->rDAO->deleteRicettaByID($idRicetta) == true){
                return true;
            }
        }
        return false;
    }
    
    
    
    
}
