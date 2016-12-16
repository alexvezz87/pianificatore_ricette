<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/
namespace pianificatore_ricette;

$view = new RicettaView();
$view->listenerAddRicettaForm();

?>
<h2>Inserisci la tua ricetta</h2>
<div class="form-container">
<?php $view->printAddRicettaForm() ?>
</div>