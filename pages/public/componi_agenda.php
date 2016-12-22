<?php
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

namespace pianificatore_ricette;

function printPaginaAgenda($mode=null){
    
    //mode assume null in caso di utenza premium
    //mode assume 's' in caso di utenza standard

    $view = new AgendaView();
    $ricette = new RicettaView();

    ?>

    <div class="loader-container">
        <div class="loader"></div>
    </div>
    <h1>Le ricette</h1>
    <div class="container-ricette">
    <?php
        if($mode == null){
            $ricette->printShowPublicRicette();
        }
        else{
            $ricette->printShowPublicRicette($mode);
        }
    ?>    
    </div>

    <div class="clear"></div>
    <div class="ricerca-ricette col-sm-6">
        <h3 class="titolo">Ricerca le ricette</h3>
        <?php
            if($mode == null){
                $ricette->printFormRicerca();
            }
            else{
                $ricette->printFormRicerca($mode);
            }
        ?>
    </div>
   
    <?php if($mode == null){ ?>
    <div class="col-xs-12 col-sm-6" id="ricerca-template">
        <h3 class="titolo">Utilizza un'agenda già fatta</h3>
        <?php $view->printSelectTemplate() ?>
    </div>
    <?php } ?>
    <div class="clear"></div>
    
    <div class="col-xs-12 container-risultati"></div>


    <div class="" id="selezionatore-ricette">
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

<?php
}
?>