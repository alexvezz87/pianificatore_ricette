<?php
namespace pianificatore_ricette;
/**
 * Description of ObjectDAO
 * Classe generica dedita alle operazioni base sul database
 *
 * @author Alex
 */
class ObjectDAO {
    
    private $wpdb;
    private $table;
    
    function __construct($table) {
        global $wpdb, $DB_PREFIX;
        $this->wpdb = $wpdb;
        $this->wpdb->prefix = $DB_PREFIX;
        $this->table = $this->wpdb->prefix.$table;
    }
    
    /**
     * Funzione di salvataggio di un oggetto nel database
     * @param type $campi
     * @param type $formato
     * @return boolean
     */
    protected function saveObject($campi, $formato){   
        //salvo un oggetto generico
        try{
            $this->wpdb->insert(
                    $this->table,
                    $campi,
                    $formato
            );
            return $this->wpdb->insert_id;
            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione passati i parametri di select, where e order restituisce un oggetto 
     * @param type $select --> array(...,...,...)
     * @param type $where --> array(array('campo' => x, 'valore' => y, 'formato' => z))
     * @param type $order --> array(array('campo' => x, 'ordine' => y) )
     * @return type
     */
    protected function getObjects($select = null, $where = null, $order = null, $limit = null){        
        
        //Vengono indicati i campi di select
        $query = "SELECT";
        if($select == null){
            $query.= " *";
        }
        else{
            $counter = 0;
            foreach($select as $value){
                $query.= " ".$value;
                if($counter == count($select) -1){
                    
                }
                else{
                    $query.=",";
                }
                $counter++;
            }
        }        
        //Viene indicata la tabella
        $query .= " FROM ".$this->table;    
        
        //Vegnono indicati i campi where (se ce ne sono)
        if($where != null){
            $query.= " WHERE";
            $counter = 0;
            foreach($where as $item){
                if($item['formato'] == 'INT'){
                    $query.=" ".$item['campo']." = ".$item['valore'];
                }
                else{
                    $query.=" ".$item['campo']." = '".$item['valore']."'";
                }
                if($counter == count($where) -1){
                    
                }
                else{
                    $query.=" AND";
                }
                $counter++;                
            }
        } 
        
        //Vengono indicati i campi di order by (se ce ne sono)
        if($order != null){
            $query.= " ORDER BY";
            $counter = 0;
            foreach($order as $item){
                $query.= " ".$item['campo']." ".$item['ordine'];
                if($counter == count($order) -1){
                    
                }
                else{
                    $query.=",";
                }
                $counter++;
            }
        }
        
        if($limit != null){
            $query .= " LIMIT ".$limit;
        }
        //print_r($query);    
        try{            
             return $this->wpdb->get_results($query);                        
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }        
    }
    
    /**
     * La funzione esegue una query sul database, con query passata come parametro
     * @param type $query
     * @return type
     */
    protected function searchObjects($query){
        try{
            return $this->wpdb->get_results($query);
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }
    
    /**
     * La funzione elimina un oggetto dal database
     * @param type $array
     * @return boolean
     */
    protected function deleteObject($array){        
        try{
            $this->wpdb->delete($this->table, $array);
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }        
    }
    
    /**
     * Funzione che elimina un oggetto dal database per ID passato
     * @param type $ID
     * @return type
     */
    protected function deleteObjectByID($ID){
        $array = array('ID' => $ID);
        return $this->deleteObject($array);
    }
    
    /**
     * La funzione aggiorna un oggetto nel database
     * @param type $update
     * @param type $formatUpdate
     * @param type $where
     * @param type $formatWhere
     * @return boolean
     */
    protected function updateObject($update, $formatUpdate, $where, $formatWhere){
        try{
            $this->wpdb->update(
                    $this->table,
                    $update,
                    $where,
                    $formatUpdate,
                    $formatWhere
            );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
        
}
