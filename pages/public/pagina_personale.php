<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

namespace pianificatore_ricette;

//La pagina ha lo scopo di visualizzare le agende e le ricette di un determinato utente


//ottengo i dati dell'utente corrente
global $current_user;
get_currentuserinfo();

$aView = new AgendaView();
$rView = new RicettaView();

$aView->listenerDettaglioAgenda();
   
?>
<h2>Le mie agende</h2>
<div class="container-mie-agende"> 
<?php echo $aView->printLeMieAgende($current_user->ID) ?>
</div>

<h2>Le mie Ricette</h2>
<div class="container-mie-ricette">
<?php echo $rView->printRicetteByUtente($current_user->ID) ?>
</div>