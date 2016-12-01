<?php
namespace pianificatore_ricette;
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$view = new IngredienteView();

$view->listenerAddIngredienteForm();

?>

<h1>Gestione Ingredienti</h1>

<h3>Inserisci Ingrediente</h3>
<div class="form-container">
    <?php $view->printAddIngredienteForm(); ?>
</div>

<h3>Visualizza ingredienti</h3>
<?php $view->printAllIngredienti() ?>