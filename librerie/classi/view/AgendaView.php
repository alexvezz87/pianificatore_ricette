<?php

/**
 * Description of AgendaView
 *
 * @author Alex
 */
class AgendaView extends PrinterView {
    private $aC;
    private $rC;
    private $tpC; 
    private $form;
    private $label;  
    
       
    function __construct() {
        parent::__construct();
        $this->aC = new AgendaController();
        $this->rC = new RicettaController();
        $this->tpC = new TipologiaPastoController();
        
        global $FORM_G_NOME, $FORM_G_DATA, $FORM_A_SUBMIT, $LABEL_SUBMIT;
        
        $this->form['g-nome'] = $FORM_G_NOME;
        $this->form['g-data'] = $FORM_G_DATA;
        $this->form['a-submit'] = $FORM_A_SUBMIT;
        
        $this->label['submit'] = $LABEL_SUBMIT;
    }
    
    
    private function getNomiRicette($ID){
        $result = array();
        $ricette = $this->rC->getRicetteByUtente($ID);
        
        foreach($ricette as $ricetta){
            $r = new Ricetta();
            $r = $ricetta;
            array_push($result, $r->getNome());
        }
        
        return $result;
    }
    
    public function printFormAgenda(){
        //ottengo la data di oggi
        date_default_timezone_set('Europe/Rome');        
        $now = date('Y-m-d H:i:s', strtotime("now"));        
        $week = date('W', strtotime("now")); 
        
        $ID = 1; //l'ID dell'amministratore **Da modificare nel caso**
        $ricette = $this->getNomiRicette($ID);
        
    ?>
        <script>
            jQuery( function($) {
                var ricette = [
    <?php
                $count = 0;
                foreach($ricette as $r){
                    if($count < count($ricette) -1){
                        echo '"'.$r.'",';
                    }
                    else{                        
                        echo '"'.$r.'"';
                    }
                    $count++;
                }
    ?>
                ];
                
                $(document.body).on('focus', '.nome-ricetta input', function(){
                    $(this).autocomplete({
                        source: ricette,
                        appendTo:$(this).siblings('.suggerimenti')
                    });
                });
          } );
        </script>


        <form class="form-horizontal agenda-container" role="form" action="<?php echo curPageURL() ?>" name="form-agenda" method="POST">
            <?php parent::printHiddenFormField('user-id', get_current_user_id()) ?>
            <?php parent::printHiddenFormField('id-week', $week) ?>
    <?php
        for($i=0; $i < 7; $i++){
           $this->printFormGiorno($now, $i);
        }
    ?>
            <div class="clear"></div>
            <?php parent::printSubmitFormField($this->form['a-submit'], $this->label['submit']) ?>
            
        </form>    
    <?php        
        
        
    }

    protected function printFormGiorno($now, $i){        
        
        $today = date('N-j-m', strtotime($now.' + '.$i.' day' )); 
        $nome = parent::translateDate($today);   
        $data = date('Y-m-d H:i:s', strtotime($now.' + '.$i.' day' ));   
        
        //ottengo i pasti
        $pasti = $this->tpC->getTipologiaPasti();
        
    ?>
        <div class="giorno-agenda col-xs-12">
            <?php parent::printHiddenFormField($this->form['g-nome'].'-'.$i, $nome) ?>
            <?php parent::printHiddenFormField($this->form['g-data'].'-'.$i, $data) ?>
            <h5><?php echo $nome ?></h5>
            <?php
                //stampo i pasti
                $countPasti = 0;
                foreach($pasti as $pasto){
                    $this->printFormPasto($i, $countPasti, $pasto);
                    $countPasti++;
                }
            ?>            
            <div class="clear"></div>
        </div>
    <?php
        
    }
    
    
    protected function printFormPasto($i, $j, TipologiaPasto $tp){    
    ?>  
        <div class="pasto col-sm-3">
            <?php parent::printHiddenFormField('id-tp-'.$i.'-'.$j, $tp->getID()) ?>
            <p><?php echo $tp->getNome() ?></p>
            <div class="lista-ricette">
                <div class="nome-ricetta ui-widget">
                    <input type="text" name="nome-ricetta-<?php echo $i ?>-<?php echo $tp->getID() ?>-1" />
                    <div class="suggerimenti"></div>
                </div>
            </div>
            <div class="aggiungi-ricetta">
                <a>+ Ricetta</a>
            </div>
        </div>
    <?php
    }
    
    
    public function listenerFormAgenda(){
        if(isset($_POST[$this->form['a-submit']])){
            
            //print_r($_POST);
            
            $errors = 0;
            
            //faccio un controllo sui nomi ricetta passati (almeno uno deve essere compilato)
            $empty = true;
            foreach($_POST as $key => $value){
                if(strpos($key, 'nome-ricetta') !== false) {
                    if(trim($value) != ''){
                        $empty = false;
                    }
                }
            }
            
            if($empty == true){
                $errors++;
                parent::printErrorBoxMessage('L\'Agenda deve avere almeno un campo compilato!');                                                
            }
                        
            //devo comporre un'agenda nuova
            $a = new Agenda();
            $a->setIdUtente($_POST['user-id']);
            $a->setSettimana($_POST['id-week']);
            
            //devo comporre i giorni
            $giorni = array();
            $countGiorno = 0;
            foreach($_POST as $key => $value){
                if (strpos($key, 'g-nome') !== false) {
                    $g = new Giorno();
                    $g->setNome($value);                    
                }
                if (strpos($key, 'g-data') !== false) {
                    $g->setData($value);
                    
                    //devo comporre il pasto per il giorno
                    $pasti = array();
                    foreach($_POST as $key2 => $value2){
                        
                        if(strpos($key2, 'id-tp') !== false) {
                            //cerco di capire se il pasto è quello del giorno corrispondente
                            $temp = explode('-', $key2);                            
                            //il terzo elemento rappresenta il giorno
                            if($temp[2] == $countGiorno){
                                $p = new Pasto();
                                $p->setIdTipologiaPasto($value2);
                                
                                //devo comporre le ricette per pasto
                                $ricette = array();                                
                                foreach($_POST as $key3 => $value3){
                                    if(strpos($key3, 'nome-ricetta-'.$countGiorno.'-'.$value2) !== false) {
                                        //devo ottenere l'id della ricetta dal nome
                                        if(trim($value3 != '')){
                                            $idRicetta = $this->rC->getIdRicettaByNome($value3);
                                            if($idRicetta != null){
                                                array_push($ricette, $idRicetta);
                                            }
                                            else{
                                                parent::printErrorBoxMessage('Ricetta "'.$value3.'" non riconosciuta');
                                                $errors++;
                                            }
                                        }
                                    }
                                }                                
                                $p->setRicette($ricette);
                                array_push($pasti, $p);
                                unset($p);
                            }                            
                        }
                    }
                    $g->setPasti($pasti);
                    array_push($giorni, $g);
                    $countGiorno++;
                    unset($g);
                }
            }
            
            $a->setGiorni($giorni);
            
            //print_r($a); 
            
            if($errors > 0){
                return;
            }
            
            //salvo l'agenda
            $save = $this->aC->saveAgenda($a);
            if($save === -1){
                parent::printErrorBoxMessage('Agenda non salvata correttamente!');
                return;
            }
            else if($save === -2){
                parent::printErrorBoxMessage('Agenda non salvata correttamente! Errore nel salvare il giorno.');
                return;
            }
            else if($save === -3){
                parent::printErrorBoxMessage('Agenda non salvata correttamente! Errore nel salvare il pasto.');
                return;
            }
            else if($save === -4){
                parent::printErrorBoxMessage('Agenda non salvata correttamente! Errore nel salvare l\'associazione giorno pasto.');
                return;
            }
            else if($save === -5){
                parent::printErrorBoxMessage('Agenda non salvata correttamente! Errore nel salvare l\'associazione pasto ricetta');
                return;
            }
            else if($save === true){
                parent::printOkBoxMessage('Agenda salvata correttamente!');
                unset($_POST);
                return;
            }
            
        }
    }
   
}
