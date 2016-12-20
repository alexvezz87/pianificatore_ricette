<?php
namespace pianificatore_ricette;
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
            'id_utente'     => $r->getIdUtente(),
            'data'          => $timestamp,
            'dose'          => $r->getDose(),
            'approvata'     => $r->getApprovata()
        );        
        $formato = array('%s', '%s', '%d', '%s', '%d', '%s', '%d', '%d');
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
                $r->setIdUtente($item->id_utente);
                $r->setNome(stripslashes($item->nome));
                $r->setPreparazione(stripslashes($item->preparazione));
                $r->setDose($item->dose);
                $r->setApprovata($item->approvata);
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
            'dose'          => $r->getDose(),
            'approvata'     => $r->getApprovata()
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
    
    /**
     * La funzione compone una query complessa su determinate tabelle per restituire un array di id ricette
     * @global type $DB_TABLE_RICETTE
     * @global type $DB_TABLE_INGREDIENTI_RICETTE
     * @global type $DB_TABLE_INGREDIENTI
     * @global type $DB_TABLE_RICETTE_TIPOLOGIE
     * @param type $param
     * @return type
     */
    public function searchRicette($param, $mode){
        global $DB_PREFIX;
        global $DB_TABLE_RICETTE, $DB_TABLE_INGREDIENTI_RICETTE, $DB_TABLE_RICETTE_TIPOLOGIE;
        global $ADMIN_ID;
        
        $query =  "SELECT DISTINCT r.ID ";
        $query .= "FROM ".$DB_PREFIX.$DB_TABLE_RICETTE." r ";
        $query .= "INNER JOIN ".$DB_PREFIX.$DB_TABLE_INGREDIENTI_RICETTE." ir ";
        $query .= "ON r.ID = ir.id_ricetta ";        
        $query .= "INNER JOIN ".$DB_PREFIX.$DB_TABLE_RICETTE_TIPOLOGIE." rt ";
        $query .= "ON rt.id_ricetta = r.ID ";        
        
        //WHERE
        $query .= "WHERE r.approvata = 1 ";
        
        //per la modalità standard, bisogna far visualizzare solo le ricette pubblicate dall'amministratore
        if($mode == 's'){
            $query .= 'AND r.id_utente = '.$ADMIN_ID.' ';
        }
        
        //nome ricetta
        if(isset($param['nome'])){
            $query .= "AND r.nome LIKE '%".$param['nome']."%' ";
        }
        
        //ingredienti
        if(isset($param['ingredienti']) && count($param['ingredienti']) > 0){
            $query .= "AND r.ID IN ( ";
            $query .= "SELECT r.ID FROM ".$DB_PREFIX.$DB_TABLE_RICETTE." r ";
            $query .= "INNER JOIN ".$DB_PREFIX.$DB_TABLE_INGREDIENTI_RICETTE." ir ";
            $query .= "ON r.ID = ir.id_ricetta "; 
            $query .= "WHERE ir.id_ingrediente IN ( ";
            $count = 0;
            foreach($param['ingredienti'] as $ing){
                if($count == count($param['ingredienti']) - 1){
                    $query .= $ing." ";
                }
                else{
                    $query .= $ing.", ";
                }
                
                $count++;
            }
            $query .= ") ";           
            $query .= "GROUP BY r.ID HAVING COUNT(*) = ".count($param['ingredienti']).") "; 
        }
        
        //tipologie
        if(isset($param['tipologie']) && count($param['tipologie'])>0){
            $query .= "AND r.ID IN ( ";
            $query .= "SELECT r.ID FROM ".$DB_PREFIX.$DB_TABLE_RICETTE." r ";
            $query .= "INNER JOIN ".$DB_PREFIX.$DB_TABLE_RICETTE_TIPOLOGIE." rt ";
            $query .= "ON rt.id_ricetta = r.ID ";
            $query .= "WHERE rt.id_tipologia IN ( ";
            $count = 0;
            foreach($param['tipologie'] as $tipo){
                if($count == count($param['tipologie']) - 1){
                    $query .= $tipo." ";
                }
                else{
                    $query .= $tipo.", ";
                }                
                $count++;
            }
            $query .= ") ";            
            $query .= "GROUP BY r.ID HAVING COUNT(*) = ".count($param['tipologie']).") ";
        }
        
        //print_r($query);
             
        return parent::searchObjects($query); 
    }

}
