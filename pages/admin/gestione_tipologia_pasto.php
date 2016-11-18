<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$view = new TipologiaPastoView();
$view->listenerAddTipologiaPastoForm();
?>

<h1>Gestione Tipologia Pasto</h1>

<h3>Inserisci Tipologia Pasto</h3>
<div class="form-container">
    <?php $view->printAddTipologiaPastoForm() ?>
</div>

<h3>Visualizza Tipologie Pasto</h3>
<?php $view->printAllTipologiePasto() ?>