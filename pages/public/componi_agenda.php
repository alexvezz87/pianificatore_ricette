<?php

namespace pianificatore_ricette;
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$view = new AgendaView();
$ricette = new RicettaView();

?>

<h1>Le ricette</h1>
<div class="container-ricette">
<?php
    $ricette->printShowPublicRicette();
?>    
</div>

<div class="clear"></div>
<div class="ricerca-ricette">
    <h3>Ricerca</h3>
    <?php
        $ricette->printFormRicerca();
    ?>
</div>

<div class="clear"></div>
<div class="col-xs-12 col-sm-6" id="selezionatore-ricette">
    <h4>Selezione ricette</h4>
    <div class="lista">
        
    </div>
    <div class="clear"></div>
    <div class="azioni">
        <button type="button" class="btn btn-success prosegui-agenda">Prosegui</button>
        <button type="button" class="btn btn-danger cancella-lista">Cancella</button>
    </div>
</div>

<div class="col-xs-12 col-sm-6" id="ricerca-template">
    <?php $view->printSelectTemplate() ?>
</div>

<div class="clear"></div>
<?php 
    $view->listenerFormAgenda();
?>

<h1>Pianifica le ricette durante la settimana</h1>

<?php $view->printFormAgenda() ?>