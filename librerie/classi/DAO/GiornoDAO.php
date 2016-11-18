<?php

/**
 * Description of GiorniDAO
 *
 * @author Alex
 */
class GiornoDAO extends ObjectDAO{
    
    //costruttore
    function __construct() {
        global $DB_TABLE_GIORNI;
        parent::__construct($DB_TABLE_GIORNI);
    }
    
    /**
     * La funzione salva un giorno nel database
     * @param Giorno $g
     * @return type
     */
    public function saveGiorno(Giorno $g){
        $campi = array(            
            'nome'      => $g->getNome(),
            'data'      => $g->getData(),
            'id_agenda' => $g->getIdAgenda()           
        );
        $formato = array('%s', '%s', '%d');
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * La funzione restituisce un array di giorni
     * @param type $where
     * @return array
     */
    public function getGiorni($where = null){
        $result = null;
        $temp = parent::getObjects(null, $where);
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $g = new Giorno();
                $g->setID($item->ID);
                $g->setIdAgenda($item->id_agenda);
                $g->setNome(stripcslashes($item->nome));
                $g->setData($item->data);
                array_push($result,$g);
            }
        }
        return $result;
    }
    
    /**
     * La funzione aggiorna un Giorno nel database
     * @param Giorno $g
     * @return type
     */
    public function updateGiorno(Giorno $g){
        $update = array(
            'nome'      => $g->getNome(),
            'data'      => $g->getData(),
            'id_agenda' => $g->getIdAgenda()
        );
        $formatUpdate = array('%s', '%s', '%d');
        $where = array('ID' => $g->getID());
        $formatWhere = array('%d');
        return parent::updateObject($update, $formatUpdate, $where, $formatWhere);
    }
    
    /**
     * La funzione elimina un determinato giorno dal database
     * @param type $ID
     * @return type
     */
    public function deleteGiornoByID($ID){
        return parent::deleteObjectByID($ID);
    }
}
