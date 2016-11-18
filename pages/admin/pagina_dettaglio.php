<?php

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
    
} 
else if($type == 'TP'){
    $view = new TipologiaPastoView();
    $nome = 'Tipologia Pasto';
?>
    <h1>Pagina dettaglio <?php echo $nome ?></h1>
<?php 
    $view->listenerDettaglioTipologiaPasto();
    $view->printDettaglioTipologiaPasto($id);
} 
else if($type == 'TR'){
    $view = new RicettaView();
    $nome = 'Tipologia Ricetta';
?>
    <h1>Pagina dettaglio <?php echo $nome ?></h1>
<?php 
    $view->listenerDettaglioTipologiaRicetta();
    $view->printDettaglioTipologiaRicetta($id);
   
}

?>


