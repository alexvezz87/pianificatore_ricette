<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$type = $_GET['type'];
$id = $_GET['id'];
$nome = "";

?>
<div class="back" style="margin-top:20px">
    <a href="<?php echo admin_url() ?>/admin.php?page=ingredienti"><<<< Torna alla pagina precedente</a>
</div>
<div class="dettaglio-utente">
<?php

if($type == 'ING'){
    $view = new IngredienteView();
    $nome = 'Ingrediente';
?>

    <h1>Pagina dettaglio <?php echo $nome ?></h1>
    <?php 
        $view->listenerDettaglioIngrediente();
        $view->printDettaglioIngrediente($id);            
    ?>

<?php } ?>

