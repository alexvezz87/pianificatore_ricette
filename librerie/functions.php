<?php
namespace pianificatore_ricette;
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/


//CREA DATABASE
function install_pianificatore(){
    global $DB_TABLE_INGREDIENTI, $DB_TABLE_PREPARAZIONI, $DB_TABLE_RICETTE, $DB_TABLE_TIPOLOGIA_RICETTE, $DB_TABLE_INGREDIENTI_RICETTE;
    global $DB_TABLE_AGENDE, $DB_TABLE_GIORNI, $DB_TABLE_TIPOLOGIA_PASTI, $DB_TABLE_PASTI, $DB_TABLE_GIORNI_PASTI, $DB_TABLE_PASTI_RICETTE;
    global $DB_TABLE_RICETTE_TIPOLOGIE, $DB_TABLE_PREFERITE, $DB_TABLE_TEMPLATE_AGENDE;
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
                'tabella' => $DB_TABLE_INGREDIENTI,                
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
        creaTabella($DB_TABLE_TIPOLOGIA_RICETTE, $args);
        
        
        //RICETTE
        $args = array(
            array(
                'nome' => 'nome',
                'tipo' => 'TEXT',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'preparazione',
                'tipo' => 'TEXT',
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
                'nome' => 'id_utente',
                'tipo' => 'INT',
                'null' => null
            ),
            array(
                'nome' => 'data',
                'tipo' => 'TIMESTAMP',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'dose',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'approvata',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            )
        );         
        creaTabella($DB_TABLE_RICETTE, $args);
        
        
        //RICETTE-TIPOLOGIA
        $args = array(
            array(
                'nome'  => 'id_ricetta',
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => 'id_tipologia',
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            )            
        );
        $fks = array(
            array(
                'key1'      => 'id_ricetta',
                'tabella'   => $DB_TABLE_RICETTE
            ),
            array(
                'key1'      => 'id_tipologia',
                'tabella'   => $DB_TABLE_TIPOLOGIA_RICETTE
            )
        );
        creaTabella($DB_TABLE_RICETTE_TIPOLOGIE, $args, $fks);
        
        //PREFERITE
        $args = array(
            array(
                'nome'  => 'id_ricetta',
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => 'id_utente',
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            )
        );
        $fks = array(
            array(
                'key1'      => 'id_ricetta',
                'tabella'   => $DB_TABLE_RICETTE
            )            
        );
        creaTabella($DB_TABLE_PREFERITE, $args);
        
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
                'null' => null
            ),
            array(
                'nome' => 'unita_misura',
                'tipo' => 'VARCHAR(20)',
                'null' => null
            ),
        );      
        $fks = array(
            array(
                'key1'    => 'id_ingrediente',
                'tabella' => $DB_TABLE_INGREDIENTI                
            ),
            array(
                'key1'    => 'id_ricetta',
                'tabella' => $DB_TABLE_RICETTE               
            )
        );
        creaTabella($DB_TABLE_INGREDIENTI_RICETTE, $args, $fks);
        
        //AGENDE
        $args = array(
            array(
                'nome' => 'nome',
                'tipo' => 'TEXT',
                'null' => 'null'
            ),
            array(
                'nome' => 'settimana',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'id_utente',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'data',
                'tipo' => 'TIMESTAMP',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'pdf',
                'tipo' => 'TEXT',
                'null' => null
            )
            
        );        
        creaTabella($DB_TABLE_AGENDE, $args);
        
        //TEMPLATE AGENDE
        $args = array(
            array(
                'nome' => 'nome',
                'tipo' => 'TEXT',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'id_agenda',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            ),
            array(
                'nome' => 'descrizione',
                'tipo' => 'TEXT',
                'null' => null
            )
        );
        $fks = array(
            array(
                'key1'    => 'id_agenda',
                'tabella' => $DB_TABLE_AGENDE
            )
        );
        creaTabella($DB_TABLE_TEMPLATE_AGENDE, $args, $fks);
        
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
                'tabella' => $DB_TABLE_AGENDE                
            )
        );  
        creaTabella($DB_TABLE_GIORNI , $args, $fks);
        
        //TIPOLOGIA PASTI
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
        creaTabella($DB_TABLE_TIPOLOGIA_PASTI, $args);
        
        //PASTI
        $args = array(
            array(
                'nome' => 'id_tipologia',
                'tipo' => 'INT',
                'null' => 'NOT NULL'
            )
        );
        $fks = array(
            array(
                'key1' => 'id_tipologia',
                'tabella' => $DB_TABLE_TIPOLOGIA_PASTI
            )
        );        
        creaTabella($DB_TABLE_PASTI, $args, $fks);
        
        
        
        //GIORNI PASTI
        $args = array(
            array(
                'nome' => 'id_pasto',
                'tipo' => 'INT',
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
                'key1' => 'id_pasto',
                'tabella' => $DB_TABLE_PASTI
            ),
            array(
                'key1' => 'id_giorno',
                'tabella' => $DB_TABLE_GIORNI
            )
        );
        creaTabella($DB_TABLE_GIORNI_PASTI, $args, $fks);
        
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
                'tabella' => $DB_TABLE_RICETTE                
            ),
            array(
                'key1'    => 'id_pasto',
                'tabella' => $DB_TABLE_PASTI                
            ),
        );
        creaTabella($DB_TABLE_PASTI_RICETTE, $args, $fks);
                
        
        
    } catch (Exception $ex) {
        e($ex);
        return false;
    }
}

//ELIMINA DATABASE
function dropPianificatore(){
    global $DB_TABLE_INGREDIENTI, $DB_TABLE_PREPARAZIONI, $DB_TABLE_RICETTE, $DB_TABLE_TIPOLOGIA_RICETTE, $DB_TABLE_INGREDIENTI_RICETTE;
    global $DB_TABLE_AGENDE, $DB_TABLE_GIORNI, $DB_TABLE_TIPOLOGIA_PASTI, $DB_TABLE_PASTI, $DB_TABLE_GIORNI_PASTI, $DB_TABLE_PASTI_RICETTE;
    global $DB_TABLE_RICETTE_TIPOLOGIE, $DB_TABLE_PREFERITE, $DB_TABLE_TEMPLATE_AGENDE;
    
    try{
        
        //drop di ingredienti_ricette
        dropTabella($DB_TABLE_INGREDIENTI_RICETTE);
        
        //drop di preparazioni e ingredienti
        if(dropTabella($DB_TABLE_PREPARAZIONI) == true ){
            dropTabella($DB_TABLE_INGREDIENTI);
        }
        
        //drop table di ciò che riguarda ricette
        if(dropTabella($DB_TABLE_PASTI_RICETTE)==true){
            if(dropTabella($DB_TABLE_RICETTE_TIPOLOGIE)==true){
                if(dropTabella($DB_TABLE_PREFERITE) == true){
                    if(dropTabella($DB_TABLE_RICETTE)==true){
                        dropTabella($DB_TABLE_TIPOLOGIA_RICETTE);
                    }
                }
            }
            
            if(dropTabella($DB_TABLE_GIORNI_PASTI)==true){
                if(dropTabella($DB_TABLE_PASTI)== true){
                    if(dropTabella($DB_TABLE_GIORNI) == true){
                        dropTabella($DB_TABLE_TEMPLATE_AGENDE);
                        dropTabella($DB_TABLE_AGENDE);
                        dropTabella($DB_TABLE_TIPOLOGIA_PASTI);
                    }
                }
            }
        }
    } catch (Exception $ex) {
        _e($ex);        
        return false;
    }
}


function curPageURL() {
    $pageURL = 'http';
    
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
     $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
     $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function getTime($time){
    //viene passata una data nella forma aaaa-mmm-dd hh:mm:ss (es. 2015-09-13 16:30:40)
    //devo restituire gg/mm/aaaa hh:mm

    $temp = explode(' ', $time);
    $time1 = explode('-', $temp[0]);
    $time2 = explode(':', $temp[1]);

    return $time1[2].'/'.$time1[1].'/'.$time1[0];
    //return $time1[2].'/'.$time1[1].'/'.$time1[0].' - '.$time2[0].':'.$time2[1];
}

function createPages(){
    if(get_page_by_title('Agenda') == null){
        //la pagina non esiste
        
        $post = array(
            'comment_status' => 'open',
            'ping_status' => 'closed',
            'post_date' => date('Y-m-d H:i:s'),
            'post_name' => 'agenda',
            'post_status' => 'publish',
            'post_title' => 'Agenda',
            'post_type' => 'page',
            'post_content' => '[paginaAgenda]'
        );
        //insert page and save the id
        return wp_insert_post($post, false);        
    }
    
    if(get_page_by_title('Ricetta') == null){
        //la pagina non esiste
        
        $post = array(
            'comment_status' => 'open',
            'ping_status' => 'closed',
            'post_date' => date('Y-m-d H:i:s'),
            'post_name' => 'ricetta',
            'post_status' => 'publish',
            'post_title' => 'Ricetta',
            'post_type' => 'page',
            'post_content' => '[paginaRicetta]'
        );
        //insert page and save the id
        return wp_insert_post($post, false);        
    }
}

/**
 * Funzione di ricerca ricette, chiamata da ajax
 */
function ricerca_ricette($array){
   
    //scompongo l'array
    $param = array();
    
    //nome
    if(isset($array['nomeRicetta']) && trim($array['nomeRicetta']) != ''){
        $param['nome'] = $array['nomeRicetta'];
    }
    
    //tipologia
    if(isset($array['tipologia']) && count($array['tipologia']) > 0 && $array['tipologia'] != ''){        
        $param['tipologie'] = array();
        foreach($array['tipologia'] as $tipo){
            array_push($param['tipologie'], $tipo);
        }
    }
    
    //ingredienti
    if(isset($array['ingredienti']) && trim($array['ingredienti']) != ''){
        $temp = explode(',', $array['ingredienti']);
        $param['ingredienti'] = array();
        $iC = new IngredienteController();        
        foreach($temp as $ing){ 
            $idIng = $iC->getIngredienteByNome($ing);
            if($idIng != null){
                array_push($param['ingredienti'], $idIng);
            }
        }
    }
    
    //eseguo la query
    $rC = new RicettaController();
    $ricette = $rC->searchRicette($param);
    
    echo json_encode($ricette);        
    die();
}


$closure = function($name){
    return "Hello ".$name;
};

?>