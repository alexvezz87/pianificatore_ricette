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
            'id_tipologia'  => $r->getId_tipologia(),
            'id_utente'     => $r->getId_utente(),
            'data'          => $timestamp
        );
        $formato = array('%s', '%s', '%d', '%s', '%d', '%d', '%s');
        return parent::saveObject($campi, $formato);
    }
    
    /**
     * Funzione che passati i parametri di where, restituisce un array di oggetti ricette
     * @param type $where
     * @param string $order
     * @return array
     */
    public function getRicette($where, $order = null){
        $result = null;
        if($order == null){
            $order = array(
                'campo'  => 'data',
                'ordine' => 'DESC'
            );
        }
        $temp = parent::getObjects(null, $where, $order);
        if(count($temp) > 0){
            $result = array();
            foreach($temp as $item){
                $r = new Ricetta();
                $r->setData($item->data);
                $r->setDurata($item->durata);
                $r->setFoto($item->foto);
                $r->setID($item->ID);
                $r->setId_tipologia($item->id_tipologia);
                $r->setId_utente($item->id_utente);
                $r->setNome($item->nome);
                $r->setPreparazione($item->preparazione);
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
            'id_tipologia'  => $r->getId_tipologia()
        );
        $formatUpdate = array('%s', '%s', '%d', '%s', '%d');
        $where = array('ID' => $r->getID());
        $formatWhere = array('%d');
        return parent::updateObject($update, $formatUpdate, $where, $formatWhere);
    }
    
    /**
     * La funzione elimina una ricetta dal database
     * @param type $ID
     * @return type
     */
    public function deleteRicetta($ID){
        $array = array('ID' => $ID);
        return parent::deleteObject($array);
    }

}
