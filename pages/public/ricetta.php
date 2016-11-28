<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

if(!isset($_GET['id'])){
    
    return;
}

$id = $_GET['id'];
$view = new RicettaView();


$view->printPublicRicetta($id);

?>