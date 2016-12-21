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
                   echo '<button id="printbutton" onclick="location.href=\''.$a->getPdf().'\'">STAMPA</button>';
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