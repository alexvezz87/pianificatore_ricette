<?php

/**
 * Description of IngredienteController
 *
 * @author Alex
 */
class IngredienteController {
    private $iDAO; //ingrediente DAO
    private $pDAO; //preparazione DAO
    private $irDAO; //ingredienteRicetta DAO
    
    function __construct() {
        $this->iDAO = new IngredienteDAO();
        $this->pDAO = new PreparazioneDAO();
        $this->irDAO = new IngredienteRicettaDAO();
    }
    
    /**
     * Funzione che salva un ingrediente e le preparazioni ad esso associate
     * @param Ingrediente $i
     * @return boolean
     */
    public function saveIngrediente(Ingrediente $i){
        //salvare l'ingrediente, vuol dire salvare l'ingrediente e successivamente, salvare le preparazioni associate
        //1. Salvo l'ingrediente
        $idIngrediente = $this->iDAO->saveIngrediente($i);
        //2. Salvo le preparazioni
        if($idIngrediente != false){      
            if($i->getPreparazioni() != null){
                //ciclo se l'ingrediente ha preparazioni, altrimenti non ha senso farlo
                foreach($i->getPreparazioni() as $preparazione){
                    $p = new Preparazione();
                    $p = $preparazione;
                    $p->setIdIngrediente($idIngrediente);
                    if($this->pDAO->savePreparazione($p) == false){
                        return false;
                    }
                }   
            }
            return true;
        }
        return false;
    }
    
    /**
     * La funzione salva una singola preparazione
     * @param Preparazione $p
     * @return boolean
     */
    public function savePreparazione(Preparazione $p){
        if($this->pDAO->savePreparazione($p) == false){
            return false;
        }
        return true;
    }
    
    /**
     * La funzione restituisce tutti gli ingredienti con le relative preparazioni
     * @return array
     */
    public function getAllIngredienti(){        
        return $this->getIngredienti();
    }
    
    /**
     * La funzione restituisce un ingrediente passato un ID
     * @param type $ID
     * @return type
     */
    public function getIngredienteByID($ID){        
        $query = array(
            array(
                'campo'     => 'ID',
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        $temp =  $this->getIngredienti($query);  
        if($temp != null){
            return $temp[0];
        }
        return null;
    }
    
    /**
     * La funzione restituisce l'id di un ingrediente conoscendone il nome, null in caso di insuccesso
     * @param type $nome
     * @return type
     */
    public function getIngredienteByNome($nome){
        $query = array(
            array(
                'campo'     => 'nome',
                'valore'    => addslashes(trim($nome)),
                'formato'   => null
            )                        
        );
        $temp = $this->getIngredienti($query);
        if($temp != null){
            $i = new Ingrediente();
            $i = $temp[0];
            return $i->getID();
        }
        return null;
    }
    
    /**
     * Funzione generica che fa una query sul db e restituisce un array di ingredienti
     * @param type $where
     * @return array
     */
    private function getIngredienti($where = null){
         $result = array();
        //una volta ottenuti gli ingredienti, devo associare le relative preparazioni
        $ingredienti = $this->iDAO->getIngredienti($where);
        if($ingredienti != null){
            foreach($ingredienti as $ingrediente){
                $i = new Ingrediente();
                $i = $ingrediente;            
                //vado a prendere le preparazioni relative
                $i->setPreparazioni($this->pDAO->getPreparazioni($i->getID()));
                array_push($result, $i);            
            }
        }        
        return $result;      
    }
    
    /**
     * La funzione aggiorna un ingrediente e tutte le preparazioni annesse
     * @param Ingrediente $i
     * @return boolean
     */
    public function updateIngrediente(Ingrediente $i){
        //aggiorno l'ingrediente e le preparazioni associate
        if($this->iDAO->updateIngrediente($i) == true){
            if($i->getPreparazioni() != null){
                foreach($i->getPreparazioni() as $preparazione){
                    //può capitare che nell'aggiornare l'ingrediente, aggiungendo una nuova preparazione,
                    //questa non è presente nel database. Devo quindi aggiungerla
                    if(!isset($_POST['id-preparazione'])){
                        //la preparazione è stata aggiunta
                        if($this->pDAO->savePreparazione($preparazione)!=false){
                            return true;
                        }
                    }
                    else{
                    //altrimenti basta aggiornarlo
                        if($this->pDAO->updatePreparazione($preparazione)==true){
                            return true;
                        }
                    }
                }
            }
            else{
                //se non ci sono preparazioni elimino tutte quelle associate
                return $this->pDAO->deletePreprazioni($i->getID());
            }
        }
        return false;
    }
    
    /**
     * La funzione elimina un ingrediente e tutte le preparazioni annesse
     * @param type $ID
     * @return boolean
     */
    public function deleteIngrediente($ID){
        //prima di cancellare un ingrediente devo controllare che questo sia presente in una ricetta
        
        if($this->isIngredienteInRicetta($ID) == false){
        //devo cancellare prima tutte le preparazioni connesse all'ingrediente e poi l'ingrediente stesso
            if($this->pDAO->deletePreprazioni($ID) == true){
                //cancello l'ingrediente
                if($this->iDAO->deleteIngredienteByID($ID) == true){
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * La funzione elimina una determinata preparazione
     * @param type $ID
     * @return boolean
     */
    public function deletePreparazione($ID){
        if($this->pDAO->deletePreparazione($ID) == true){
            return true;
        }
        return false;
    }
    
    /**
     * La funzione indica se un ingrediente (passato per ID) è presente in una o più ricette
     * @param type $ID
     * @return boolean
     */
    public function isIngredienteInRicetta($ID){
        $query = array(
            array(
                'campo'     => 'id_ingrediente',
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        
        if($this->irDAO->getIngredientiRicette($query) == null){        
            return false;
        }
        return true;        
    }
    
    /**
     * La funzione restituisce un array id ID di ricette dove compare un determinato ingrediente, passato per ID
     * @param type $ID
     * @return array
     */
    public function getRicetteFormIngrediente($ID){
        $result = null;
        $query = array(
            array(
                'campo'     => 'id_ingrediente',
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        $select = array('DISTINCT');
        $temp = $this->irDAO->getIngredientiRicette($query, $select);
        if($temp != null){
            $result = array();
            foreach($temp as $item){
                $ir = new IngredienteRicetta();
                $ir = $item;
                array_push($result, $ir->getIdIngrediente());
            }
        }
        return $result;
    }
    

}
