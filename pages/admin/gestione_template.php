<?php
namespace pianificatore_ricette;
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$view = new TemplateAgendaView();
$view->listenerAddTemplateAgendaForm();

?>
<h1>Gestione Template Agende</h1>
<div class="form-container">
    <?php $view->printAddTemplateAgendaForm() ?>
</div>

<h3>Visualizza Template Agenda</h3>
<?php $view->printAllTemplateAgenda() ?>