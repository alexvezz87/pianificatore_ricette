<?php
namespace pianificatore_ricette;
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$view = new RicettaView();
$view->listenerAddRicettaForm();
?>

<h1>Gestione Ricette</h1>

<div class="open-close-insert-ricetta down"></div>
<h3 class="title-inserisci">Inserisci Ricetta</h3>
<div class="inser-ricetta-admin">    
    <div class="form-container">
        <?php $view->printAddRicettaForm() ?>
    </div>
    <div class="clear"></div>
</div>
<hr style="clear:both; height: 1px">

<div class="clear"></div>
<h3>Visualizza Ricette NON Approvate</h3>
<div class="container-ricette-admin">
<?php $view->printRicetteNonApprovate() ?>
</div>

<h3>Visualizza Ricette Approvate</h3>
<div class="container-ricette-admin">
<?php $view->printRicetteApprovate() ?>
</div>