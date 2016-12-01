<?php
namespace pianificatore_ricette;
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$type = $_GET['type'];
$id = $_GET['id'];
$nome = "";


$url = "";
if($type == 'ING'){
    $url = 'ingredienti';
}
else if($type == 'TP'){
    $url = 'tipologia_pasto';
}
else if($type == 'TR'){
    $url = 'tipologia_ricetta';
}
else if($type == 'R'){
    $url = 'pianificatore_ricette';
}
else if($type == 'A'){    
    $url = 'visualizza_agende';
}

?>
<div class="back" style="margin-top:20px">
    <a href="<?php echo admin_url() ?>admin.php?page=<?php echo $url ?>"><<<< Torna alla pagina precedente</a>
</div>
<div class="dettaglio-utente">
<?php

if($type == 'ING'){
    $view = new IngredienteView();
    $nome = 'Ingrediente';
?>

    <h1>Pagina dettaglio <?php echo $nome ?></h1>
<?php 
    $view->listenerDettaglioIngrediente();
    $view->printDettaglioIngrediente($id);  
    die();
} 
else if($type == 'TP'){
    $view = new TipologiaPastoView();
    $nome = 'Tipologia Pasto';
?>
    <h1>Pagina dettaglio <?php echo $nome ?></h1>
<?php 
    $view->listenerDettaglioTipologiaPasto();
    $view->printDettaglioTipologiaPasto($id);
    die();
} 
else if($type == 'TR'){
    $view = new RicettaView();
    $nome = 'Tipologia Ricetta';
?>
    <h1>Pagina dettaglio <?php echo $nome ?></h1>
<?php 
    $view->listenerDettaglioTipologiaRicetta();
    $view->printDettaglioTipologiaRicetta($id);
    die();
   
}
else if($type == 'R'){
    $viewR = new RicettaView();
    $nome = 'Ricetta';

?>
    <h1>Pagina dettaglio <?php echo $nome ?></h1>
<?php 
    $viewR->listenerDettaglioRicetta();
    $viewR->printDettaglioRicetta($id);
    die();
}
else if($type == 'A'){
    $viewA = new AgendaView();
    $nome = 'Agenda';

?>
    <h1>Pagina dettaglio <?php echo $nome ?></h1>
<?php 
    $viewA->printDettaglioAgenda($id);
    die();
}
?>


