<?php

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
<?php 
    $view->listenerFormAgenda();
?>

<h1>Pianifica le ricette durante la settimana</h1>

<?php $view->printFormAgenda() ?>