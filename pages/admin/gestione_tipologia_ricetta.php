<?php
namespace pianificatore_ricette;
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$view = new RicettaView();
$view->listenerAddTipologiaRicettaForm();

?>

<h1>Gestione Tipologia Ricette</h1>

<h3>Inserisci Tipologia Ricetta</h3>
<div class="form-container">
    <?php $view->printAddTipologiaRicettaForm() ?>
</div>

<h3>Visualizza Tipologie Ricetta</h3>
<?php $view->printAllTipologieRicetta() ?>