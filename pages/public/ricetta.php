<?php
namespace pianificatore_ricette;
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

if(!isset($_GET['id'])){
    
    return;
}

global $ADMIN_ID;
$id = $_GET['id'];
$view = new RicettaView();

$rC = new RicettaController();
$r = new Ricetta();
$r = $rC->getRicettaByID($id);

if($r != null){
    $view->listenerDettaglioRicetta();
    if(get_current_user_id() == $r->getIdUtente() || get_current_user_id() == $ADMIN_ID){
    ?>
        <button class="modifica-ricetta">Modifica Ricetta</button>
        <button class="modifica-annulla">Annulla</button>
        <div class="edit-ricetta">
    <?php
        
        $view->printDettaglioRicetta($id);
    ?>
        </div>    
    <?php
    }

    ?>
    <div class="view-ricetta">     
    <?php    
    $view->printPublicRicetta($id);

    ?>
    </div>
<?php
}
else{
    echo '<p>Ricetta non presente nel sistema.</p>';
}
?>
