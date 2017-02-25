<?php
namespace pianificatore_ricette;
/**
 * Description of AgendaView
 *
 * @author Alex
 */
class AgendaView extends PrinterView {
    private $aC;
    private $rC;
    private $tpC;
    private $taC;
    private $form;
    private $label;  
    
       
    function __construct() {
        parent::__construct();
        $this->aC = new AgendaController();
        $this->rC = new RicettaController();
        $this->tpC = new TipologiaPastoController();
        $this->taC = new TemplateAgendaController();
        
        global $FORM_G_NOME, $FORM_G_DATA, $FORM_A_SUBMIT, $FORM_A_NOME, $LABEL_A_NOME, $LABEL_SUBMIT;
        
        $this->form['g-nome'] = $FORM_G_NOME;
        $this->form['g-data'] = $FORM_G_DATA;
        $this->form['a-submit'] = $FORM_A_SUBMIT;
        $this->form['a-nome'] = $FORM_A_NOME;
        
        $this->label['a-nome'] = $LABEL_A_NOME;
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
        $now = date('Y-m-d H:i:s', strtotime("now + 3 day"));        
        $week = date('W', strtotime("now + 3 day"));         
        
        
    ?>       
        <form class="form-horizontal agenda-container pianificatore-ricette" role="form" action="<?php echo curPageURL() ?>" name="form-agenda" method="POST">
            <?php parent::printHiddenFormField('user-id', get_current_user_id()) ?>
            <?php parent::printHiddenFormField('id-week', $week) ?>
            <div class="nome-agenda">
                <?php parent::printTextFormField($this->form['a-nome'], $this->label['a-nome']) ?>
            </div>
            <?php 
                  $dose = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10);
                  parent::printSelectFormField('dose-persone', 'Indica per quante persone', $dose, true, 1);
            ?>
    <?php
        for($i=0; $i < 7; $i++){
           $this->printFormGiorno($now, $i);
        }
    ?>
            <div class="clear"></div>
            <p>Hai terminato di pianificare tutto quello che desideri?</p>
            <?php parent::printSubmitFormField($this->form['a-submit'], 'AVANTI') ?>
            
        </form>    
    <?php        
        
        
    }

    protected function printFormGiorno($now, $i){        
        
        $today = date('N-j-m', strtotime($now.' + '.$i.' day' )); 
        $nome = parent::translateDate($today);   
        $data = date('Y-m-d H:i:s', strtotime($now.' + '.$i.' day' ));   
        
        //ottengo i pasti
        $pasti = $this->tpC->getTipologiaPasti();
        $classe = "";
        if($i % 2 == 0){
            $classe = "pari";
        }
        else{
            $classe = "dispari";
        }
        
    ?>
        <div class="giorno-agenda day-<?php echo $i ?> col-xs-12 <?php echo $classe ?>">
            <?php parent::printHiddenFormField($this->form['g-nome'].'-'.$i, $nome) ?>
            <?php parent::printHiddenFormField($this->form['g-data'].'-'.$i, $data) ?>
            <span class="title-giorno"><?php echo $nome ?></span>
            <?php
                //stampo i pasti
                $countPasti = 0;
                if($pasti != null){
                    foreach($pasti as $pasto){
                        $this->printFormPasto($i, $countPasti, $pasto);
                        $countPasti++;
                    }
                }
            ?>            
            <div class="clear"></div>
        </div>
    <?php
        
    }
    
    
    protected function printFormPasto($i, $j, TipologiaPasto $tp){    
    ?>  
        <div class="pasto col-sm-3 pasto-<?php echo $tp->getID() ?>">
            <?php parent::printHiddenFormField('id-tp-'.$i.'-'.$j, $tp->getID()) ?>
            <span class="title-pasto"><?php echo $tp->getNome() ?></span>
            <div class="lista-ricette">
                <div class="nome-ricetta">
                    <select name="nome-ricetta-<?php echo $i ?>-<?php echo $tp->getID() ?>-1">
                        <option value=""></option>
                    </select>
                    <a title="Rimuovi Ricetta" class="remove-from-pasto"></a>
                </div>
            </div>
            <div class="aggiungi-ricetta">
                <a title="Aggiungi Ricetta">Ricetta</a>
            </div>
        </div>
    <?php
    }
    
    
    public function listenerFormAgenda(){
        if(isset($_POST[$this->form['a-submit']])){
            $dose = $_POST['dose-persone'];
            
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
            $temp = $this->composeAgenda($_POST, $errors);  
            $errors = $temp['errors'];            
            
            if($errors > 0){
                return;
            }
            
            $a = new Agenda();
            $a = $temp['agenda'];
            $a->setDose($dose);
            
            //nome - CAMPO NON OBBLIGATORIO
            if(parent::checkSingleField($this->form['a-nome'])!= false){
                $a->setNome(parent::checkSingleField($this->form['a-nome']));
            }
            
            //salvo l'agenda
            $idAgenda = $this->aC->saveAgenda($a);
            //var_dump($idAgenda);
            if($idAgenda === -1){
                parent::printErrorBoxMessage('Agenda non salvata correttamente!');
                return;
            }
            else if($idAgenda === -2){
                parent::printErrorBoxMessage('Agenda non salvata correttamente! Errore nel salvare il giorno.');
                return;
            }
            else if($idAgenda === -3){
                parent::printErrorBoxMessage('Agenda non salvata correttamente! Errore nel salvare il pasto.');
                return;
            }
            else if($idAgenda === -4){
                parent::printErrorBoxMessage('Agenda non salvata correttamente! Errore nel salvare l\'associazione giorno pasto.');
                return;
            }
            else if($idAgenda === -5){
                parent::printErrorBoxMessage('Agenda non salvata correttamente! Errore nel salvare l\'associazione pasto ricetta');
                return;
            }
            else if($idAgenda > 0){
                //parent::printOkBoxMessage('Agenda salvata correttamente!');
                unset($_POST);                
            }
            
            
            //riprendo l'agenda
            $ag = new Agenda();
            $ag = $this->aC->getAgendaById($idAgenda);
            
            
            //creo il pdf
            $urlPDF = $this->aC->createPDF($ag, $dose);
            
            //aggiorno l'agenda con il pdf
            $ag->setPdf($urlPDF);            
            if($this->aC->updatePDF($ag) != true){
                parent::printErrorBoxMessage('PDF non salvato correttamente nell\'Agenda');                
            }
            
            //pubblico il link per andare alla pagina dettaglio ricetta
            ?>
                <div class="container-link-dettaglio">
                    <h2>La tua Agenda è stata creata!</h2>
                    <p>Puoi consultarla cliccando sul bottone sottostante</p>
                    <a href="<?php echo home_url().'/dettaglio-agenda?id='.$idAgenda ?>">Visualizza l'agenda</a>
                </div>
            <?php
        }
    }
    
    /**
     * La funzione compone un agenda dall'array POST ricevuto
     * @param type $array
     * @param type $errors
     * @return type
     */
    protected function composeAgenda($array, $errors){
        $a = new Agenda();
        $a->setIdUtente($_POST['user-id']);
        $a->setSettimana($_POST['id-week']);

        //devo comporre i giorni
        $giorni = array();
        $countGiorno = 0;
        foreach($array as $key => $value){
            if (strpos($key, 'g-nome') !== false) {
                $g = new Giorno();
                $g->setNome($value);                    
            }
            if (strpos($key, 'g-data') !== false) {
                $g->setData($value);

                //devo comporre il pasto per il giorno
                $pasti = array();
                foreach($array as $key2 => $value2){

                    if(strpos($key2, 'id-tp') !== false) {
                        //cerco di capire se il pasto è quello del giorno corrispondente
                        $temp = explode('-', $key2);                            
                        //il terzo elemento rappresenta il giorno
                        if($temp[2] == $countGiorno){
                            $p = new Pasto();
                            $p->setIdTipologiaPasto($value2);

                            //devo comporre le ricette per pasto
                            $ricette = array();                                
                            foreach($array as $key3 => $value3){
                                if(strpos($key3, 'nome-ricetta-'.$countGiorno.'-'.$value2) !== false) {
                                    //devo ottenere l'id della ricetta dal nome
                                    if(trim($value3 != '')){
                                      
                                        array_push($ricette, $value3);
                                        
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

        //elimino i pasti duplicati
        $a = $this->aC->deletePastiDuplicati($a);
        $result['agenda'] = $a;
        $result['errors'] = $errors;
        
        return $result;
    }
    
    public function printAllAgende(){
        return $this->printTableAgende($this->aC->getAllAgende());
    }
    
    
    public function printLeMieAgende($idUtente){        
        return $this->printTableAgende($this->aC->getUserAgende($idUtente));
    }
    
    public function printTableAgende($agende){
        global $ADMIN_ID;
        
        if(get_current_user_id() == $ADMIN_ID){
            $header = array(
                'ID',
                'Nome',
                'Data di pianificazione',
                'Utente',
                'PDF',
                'Azioni'
            );
        }
        else{
            $header = array( 
                'Agenda',
                'Nome',
                'Data di pianificazione', 
                'Azioni',
                ''
            );
        }
        
        $bodyTable = $this->printBodyTable($agende);
        parent::printTableHover($header, $bodyTable);
    }
    
    protected function printBodyTable($array) {
        global $ADMIN_ID;
        parent::printBodyTable($array);
        $html = "";
        if($array != null){
            $counter = 1;
            foreach($array as $item){
                $a = new Agenda();
                $a = $item;

                $html.='<tr>';
                
                //ID Agenda
                if(get_current_user_id() == $ADMIN_ID){
                    $html.='<td>'.parent::printTextField(null, $a->getID()).'</td>';
                }
                else{
                    $html.='<td>'.parent::printTextField(null, $counter).'</td>';
                }
                
                
                //nome
                $html.='<td>'.parent::printTextField(null, $a->getNome()).'</td>';
                //Caricata
                $html.='<td>'.parent::printTextField(null, getTime($a->getData())).'</td>';
                if(get_current_user_id() == $ADMIN_ID){
                //Utente
                    if($a->getIdUtente() != 0){
                        $user = get_userdata($a->getIdUtente());
                        $html.='<td>'.parent::printTextField(null, $user->user_nicename).'</td>';
                    }
                    else{
                        $html.='<td></td>';
                    }
                }
                if(get_current_user_id() == $ADMIN_ID){
                    //PDF
                    if($a->getPdf() != null){
                        $html.='<td><a target="_blank" href="'.$a->getPdf().'">Apri il PDF</a></td>';
                    }
                    else{
                        $html.='<td></td>';
                    }
                }
                //dettagli
                if(get_current_user_id() == $ADMIN_ID){
                    $html.='<td><a href="'. get_admin_url().'admin.php?page=pr_pagina_dettaglio&type=A&id='.$a->getID().'">Vedi dettagli</a></td>';
                }
                else{
                    $html.='<td><a href="'. home_url().'/dettaglio-agenda?id='.$a->getID().'">Vedi dettagli</a></td>';
                    $html.='<td><form action="'. curPageURL().'" method="POST"><input type="hidden" name="id-agenda" value="'.$a->getID().'" /><input type="submit" name="delete-agenda" class="btn btn-danger" value="ELIMINA" /></form></td>';
                }

                $html.='</tr>';
                $counter++;
            }
        }
        
        return $html;
    }
    
    public function printDettaglioAgenda($ID){
        $a = new Agenda();
        $a = $this->aC->getAgendaById($ID);
        $tps = $this->tpC->getTipologiaPasti();
        
        if($a != null){
    ?>
        <form class="form-horizontal agenda-container" role="form" action="<?php echo curPageURL() ?>" name="form-agenda" method="POST">
            <?php echo parent::printHiddenFormField('id-agenda', $a->getID()) ?>
            <?php parent::printTextFormField($this->form['a-nome'], $this->label['a-nome'], false, $a->getNome()) ?>
            <div class="container-agenda">
                <div class="container-tipologie">
                    <div class="empty-box"></div>
    <?php
                foreach($tps as $tipo){
                    $tp = new TipologiaPasto();
                    $tp = $tipo;
    ?>
                    <div class="tipo-pasto">
                        <h5><?php echo $tp->getNome() ?></h5>
                    </div>
    <?php                
                }
    ?>
                    
                </div>
                <div class="container-giorni">                    
    <?php
                foreach($a->getGiorni() as $giorno){
                    $g = new Giorno();
                    $g = $giorno;
    ?>
                    <div class="giorno">                        
                        <h5><?php echo $g->getNome() ?></h5>
    <?php                        
                        foreach($tps as $tipo){
                            $tp = new TipologiaPasto();
                            $tp = $tipo;
                            if($g->getPasti() != null){
                            foreach($g->getPasti() as $pasto){
                                $p = new Pasto();
                                $p = $pasto;
                                
                                if($tp->getID() === $p->getIdTipologiaPasto()){
    ?>                              
                                    <div class="tipo-pasto">
    <?php
                                    if($p->getRicette() != null){
    ?>
                                        <div class="container-ricette">    
    <?php                                    
                                        foreach($p->getRicette() as $ricetta){
                                            $r = new Ricetta();
                                            $r = $ricetta;
        ?>
                                            <p><?php echo $r->getNome() ?></p>    
        <?php                                    
                                        }
    ?>
                                        </div>    
    <?php                                        
                                    }
    ?>  
                                    </div>  
    <?php                    
                                }
                            }
                            }
                            
    ?>
                          
    <?php
                        }
    ?>
                    </div>
    <?php
                }
    ?>
                </div>
            </div>
            <div class="clear" style="height:30px"></div>            
            <?php echo parent::printDeleteDettaglio('agenda') ?>
        </form>
    <?php        
            
        }
        else{
            echo '<p>Agenda non presente nel sistema.</p>';
        }
    }
    
    
    public function listenerDettaglioAgenda(){
        //Cancellazione
        if(isset($_POST['delete-agenda'])){
            
            //prima di eliminare un'agenda devo vedere se questa è sfruttata in un template
            if($this->taC->isAgendaInTemplate($_POST['id-agenda']) == false){
            
                $delete = $this->aC->deleteAgenda($_POST['id-agenda']);

                if($delete === -1){
                    parent::printErrorBoxMessage('Errore nel cancellare pasti ricette');
                    return;
                }
                else if($delete === -2){
                    parent::printErrorBoxMessage('Errore nel cancellare giorni pasto');
                    return;
                }
                else if($delete === -3){
                    parent::printErrorBoxMessage('Errore nel cancellare i pasti');
                    return;
                }
                else if($delete === -4){
                    parent::printErrorBoxMessage('Errore nel cancellare i giorni');
                    return;
                }
                else if($delete === -5){
                    parent::printErrorBoxMessage('Errore nel cancellare l\'agenda');
                    return;
                }
                else if($delete === -6){
                    parent::printErrorBoxMessage('Errore nel cancellare il pdf');
                    return;
                }
                else if($delete === true){
                    parent::printOkBoxMessage('Agenda eliminata con successo!');
                    unset($_POST);
                    return;
                }
            }
            else{
                parent::printErrorBoxMessage('L\'Agenda non può essere cancellata in quanto è utilizzata per un template');
                return;
            }
        }
    }
    
    public function printDettaglioAgendaPublic(Agenda $a){
        global $IMG_NOT_FOUND;
        $ingredienti = $this->aC->createListaIngredienti($a, $a->getDose());
        
        $tps = $this->tpC->getTipologiaPasti();
        $arrayPasti = array();        
        array_push($arrayPasti, 'Preparazione');        
        foreach($tps as $tipo){
            $tp = new TipologiaPasto();
            $tp = $tipo;
            array_push($arrayPasti, $tp->getNome());
        }       
        
        //creo un array di risultati 
        $result = $this->aC->createArrayCalendario($a, $arrayPasti);
        
        //ottengo info sull'utente
        $user = get_userdata($a->getIdUtente());
        
        //ottengo il periodo
        $firstDay = "";
        $lastDay = "";
        if(count($result) > 0){
            $count = 0;
            //print_r($result);
            foreach($result as $giorni){               
                foreach($giorni as $keyG => $valueG){
                    if($count == 0){
                        $firstDay = $keyG;
                    }
                    if($count == count($result) - 1){
                        $lastDay = $keyG;
                    }
                }
                $count++;
            }
        }
        
        $dose = $a->getDose();
        if($a->getDose() > 1){
            $dose .= ' persone';
        }
        else{
            $dose .= ' persona';
        }
        
    ?>
        
        <h2 class="title">Agenda settimanale di <?php echo $user->user_nicename ?></h2> 
        <div class="descrizione-agenda">
            <p>Periodo: <?php echo $firstDay ?> - <?php echo $lastDay ?></p>
            <p>La dose indicata per le ricette è per <?php echo $dose ?></p>
        </div>
        <h3 class="title">Calendario</h3>
        
        <div class="container-agenda-public hidden-xs">
            <div class="container-tp">
                <div class="tp">&nbsp;</div>                
    <?php
            foreach($arrayPasti as $pasto){               
    ?>
                <div class="tp"><?php echo $pasto ?></div>
    <?php            
            }
    ?>
            </div>
    <?php
        //print_r($result);
        if(count($result) > 0){ 
            $counterDay = 0;
            foreach($result as $giorni){                
                foreach($giorni as $keyG => $valueG){
                    //print_r($valueG);
                    $giorno = 'dispari';
                    if($counterDay % 2 == 0){
                        $giorno = 'pari';
                    }
    ?>
            <div class="giorno <?php echo $giorno ?>">
                <div class="tp nome-giorno">
                    <p><?php echo $keyG ?></p>
                </div>
    <?php
                    foreach($arrayPasti as $pasto){
    ?>
                <div class="tp <?php echo strtolower($pasto) ?>">
                <?php
                    echo '<div class="container-elemento">';
                    foreach($valueG[$pasto] as $item){
                        if($pasto != 'Preparazione'){  
                            $idRicetta = $this->rC->getIdRicettaByNome($item);
                            $r = new Ricetta();
                            $r = $this->rC->getRicettaByID($idRicetta);
                            
                            $urlFoto = $IMG_NOT_FOUND;
                            if($r->getFoto() != null && $r->getFoto() != '') {
                                $urlFoto = $r->getFoto();
                            }
                            
                        ?>
                            <div class="ricetta">                                
                                <a target="_blank" href="<?php echo home_url() ?>/ricetta?id=<?php echo $idRicetta ?>">
                                    <img class="img-ricetta" alt="<?php echo $item ?>" title="<?php echo $item ?>" src="<?php echo $urlFoto ?>"/>                                                
                                </a>                                
                                <a style="display:block;" target="_blank" href="<?php echo home_url() ?>/ricetta?id=<?php echo $idRicetta ?>">
                                    <?php echo $item ?>
                                </a>
                            </div>
                        <?php                                       
                        }
                        else{
                            echo $item.'<br>';                                                           
                        }
                    }                    
                    echo '</div>';                    
                ?>
                </div>
    <?php            
                    }
    ?>                
            </div>
    <?php       
                    $counterDay++;
                }
            }
        }
    ?>        
        </div>
    
        <div class="container-agenda-public-mobile visible-xs">
    <?php
        //stampo l'agenda per il mobile
        //ottengo il calendario
        $calendario = $this->aC->createAgenda($a);
        
        //ottengo le tipologie pasti
        $tps = $this->tpC->getTipologiaPasti();
        
        foreach($calendario as $data => $dataValue){
    ?>
            <div class="giorno col-xs-12">
                <?php echo $data ?>
            </div>
    <?php
            if(isset($calendario[$data]['Preparazione'])){ 
    ?>
                <div class="pasto col-xs-12">Preparazioni</div>
                <div class="descrizione-pasto col-xs-12">
    <?php
                foreach($calendario[$data]['Preparazione'] as $preparazioni){              
                    foreach($preparazioni as $preparazione){
                        echo '<p>'.$preparazione.'</p>';
                    }                  
                }
    ?>
                </div>    
    <?php
            }
            
            //ciclo sui pasti
            foreach($tps as $tipoPasto){
                $tp = new TipologiaPasto();
                $tp = $tipoPasto;
                if(isset($calendario[$data][$tp->getNome()])){   
    ?>
                    <div class="pasto col-xs-12"><?php echo $tp->getNome() ?></div>
                    <div class="descrizione-pasto col-xs-12">
    <?php
                   foreach($calendario[$data][$tp->getNome()] as $tipi){
                       foreach($tipi as $tipo ){
                            $idRicetta = $this->rC->getIdRicettaByNome($tipo);
                            $r = new Ricetta();
                            $r = $this->rC->getRicettaByID($idRicetta);
    ?>
                            <div class="ricetta">
                                <?php if($r->getFoto() != null && $r->getFoto() != '') { ?>
                                    <a target="_blank" href="<?php echo home_url() ?>/ricetta?id=<?php echo $idRicetta ?>">
                                        <img class="img-ricetta" alt="<?php echo $tipo ?>" title="<?php echo $tipo ?>" src="<?php echo $r->getFoto() ?>"/>                                                
                                    </a>
                                <?php } ?>
                                <a style="display:block;" target="_blank" href="<?php echo home_url() ?>/ricetta?id=<?php echo $idRicetta ?>">
                                    <?php echo $tipo ?>
                                </a>
                            </div>
    <?php                 
                       }
                   } 
    ?>
                    </div>
    <?php                
                }
            }
        }        
    ?>  
        </div>
        
        <div class="clear"></div>
        <h3 class="title">Ingredienti</h3>
        <div class="container-ingredienti col-xs-12 ">
    <?php
            $count = 0;
            foreach($ingredienti as $key => $value){
                $classe = 'dispari';
                if($count % 2 == 0){
                    $classe = 'pari';
                }
                $string = ""; 
                if($value['qt']!= '' && $value['qt']!= '0'){
                    $string.= $value['qt'].' ';
                }

                if($value['um']!='' && $value['um']!='q.b.'){
                    $string.= $value['um'].' ';
                }           

                if($key != ''){
                    $string.= $key;
                }            
    ?>
            <div class="col-xs-12 col-sm-6 ingrediente <?php echo $classe ?>">
                <?php echo $string ?>
            </div>
    <?php
            
                $count++;
            }
    ?>
        </div>
    <?php         
        
         
    }
    
    
    public function printSelectTemplate(){
        $temp = $this->taC->getTemplateAgenda();
        
        //MODIFICA COMPORTAMENTO
        //vengono visualizzati i template che sono in un determinato arco temporale
        //oppure quelli che non posseggono un arco temporale
        
        date_default_timezone_set('Europe/Rome');
        $now = date('Y-m-d', strtotime("now"));
        $currentYear = date('Y', strtotime("now"));
        
        if($temp != null){
            $result = array();
            foreach($temp as $item){
                $ta = new TemplateAgenda();
                $ta = $item;
                
                $start = date('Y-m-d', strtotime($ta->getInizio().'/01/'.$currentYear));
                
                $monthEnd = '31';
                if($ta->getFine() == '11' || $ta->getFine() == '04' || $ta->getFine() == '06' || $ta->getFine() == '09'){
                    $monthEnd = '30';
                }
                else if($ta->getFine() == '02'){
                    $monthEnd = '28';
                }
                
                
                $end = date('Y-m-d', strtotime($ta->getFine().'/'.$monthEnd.'/'.$currentYear));
                if($ta->getFine() < $ta->getInizio()){
                    //ho passato l'anno
                    $end = date('Y-m-d', strtotime($ta->getFine().'/'.$monthEnd.'/'.($currentYear+1)));
                }
                
                //print_r($now.';'.$start.';'.$end.'<br>');
                
                if($ta->getInizio() == 0 && $ta->getFine() == 0){
                    $result[$ta->getID()] = $ta->getNome();
                }
                else if($now >= $start && $now <= $end){                    
                    $result[$ta->getID()] = $ta->getNome();
                }
            }
    ?>
        
            <?php parent::printSelectFormField('ricerca-template', 'Seleziona', $result) ?>
            <div class="col-xs-12">
                <button class="btn carica-template">Carica agenda</button>
            </div>
       
    <?php
        }
        else{
            echo '<p>Nessun template caricato</p>';
        }
    }
   
}
