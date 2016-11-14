<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/


//CREA DATABASE
function install_pianificatore(){
    global $DB_TABLE_INGREDIENTI, $DB_TABLE_PREPARAZIONI, $DB_TABLE_RICETTE, $DB_TABLE_TIPOLOGIE, $DB_TABLE_INGREDIENTI_RICETTE;
    global $DB_TABLE_AGENDE, $DB_TABLE_GIORNI ;
    try{
        //INGREDIENTI
        $args = array(
            array(
                'nome' => 'nome',
                'tipo' => 'VARCHAR(100)',
                'null' => 'NOT NULL'
            )
        );        
        creaTabella($DB_TABLE_INGREDIENTI, $args);
        
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
        creaTabella($DB_TABLE_PREPARAZIONI, $args, $fks);
        
        //TIPLOGIA RICETTA
        $args = array(           
            array(
                'nome' => 'nome',
                'tipo' => 'VARCHAR(100)',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'descrizione',
                'tipo' => 'TEXT',
                'null' => null
            )
        );
        creaTabella($DB_TABLE_TIPOLOGIE, $args);
        
        
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
            ),
            array(
                'nome' => 'id_utente',
                'tipo' => 'INT',
                'null' => null
            ),
            array(
                'nome' => 'data',
                'tipo' => 'TIMESTAMP',
                'null' => 'NOT NULL'
            )
        );
        $fks = array(
            array(
                'key1'    => 'id_tipologia',
                'tabella' => 'tipologie'                
            )
        );        
        creaTabella($DB_TABLE_RICETTE, $args, $fks);
        
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
        creaTabella($DB_TABLE_INGREDIENTI_RICETTE, $args, $fks);
        
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
        creaTabella($DB_TABLE_AGENDE, $args);
        
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
        creaTabella($DB_TABLE_GIORNI , $args, $fks);
        
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

//ELIMINA DATABASE
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
?>