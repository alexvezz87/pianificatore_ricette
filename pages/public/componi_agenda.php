<?php
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

namespace pianificatore_ricette;

function printPaginaAgenda($mode=null){
    
    //mode assume null in caso di utenza premium
    //mode assume 's' in caso di utenza standard

    $view = new AgendaView();
    $ricette = new RicettaView();
    $class = "premium";
    if($mode == 's'){
        $class="standard";
    }

    ?>

    <div class="mycontainer cointainer-<?php echo $class ?>">
        <div class="loader-container">
            <div class="loader"></div>
        </div>
        
        <!-- VIDEO -->
        <div class="accordian fusion-accordian"><div class="panel-group" id="accordion-997-1"><div class="fusion-panel panel-default fusion-toggle-no-divider"><div class="panel-heading"><h4 class="panel-title toggle" data-fontsize="20" data-lineheight="30"><a data-toggle="collapse" data-parent="#accordion-997-1" data-target="#e4f43845c18854f66" href="#e4f43845c18854f66" class="collapsed"><div class="fusion-toggle-icon-wrapper"><i class="fa-fusion-box"></i></div><div class="fusion-toggle-heading">PIANIFICATORE: Guida Video</div></a></h4></div><div id="e4f43845c18854f66" class="panel-collapse collapse" style="height: 0px;"><div class="panel-body toggle-content"><br>
<div class="fusion-video fusion-youtube" style="max-width:600px;max-height:360px;"><div class="video-shortcode"><div class="fluid-width-video-wrapper" style="padding-top: 60%;"><iframe title="YouTube video player" src="http://www.youtube.com/embed/yt6oIlxiZz8?wmode=transparent&amp;autoplay=0&amp;rel=0&amp;; modestbranding=1" allowfullscreen="" name="fitvid0"></iframe></div></div></div><p></p>
<p></p></div></div></div></div></div>
        <!-- FINE VIDEO -->
        
        <!-- Link -->
        <?php if($mode==null){ ?>
            <div class="fusion-button-wrapper">
                <style type="text/css" scoped="scoped">
                    .fusion-button.button-1{width:auto;}
                </style>
                <a class="fusion-button button-flat button-pill button-large button-lightgray button-1 fusion-animated" data-animationtype="fadeInLeft" data-animationduration="0.3" data-animationoffset="100%" target="_self" href="<?php echo home_url() ?>/aggiungi-ricetta/" style="visibility: visible; animation-duration: 0.3s;">
                    <i class="fa fa-pencil-square-o button-icon-left"></i>
                    <span class="fusion-button-text">Aggiungi le tue&nbsp;Ricette</span>
                </a>
            </div>
        <?php } else{ ?>
            <div class="fusion-button-wrapper">
                <style type="text/css" scoped="scoped">
                    .fusion-button.button-2{width:auto;}
                </style>
                <a class="fusion-button button-flat button-pill button-large button-lightgray button-2 fusion-animated" data-animationtype="fadeInLeft" data-animationduration="0.3" data-animationoffset="100%" target="_self" href="<?php echo home_url() ?>/upgrade-to-premium/" style="visibility: visible; animation-duration: 0.3s;">
                    <i class="fa fa-folder-open-o button-icon-left"></i>
                    <span class="fusion-button-text">Altre Ricette dagli Utenti</span>
                </a>
            </div>

            <div class="fusion-button-wrapper">
                <style type="text/css" scoped="scoped">
                    .fusion-button.button-3{width:auto;}
                </style>
                <a class="fusion-button button-flat button-pill button-large button-darkgray button-3 fusion-animated" data-animationtype="fadeInLeft" data-animationduration="0.3" data-animationoffset="100%" target="_self" href="<?php echo home_url() ?>/upgrade-to-premium/" style="visibility: visible; animation-duration: 0.3s;">
                    <i class="fa fa-pencil-square-o button-icon-left"></i>
                    <span class="fusion-button-text">Aggiungi le tue Ricette</span>
                </a>
            </div>

            <div class="fusion-button-wrapper">
                <style type="text/css" scoped="scoped">
                    .fusion-button.button-4{width:auto;}
                </style>
                <a class="fusion-button button-flat button-pill button-xlarge button-orange button-4 fusion-animated" data-animationtype="fadeInLeft" data-animationduration="0.3" data-animationoffset="100%" target="_self" href="<?php echo home_url() ?>/upgrade-to-premium/" style="visibility: visible; animation-duration: 0.3s;">
                    <i class="fa fa-calendar button-icon-left"></i>
                    <span class="fusion-button-text">RISPARMIA TEMPO: Carica un'Agenda già fatta</span>
                </a>
            </div>
        <?php } ?>

        <h1>Il Pianificatore</h1>
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

        <h1>Alcune ricette scelte a caso per te</h1>
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


        <div class="" id="selezionatore-ricette">
            <div class="oc-button open">

            </div>

            <h3>Selezione ricette</h3>
            <div class="lista">

            </div>
            <div class="clear"></div>
            <div class="azioni">
                <p>Hai inserito tutte le ricette che desideri pianificare?</p>
                <button type="button" class="btn btn-success prosegui-agenda">Prosegui all'agenda</button>
                <!--<button type="button" class="btn btn-danger cancella-lista">Cancella</button>-->
            </div>
        </div>

        <div class="clear"></div>
        <?php 
            $view->listenerFormAgenda();
        ?>

        <h1 id="pianificatore-ricette" class="pianificatore-ricette">Pianifica le ricette durante la settimana</h1>

        <?php $view->printFormAgenda() ?>
    </div>

<?php
}
?>