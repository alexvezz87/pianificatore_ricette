<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

/********************* CREATE TABLES *******************/

function install_pianificatore(){
    try{
        //INGREDIENTI
        $args = array(
            array(
                'nome' => 'nome',
                'tipo' => 'VARCHAR(100)',
                'null' => 'NOT NULL'
            )
        );        
        creaTabella('ingredienti', $args);
        
        //PREPARAZIONI
        $args = array(
            array(
                'nome' => 'id_ingrediente',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'giorni_anticipo',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'descrizione',
                'tipo' => 'TEXT',
                'null' => 'NOT NULL'
            )
        );
        $fks = array(
            array(
                'key1'    => 'id_ingrediente',
                'tabella' => 'ingredienti',                
            )
        );
        creaTabella('preparazioni', $args, $fks);
        
        //TIPLOGIA RICETTA
        $args = array(           
            array(
                'nome' => 'nome',
                'tipo' => 'VARCHAR(100)',
                'null' => 'NOT NULL'
            )
        );
        creaTabella('tipologie', $args);
        
        
        //RICETTE
        $args = array(
            array(
                'nome' => 'nome',
                'tipo' => 'VARCHAR(100)',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'preparazione',
                'tipo' => 'VARCHAR(100)',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'durata',
                'tipo' => 'INT',
                'null' => null
            ),
            array(
                'nome' => 'foto',
                'tipo' => 'TEXT',
                'null' => null
            ),
            array(
                'nome' => 'id_tipologia',
                'tipo' => 'INT',
                'null' => null
            )
        );
        $fks = array(
            array(
                'key1'    => 'id_tipologia',
                'tabella' => 'tipologie'                
            )
        );        
        creaTabella('ricette', $args, $fks);
        
        //INGREDIENTI RICETTE
        $args = array(
             array(
                'nome' => 'id_ingrediente',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'id_ricetta',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'quantita',
                'tipo' => 'DECIMAL(8,2)',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'unita_misura',
                'tipo' => 'VARCHAR(20)',
                'null' => 'NOT NULL'
            ),
        );      
        $fks = array(
            array(
                'key1'    => 'id_ingrediente',
                'tabella' => 'ingredienti'                
            ),
            array(
                'key1'    => 'id_ricetta',
                'tabella' => 'ricette'                
            )
        );
        creaTabella('ingredienti_ricette', $args, $fks);
        
        //AGENDE
        $args = array(
            array(
                'nome' => 'settimana',
                'tipo' => 'VARCHAR(100)',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'id_utente',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            )
        );        
        creaTabella('agende', $args);
        
        //GIORNI
        $args = array(
            array(
                'nome' => 'nome',
                'tipo' => 'VARCHAR(100)',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'data',
                'tipo' => 'TIMESTAMP',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'id_agenda',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            )     
        );    
        $fks = array(
            array(
                'key1'    => 'id_agenda',
                'tabella' => 'agende'                
            )
        );  
        creaTabella('giorni', $args, $fks);
        
        //PASTI
        $args = array(
            array(
                'nome' => 'nome',
                'tipo' => 'VARCHAR(100)',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'id_giorno',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            )
        );   
        $fks = array(
            array(
                'key1'    => 'id_giorno',
                'tabella' => 'giorni'                
            )
        );
        creaTabella('pasti', $args, $fks);        
        
        //PASTI RICETTE
        $args = array(
            array(
                'nome' => 'id_ricetta',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'id_pasto',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            )
        );        
        $fks = array(
            array(
                'key1'    => 'id_ricetta',
                'tabella' => 'ricette'                
            ),
            array(
                'key1'    => 'id_pasto',
                'tabella' => 'pasti'                
            ),
        );
        creaTabella('pasti_ricette', $args, $fks);
                
        
        
    } catch (Exception $ex) {
        e($ex);
        return false;
    }
}

/**
 * Funzione personalizzata per creare tabelle nel database
 * @global type $wpdb
 * @global type $DB_PREFIX
 * @param type $tabella, indica il nome della tabella da creare
 * @param type $param, indica una serie di parametri che popolano gli attributi
 * @param type $fks, indica una serie di collegamenti a chiave esterna
 * @return boolean
 */
function creaTabella($tabella, $param, $fks = null){
    global $wpdb, $DB_PREFIX;
    $charset_collate = "";
    //prefisso --> pps = plugin preventivi serrature
    $wpdb->prefix = $DB_PREFIX;
    if (!empty ($wpdb->charset)){
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }
    if (!empty ($wpdb->collate)){
        $charset_collate .= " COLLATE {$wpdb->collate}";
    }
    
    $table = $wpdb->prefix.$tabella;
    $query = "CREATE TABLE IF NOT EXISTS $table (";
    $query.= "ID INT NOT NULL auto_increment PRIMARY KEY,";
    
    $counter = 0;
    foreach($param as $p){
        $query.= " ".$p['nome']." ".$p['tipo'];
        if(isset($p['null'])){
            $query.= " ".$p['null'];
        }   
        
        if($counter == count($param)-1){
            
        }
        else{
            $query.=",";
        }
        $counter++;
    }
    
    if($fks != null){
        $counter = 0;
        $query.=',';
        foreach($fks as $fk){
            $query.= " FOREIGN KEY (".$fk['key1'].") REFERENCES ".$wpdb->prefix.$fk['tabella']."(ID)";
            if($counter == count($fks)-1){
            
            }
            else{
                $query.=",";
            }
            $counter++;
        }
    }
    
    $query.=");{$charset_collate}";
    
    try{
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($query);   
        return true;
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }    
    
}


/********************* DROP TABLES *******************/
function dropPianificatore(){
    
    try{
        
        //drop di ingredienti_ricette
        dropTabella('ingredienti_ricette');
        
        //drop di preparazioni e ingredienti
        if(dropTabella('preparazioni') == true ){
            dropTabella('ingredienti');
        }
        
        //drop table di ciò che riguarda ricette
        if(dropTabella('pasti_ricette')==true){
            if(dropTabella('ricette')==true){
                dropTabella('tipologie');
            }
            
            if(dropTabella('pasti')== true){
                if(dropTabella('giorni') == true){
                    dropTabella('agende');
                }
            }
        }
        
        
    } catch (Exception $ex) {
        _e($ex);        
        return false;
    }
}

/**
 * Funzione personalizzata per droppare tabelle dal database
 * @global type $wpdb
 * @global type $DB_PREFIX
 * @param type $tabella
 * @return boolean
 */
function dropTabella($tabella){
    global $wpdb, $DB_PREFIX;
    $wpdb->prefix = $DB_PREFIX;
    try{
        $query = "DROP TABLE IF EXISTS ".$wpdb->prefix.$tabella.";";
        $wpdb->query($query);
        return true;
    } catch (Exception $ex) {
        _e($e);
        return false;
    }
}


?>