<?php

/**
 * Description of RicettaDAO
 *
 * @author Alex
 */
class RicettaDAO extends ObjectDAO {
    
    //costruttore
    function __construct() {
        global $DB_TABLE_RICETTE;
        parent::__construct($DB_TABLE_RICETTE);
    }
    
    /**
     * La funzione salva una ricetta nel database
     * @param Ricetta $r
     * @return type
     */
    public function saveRicetta(Ricetta $r){
        //imposto il timezone
        date_default_timezone_set('Europe/Rome');
        $timestamp = date('Y-m-d H:i:s', strtotime("now")); 
        $campi = array(
            'nome'          => $r->getNome(),
            'preparazione'  => $r->getPreparazione(),
            'durata'        => $r->getDurata(),
            'foto'          => $r->getFoto(),
            'id_tipologia'  => $r->getIdTipologia(),
            'id_utente'     => $r->getIdUtente(),
            'data'          => $timestamp,
            'dose'          => $r->getDose()
        );        
        $formato = array('%s', '%s', '%d', '%s', '%d', '%d', '%s', '%d');
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * Funzione che passati i parametri di where, restituisce un array di oggetti ricette
     * @param type $where
     * @param string $order
     * @return array
     */
    public function getRicette($where = null, $order = null){        
        if($order == null){
            $order = array(
                array(
                    'campo'  => 'data',
                    'ordine' => 'DESC'
                )
            );
        }
        $temp = parent::getObjects(null, $where, $order);
        return $this->convertInRicatta($temp);
    }    
    
    public function getRicetteForPublic($where = null, $limit){
        $order = array(
            array(
                'campo'     => '',
                'ordine'    => 'RAND()'
            )
        );
        
        $temp = parent::getObjects(null, $where, $order, $limit);
        return $this->convertInRicatta($temp);
    }
    
    
    private function convertInRicatta($temp){
        $result = null;
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $r = new Ricetta();
                $r->setData($item->data);
                $r->setDurata($item->durata);
                $r->setFoto($item->foto);
                $r->setID($item->ID);
                $r->setIdTipologia($item->id_tipologia);
                $r->setIdUtente($item->id_utente);
                $r->setNome(stripslashes($item->nome));
                $r->setPreparazione(stripslashes($item->preparazione));
                $r->setDose($item->dose);
                array_push($result, $r);
            }
        }
        return $result;        
    }
    
    /**
     * La funzione aggiorna una ricetta nel database
     * @param Ricetta $r
     * @return type
     */
    public function updateRicetta(Ricetta $r){
        $update = array(
            'nome'          => $r->getNome(),
            'preparazione'  => $r->getPreparazione(),
            'durata'        => $r->getDurata(),
            'foto'          => $r->getFoto(),
            'id_tipologia'  => $r->getIdTipologia(), 
            'dose'          => $r->getDose()
        );
        $formatUpdate = array('%s', '%s', '%d', '%s', '%d', '%d');
        $where = array('ID' => $r->getID());
        $formatWhere = array('%d');
        return parent::updateObject($update, $formatUpdate, $where, $formatWhere);
    }
    
    /**
     * La funzione elimina una ricetta dal database
     * @param type $ID
     * @return type
     */
    public function deleteRicettaByID($ID){
        return parent::deleteObjectByID($ID);
    }

}
