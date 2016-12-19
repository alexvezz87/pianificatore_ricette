<?php

namespace pianificatore_ricette;
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$view = new AgendaView();
$ricette = new RicettaView();

?>

<div class="loader-container">
    <div class="loader"></div>
</div>
<h1>Le ricette</h1>
<div class="container-ricette">
<?php
    $ricette->printShowPublicRicette();
?>    
</div>

<div class="clear"></div>
<div class="ricerca-ricette col-sm-6">
    <h3>Ricerca le ricette</h3>
    <?php
        $ricette->printFormRicerca();
    ?>
</div>
<div class="col-xs-12 col-sm-6" id="ricerca-template">
    <h3>Utilizza un'agenda già fatta</h3>
    <?php $view->printSelectTemplate() ?>
</div>
<div class="clear"></div>
<div class="col-xs-12 container-risultati"></div>


<div class="col-xs-12 col-sm-3" id="selezionatore-ricette">
    <div class="oc-button open">
        
    </div>
    
    <h3>Selezione ricette</h3>
    <div class="lista">
        
    </div>
    <div class="clear"></div>
    <div class="azioni">
        <button type="button" class="btn btn-success prosegui-agenda">Prosegui</button>
        <button type="button" class="btn btn-danger cancella-lista">Cancella</button>
    </div>
</div>

<div class="clear"></div>
<?php 
    $view->listenerFormAgenda();
?>

<h1 class="pianificatore-ricette">Pianifica le ricette durante la settimana</h1>

<?php $view->printFormAgenda() ?>