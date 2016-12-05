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
require_once 'librerie/api_db.php';
require_once 'librerie/classi/classes.php';
require_once 'librerie/functions.php';



global $PR_DIR_PDF;
$PR_DIR_PDF = plugin_dir_path(__FILE__).'\pdf\\';

//indico la cartella dove è contenuto il plugin
require_once (dirname(__FILE__) . '/pianificatore_ricette.php');

//creo il db al momento dell'attivazione
register_activation_hook(__FILE__, 'install_pianificatore_ricette');
function install_pianificatore_ricette(){
    //installo il database
    pianificatore_ricette\install_pianificatore();     
   
}

//rimuovo il db quando disattivo il plugin
register_deactivation_hook( __FILE__, 'remove_pianificatore_ricette');
function remove_pianificatore_ricette(){
    //rimuovo il database
    pianificatore_ricette\dropPianificatore();
}

//Aggiungo il menu di Plugin
function add_pr_admin_menu(){
    add_menu_page('Pianificatore Ricette', 'Pianificatore Ricette', 'edit_plugins', 'pianificatore_ricette', 'add_pr_page_1', plugins_url('images/ico_pr.png', __FILE__), 10);
    add_submenu_page('pianificatore_ricette', 'Ingredienti', 'Ingredienti', 'edit_plugins', 'ingredienti', 'add_gestione_ingredienti');
    add_submenu_page('pianificatore_ricette', 'Visualizza Agende', 'Visualizza Agende', 'edit_plugins', 'visualizza_agende', 'add_agende');    
    add_submenu_page('pianificatore_ricette', 'Tipologia Ricetta', 'Tipologia Ricetta', 'edit_plugins', 'tipologia_ricetta', 'add_gestione_tr');    
    add_submenu_page('pianificatore_ricette', 'Tipologia Pasto', 'Tipologia Pasto', 'edit_plugins', 'tipologia_pasto', 'add_gestione_tp');
        
    add_submenu_page('', 'Pagina dettaglio',  'Pagina dettaglio', 'edit_plugins', 'pagina_dettaglio', 'add_pagina_dettaglio_pr');
}

function add_pr_page_1(){
    include 'pages/admin/gestione_ricette.php';
}

function add_gestione_ingredienti(){
    include 'pages/admin/gestione_ingredienti.php';
}

function add_gestione_tr(){
    include 'pages/admin/gestione_tipologia_ricetta.php';
}

function add_gestione_tp(){
    include 'pages/admin/gestione_tipologia_pasto.php';
}

function add_agende(){
    include 'pages/admin/gestione_agende.php';
}

function add_pagina_dettaglio_pr(){
    include 'pages/admin/pagina_dettaglio.php';
}


//aggiungo gli shortcode
add_shortcode('paginaAgenda', 'add_agenda');
add_shortcode('paginaRicetta', 'add_ricetta');

function add_agenda(){
    
    //solo per gli utenti loggati al sito
    if(is_user_logged_in()){    
        include 'pages/public/componi_agenda.php';
    }
    else{
        echo '<p>Funzionalità riservata solo agli utenti registrati al sito</p>';
    }
}

function add_ricetta(){
    include 'pages/public/ricetta.php';
}

//registro il menu
add_action('admin_menu', 'add_pr_admin_menu');


function register_pr_style(){
    wp_register_style('pr_style_css', plugins_url('css/style.css', __FILE__));
    wp_register_style('pr_bootstrap-style', plugins_url('css/bootstrap.min.css', __FILE__) );
    
    wp_enqueue_style('pr_style_css');
    wp_enqueue_style('pr_bootstrap-style');
}


function register_pr_admin_style() {
    wp_register_style('pr_admin-style', plugins_url('css/admin-style.css', __FILE__) );
    wp_register_style('pr_admin-bootstrap-style', plugins_url('css/bootstrap.min.css', __FILE__) );
    wp_register_style('pr_file-input', plugins_url('css/fileinput.min.css', __FILE__) );
    
    //wp_register_style('pr_file-input-style', plugins_url('css/fileinput.min.css', __FILE__) );    
    wp_enqueue_style('pr_admin-style');
    wp_enqueue_style('pr_admin-bootstrap-style');
    wp_enqueue_style('pr_file-input');
    //wp_enqueue_style('pr_file-input-style');
}

//registro gli stili
add_action( 'wp_enqueue_scripts', 'register_pr_style' );
add_action( 'admin_enqueue_scripts', 'register_pr_admin_style' );


//aggiungo gli script lato amministratore
function register_pr_admin_js_script(){
    wp_register_script('autocomplete-js', plugins_url('pianificatore_ricette/js/jquery.autocomplete-min.js'), array('jquery'), '1.0', false);   
    wp_register_script('ui-widget-js', plugins_url('pianificatore_ricette/js/jquery-ui.min.js'), array('jquery'), '1.0', false);       
    wp_register_script('file-input', plugins_url('pianificatore_ricette/js/fileinput.min.js'), array('jquery'), '1.0', false);       
    wp_register_script('admin-js', plugins_url('pianificatore_ricette/js/admin-script.js'), array('jquery'), '1.0', false);   
    
    
    wp_enqueue_script('autocomplete-js');  
    wp_enqueue_script('ui-widget-js'); 
    wp_enqueue_script('file-input'); 
    wp_enqueue_script('admin-js');  
}

add_action( 'admin_enqueue_scripts', 'register_pr_admin_js_script' );


//registro gli script

function register_pr_js_script(){
    wp_register_script('autocomplete-js', plugins_url('pianificatore_ricette/js/jquery.autocomplete-min.js'), array('jquery'), '1.0', false);   
    wp_register_script('ui-widget-js', plugins_url('pianificatore_ricette/js/jquery-ui.min.js'), array('jquery'), '1.0', false);       
    wp_register_script('file-input', plugins_url('pianificatore_ricette/js/fileinput.min.js'), array('jquery'), '1.0', false);       
    wp_register_script('script', plugins_url('pianificatore_ricette/js/script.js'), array('jquery'), '1.0', false);   
    
    wp_enqueue_script('autocomplete-js');  
    wp_enqueue_script('ui-widget-js'); 
    wp_enqueue_script('file-input'); 
    wp_enqueue_script('script'); 
}


//Aggiungo il file di Javascript al plugin
add_action( 'wp_enqueue_scripts', 'register_pr_js_script' );


//CHIAMATE AJAX
add_action( 'wp_ajax_nopriv_ricerca_ricette', 'ricerca_ricette' );
add_action( 'wp_ajax_ricerca_ricette', 'ricerca_ricette' );
function ricerca_ricette(){
    \pianificatore_ricette\ricerca_ricette($_POST);
}



?>