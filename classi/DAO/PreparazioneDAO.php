<?php

/**
 * Description of PreparazioneDAO
 *
 * @author Alex
 */
class PreparazioneDAO extends ObjectDAO {
    
    //costruttore
    function __construct() {
        global $DB_TABLE_PREPARAZIONI;
        parent::__construct($DB_TABLE_PREPARAZIONI);
    }
        
    /**
     * La funzione salva una preparazione nel database
     * @param Preparazione $p
     * @return type
     */
    public function savePreparazione(Preparazione $p){
        $campi = array(
                'id_ingrediente' => $p->getIdIngrediente(),
                'giorni_anticipo' => $p->getGiorniAnticipo(),
                'descrizione' => $p->getDescrizione()
        );
        $formato = array('%d', '%d', '%s');
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * La funzione restituisce un array di oggetti preparazione di un determinato ingrediente
     * @param type $idIngrediente
     * @return array
     */
    public function getPreparazioni($idIngrediente){
        $result = null;
        $where = array(
            array(
                'campo'   => 'id_ingrediente',
                'valore'  => $idIngrediente,
                'formato' => 'INT'
            )
        );
        $temp = parent::getObjects(null, $where);
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $p = new Preparazione();
                $p->setIdIngrediente($item->id_ingrediente);
                $p->setGiorniAnticipo($item->giorni_anticipo);
                $p->setDescrizione($item->descrizione);
                array_push($result, $p);
            }
        }
        return $result;
    }
    
    /**
     * La funzione aggiorna una preparazione nel database
     * @param Preparazione $p
     * @return type
     */
    public function updatePreparazione(Preparazione $p){
        $update = array(
            'giorni_anticipo'   => $p->getGiorniAnticipo(),
            'descrizione'       => $p->getDescrizione()
        );
        $formatUpdate = array('%d', '%s');
        $where = array('ID' => $p->getID());
        $formatWhere = array('%d');
        return parent::updateObject($update, $formatUpdate, $where, $formatWhere);
    }
    
    /**
     * La funzione cancella una preparazione dal database
     * @param type $ID
     * @return type
     */
    public function deletePreparazione($ID){
        $array = array('ID' => $ID);
        return parent::deleteObject($array);
    }
    
    /**
     * La funzione elimina tutte le preparazioni relative ad un determinato ingrediente
     * @param type $idIngrediente
     * @return type
     */
    public function deletePreprazioni($idIngrediente){
        $array = array('id_ingrediente' => $idIngrediente);
        return parent::deleteObject($array);
    }

}
