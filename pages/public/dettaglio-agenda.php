<?php
namespace pianificatore_ricette;
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/


function printAgendaPublic($mode = null){
    if(isset($_GET['id']) && is_user_logged_in()){
        $id = $_GET['id'];

        //ottengo i dati dell'utente corrente
        global $current_user;
        get_currentuserinfo();

        $aC = new AgendaController();
        $view = new AgendaView();

        $a = new Agenda();
        $a = $aC->getAgendaById($id);
        if($a != null){
            if($a->getIdUtente() == $current_user->ID){

                
               if($mode == null){
                   //bottoni link per utente premium
                   echo '<div class="fusion-button-wrapper">
                            <style type="text/css" scoped="scoped">
                                .fusion-button.button-1{width:auto;}
                            </style>
                            <a class="fusion-button button-flat button-pill button-medium button-lightgray button-1 fusion-animated" data-animationtype="fadeInLeft" data-animationduration="0.3" data-animationoffset="100%" target="_self" href="'.home_url().'/pianificatore/" style="visibility: visible; animation-duration: 0.3s;">
                                <i class="fa fa-reply button-icon-left"></i>
                                <span class="fusion-button-text">Torna al Pianificatore</span>
                            </a>
                        </div>';
                   
                   echo '<div class="fusion-button-wrapper"><style type="text/css" scoped="scoped">.fusion-button.button-2{width:auto;}</style><a class="fusion-button button-flat button-pill button-medium button-lightgray button-2 fusion-animated" data-animationtype="fadeInLeft" data-animationduration="0.3" data-animationoffset="100%" target="_self" href="'. home_url().'/pagina-personale/" style="visibility: visible; animation-duration: 0.3s;"><i class="fa fa-calendar button-icon-left"></i><span class="fusion-button-text">Vai alle mie Agende salvate</span></a></div>';
                   
                   echo '<div class="fusion-button-wrapper"><style type="text/css" scoped="scoped">.fusion-button.button-4{width:auto;}</style>';
                   echo '<button class="fusion-button button-flat button-pill button-xlarge button-orange button-4 fusion-animated" data-animationtype="fadeInLeft" data-animationduration="0.3" data-animationoffset="100%" target="_self" id="printbutton" onclick="location.href=\''.$a->getPdf().'\'">STAMPA</button>';
                   echo '</div>';
                   
               }
               else{
                   //bottoni link utente standard
                   
                   echo '<div class="fusion-button-wrapper"><style type="text/css" scoped="scoped">.fusion-button.button-3{width:auto;}</style><a class="fusion-button button-flat button-pill button-medium button-lightgray button-3 fusion-animated" data-animationtype="fadeInLeft" data-animationduration="0.3" data-animationoffset="100%" target="_self" href="'. home_url().'/upgrade-to-premium/" style="visibility: visible; animation-duration: 0.3s;"><i class="fa fa-calendar button-icon-left"></i><span class="fusion-button-text">Vai alle mie Agende salvate</span></a></div>';
                   echo '<div class="fusion-button-wrapper"><style type="text/css" scoped="scoped">.fusion-button.button-4{width:auto;}</style><a class="fusion-button button-flat button-pill button-xlarge button-orange button-4 fusion-animated" data-animationtype="fadeInLeft" data-animationduration="0.3" data-animationoffset="100%" target="_self" href="'. home_url().'/upgrade-to-premium/" style="visibility: visible; animation-duration: 0.3s;"><i class="fa fa-file-o button-icon-left"></i><span class="fusion-button-text">STAMPA in modo ordinato (un foglio per l’agenda e uno per gli ingredienti)</span></a></div>';
               }
                
               $view->printDettaglioAgendaPublic($a);

            }
            else{
                //se l'agenda in questione non è associata all'utente corrente allora non mostro i contenuti
                echo '<p>Non sei autorizzato ad accedere a questa pagina</p>';
            }
        }
        else{
            echo '<p>L\'Agenda che si vuole visualizzare non è presente nel sistema.';
        }
    }
    else{
        //se atterro su questa pagina senza un ID, non mostro i contenuti
        echo '<p>Non sei autorizzato ad accedere a questa pagina</p>';
    }
}
?>