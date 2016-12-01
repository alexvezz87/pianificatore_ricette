<?php
namespace pianificatore_ricette;
/**
 * Description of PastoDAO
 *
 * @author Alex
 */
class TipologiaPastoDAO extends ObjectDAO{
    
    //costruttore
    function __construct() {
        global $DB_TABLE_TIPOLOGIA_PASTI;
        parent::__construct($DB_TABLE_TIPOLOGIA_PASTI);
    }
    
    /**
     * La funzione salva un pasto nel database
     * @param TipologiaPasto $p
     * @return type
     */
    public function saveTipologiaPasto(TipologiaPasto $p){
        $campi = array(
            'nome'          => $p->getNome(),
            'descrizione'   => $p->getDescrizione()
        );
        $formato = array('%s', '%s');
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * La funzione restituisce un array di oggetti pasto
     * @param type $where
     * @return array
     */
    public function getTipologiaPasti($where = null){
        $result = null;
        $temp = parent::getObjects(null, $where);
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $p = new TipologiaPasto();
                $p->setID($item->ID);
                $p->setNome(stripslashes($item->nome));
                $p->setDescrizione(stripslashes($item->descrizione));
                array_push($result, $p);
            }
        }
        return $result;
    }
    
    /**
     * La funzione aggiorna un pasto nel database
     * @param TipologiaPasto $p
     * @return type
     */
    public function updateTipologiaPasto(TipologiaPasto $p){
        $update = array(
            'nome'          => $p->getNome(),
            'descrizione'   => $p->getDescrizione()
        );
        $formatUpdate = array('%s', '%s');
        $where = array('ID' => $p->getID());
        $formatWhere = array('%d');
        return parent::updateObject($update, $formatUpdate, $where, $formatWhere);
    }
    
    /**
     * La funzione elimina un pasto dal database
     * @param type $ID
     * @return type
     */
    public function deleteTipologiaPastoByID($ID){
        return parent::deleteObjectByID($ID);
    }

}
