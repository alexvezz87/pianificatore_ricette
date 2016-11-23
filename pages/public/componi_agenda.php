<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$view = new AgendaView();
$view->listenerFormAgenda();

?>

<h1>Pianifica le ricette durante la settimana</h1>

<?php $view->printFormAgenda() ?>