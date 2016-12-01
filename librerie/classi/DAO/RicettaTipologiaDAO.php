<?php
namespace pianificatore_ricette;
/**
 * Description of RicettaTipologiaDAO
 *
 * @author Alex
 */
class RicettaTipologiaDAO extends ObjectDAO {
    
    function __construct() {
        global $DB_TABLE_RICETTE_TIPOLOGIE;
        parent::__construct($DB_TABLE_RICETTE_TIPOLOGIE);
    }
    
    /**
     * La funzione salva un'associazione di ricetta tipologia
     * @param type $idRicetta
     * @param type $idTipologia
     * @return type
     */
    public function saveRicettaTipologia($idRicetta, $idTipologia){
        $campi = array(
            'id_ricetta'    => $idRicetta,
            'id_tipologia'  => $idTipologia
        );
        $formato = array('%d', '%d');
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * La funzione restituisce un array di idRicetta/idTipologia
     * @param type $where
     * @return array
     */
    public function getRicetteTipoologie($where){
        $result = null;
        $temp = parent::getObjects(null, $where);
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $value['idRicetta'] = $item->id_ricetta;
                $value['idTipologia'] = $item->id_tipologia;
                array_push($result, $value);
            }
        }
        return $result;
    }
    
    /**
     * La funzione elimina istanze di ricette tipologia dal database
     * @param type $array
     * @return type
     */
    public function deleteRicetteTipologie($array){
        return parent::deleteObject($array);
    }

}
