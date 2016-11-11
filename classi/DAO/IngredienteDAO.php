<?php

/**
 * Description of IngredienteDAO
 *
 * @author Alex
 */
class IngredienteDAO extends ObjectDAO {
        
    //costruttore
    function __construct() {
        global $DB_TABLE_INGREDIENTI;                
        parent::__construct($DB_TABLE_INGREDIENTI);
    }
    
    /**
     * La funzione salva un ingrediente nel database
     * @param Ingrediente $i
     * @return type
     */
    public function saveIngrediente(Ingrediente $i){
        //devo creare i due array
        $campi = array(
            'nome' => $i->getNome()
        );
        $formato = array('%s');
        
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * La funzione restituisce un array di tutti gli ingredienti
     * @return array
     */
    public function getIngredienti(){        
        $result = null;
        //omettendo i tre array alla funzione, ottengo una select * from tabella       
        $temp = parent::getObjects();
        
        if(count($temp) > 0){
            $result = array();
            //trasformo in oggetto ingrediente
            foreach($temp as $item){
                $i = new Ingrediente();
                $i->setID($item->ID);
                $i->setNome($item->nome);
                array_push($result, $i);
            }
        }        
        return $result;        
    }
    
    /**
     * La funzione aggiorna un ingrediente nel database
     * @param type $ID
     * @param type $nome
     * @return type
     */
    public function updateIngrediente(Ingrediente $i){
        $update = array('nome' => $i->getNome());
        $formatUpdate = array('%s');
        $where = array('ID' => $i->getID());
        $formatWhere = array('%d');        
        return parent::updateObject($update, $formatUpdate, $where, $formatWhere);
    }
    
    /**
     * La funzione elimina un ingrediente dal database
     * @param type $ID
     * @return type
     */
    public function deleteIngrediente($ID){
        $array = array('ID' => $ID);
        return parent::deleteObject($array);
    }

}
