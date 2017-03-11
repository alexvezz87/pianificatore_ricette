<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/
namespace pianificatore_ricette;

$view = new RicettaView();
$view->listenerAddRicettaForm();

?>

<h4 data-fontsize="20" data-lineheight="30"><strong>NOTA BENE:</strong> Per fare in modo che il pianificatore funzioni correttamente quando inserisci&nbsp;gli ingredienti di una ricetta cerca di essere il più specifico possibile, utilizza unità di misura standard come gr (grammi) ml (millilitri) tutte le volte che puoi, in alternativa puoi utilizzare “cucchiai” o “cucchiaini”, “pizzico” “foglie”.</h4>
<h4 data-fontsize="20" data-lineheight="30">Se inserisci un ingrediente che non ha unità di misura come ad esempio “una mela” lascia semplicemente vuoto il campo “unità di misura”</h4>
<br><br>
<div class="insert-ricetta-public">
    <h2 class="titolo">Inserisci la tua ricetta</h2>
    <div class="form-container">
    <?php $view->printAddRicettaForm() ?>
    </div>
</div>