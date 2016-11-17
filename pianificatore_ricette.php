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
require_once 'classi/classes.php';
require_once 'librerie/functions.php';

//indico la cartella dove è contenuto il plugin
require_once (dirname(__FILE__) . '/pianificatore_ricette.php');

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

//Aggiungo il menu di Plugin
function add_pr_admin_menu(){
    add_menu_page('Pianificatore Ricette', 'Pianificatore Ricette', 'edit_plugins', 'pianificatore_ricette', 'add_pr_page_1', plugins_url('images/ico_pr.png', __FILE__), 10);
    add_submenu_page('pianificatore_ricette', 'Ingredienti', 'Ingredienti', 'edit_plugins', 'ingredienti', 'add_gestione_ingredienti');
    
    add_submenu_page('', 'Pagina dettaglio',  'Pagina dettaglio', 'edit_plugins', 'pagina_dettaglio', 'add_pagina_dettaglio');
}

function add_pr_page_1(){
    echo 'ciao';
}

function add_gestione_ingredienti(){
    include 'pages/admin/gestione_ingredienti.php';
}


function add_pagina_dettaglio(){
    include 'pages/admin/pagina_dettaglio.php';
}

//registro il menu
add_action('admin_menu', 'add_pr_admin_menu');


function register_pr_style(){
    wp_register_style('pr_style_css', plugins_url('css/style.css', __FILE__));
    wp_register_style('pr_bootstrap-style', plugins_url('css/bootstrap.min.css', __FILE__) );
    
    wp_enqueue_style('pr_style_css');
    wp_enqueue_style('bootstrap-style');
}

//registro gli stili
add_action( 'wp_enqueue_scripts', 'register_pr_style' );
add_action( 'admin_enqueue_scripts', 'register_pr_admin_style' );

function register_pr_admin_style() {
    wp_register_style('pr_admin-style', plugins_url('css/admin-style.css', __FILE__) );
    wp_register_style('pr_admin-bootstrap-style', plugins_url('css/bootstrap.min.css', __FILE__) );
    //wp_register_style('pr_file-input-style', plugins_url('css/fileinput.min.css', __FILE__) );
    
    wp_enqueue_style('pr_admin-style');
    wp_enqueue_style('pr_admin-bootstrap-style');
    //wp_enqueue_style('pr_file-input-style');
}

?>