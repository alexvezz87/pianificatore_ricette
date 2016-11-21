<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$view = new RicettaView();
$view->listenerAddRicettaForm();
?>

<h1>Gestione Ricette</h1>

<h3>Inserisci Ricetta</h3>
<div class="form-container">
    <?php $view->printAddRicettaForm() ?>
</div>

<div class="clear"></div>
<h3>Visualizza Ricette</h3>
<?php $view->printAllRicette() ?>