<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/
 /**
 * @package pianificatore_ricette
 */
/*
Plugin Name: Pianificatore Ricette
Plugin URI: 
Description: Plugin personalizzato per la gestione di ingredienti, ricette e la gestione di questi in un'agenda settimanale
Version: 1.0
Author: Alex Vezzelli - Alex Soluzioni Web
Author URI: http://www.alexsoluzioniweb.it/
License: GPLv2 or later
*/


//includo le librerie
require_once 'librerie/variabili_globali.php';
require_once 'librerie/install_db.php';


//creo il db al momento dell'attivazione
register_activation_hook(__FILE__, 'install_DB_pianificatore');
function install_DB_pianificatore(){
    install_pianificatore();
}


//rimuovo il db quando disattivo il plugin
register_deactivation_hook( __FILE__, 'remove_DB_pianificatore');
function remove_DB_pianificatore(){
    dropPianificatore();
}

?>