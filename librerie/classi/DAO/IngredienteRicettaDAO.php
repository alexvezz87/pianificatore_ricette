<?php
namespace pianificatore_ricette;
/**
 * Description of IngredienteRicettaDAO
 *
 * @author Alex
 */
class IngredienteRicettaDAO extends ObjectDAO {
    
    //costruttore
    function __construct() {
        global $DB_TABLE_INGREDIENTI_RICETTE;
        parent::__construct($DB_TABLE_INGREDIENTI_RICETTE);
    }
    
    /**
     * La funzione salva un oggetto IngredienteRicetta nel database
     * @param IngredienteRicetta $ir
     * @return type
     */
    public function saveIngredienteRicetta(IngredienteRicetta $ir){
        $campi = array(
            'id_ingrediente'    => $ir->getIdIngrediente(),
            'id_ricetta'        => $ir->getIdRicetta(),
            'quantita'          => $ir->getQuantita(),
            'unita_misura'      => $ir->getUnitaMisura()
        );        
        $formato = array('%d', '%d', '%f', '%s');
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * La funzione restituisce un array di oggetti IngredientiRicetta
     * @param type $where
     * @return array
     */
    public function getIngredientiRicette($where, $select=null){
        $result = null;
        $temp = parent::getObjects($select, $where);
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $ir = new IngredienteRicetta();
                $ir->setID($item->ID);
                $ir->setIdIngrediente($item->id_ingrediente);
                $ir->setIdRicetta($item->id_ricetta);
                $ir->setQuantita($item->quantita);
                $ir->setUnitaMisura(stripslashes($item->unita_misura));
                array_push($result, $ir);
            }
        }
        return $result;
    }
    
    public function deleteIngredientiRicette($array){
        return parent::deleteObject($array);
    }

}
