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
        $now = date('Y-m-d H:i:s', strtotime("now"));        
        $week = date('W', strtotime("now"));         
        
        
    ?>       
        <form class="form-horizontal agenda-container" role="form" action="<?php echo curPageURL() ?>" name="form-agenda" method="POST">
            <?php parent::printHiddenFormField('user-id', get_current_user_id()) ?>
            <?php parent::printHiddenFormField('id-week', $week) ?>
            <?php parent::printTextFormField($this->form['a-nome'], $this->label['a-nome']) ?>
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
            <h5><?php echo $nome ?></h5>
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
            <p><?php echo $tp->getNome() ?></p>
            <div class="lista-ricette">
                <div class="nome-ricetta">
                    <select name="nome-ricetta-<?php echo $i ?>-<?php echo $tp->getID() ?>-1">
                        <option value=""></option>
                    </select>
                    <a class="remove-from-pasto">Rimuovi</a>
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
            
           if($urlPDF != false){
               echo '<a target="_blank" href="'.$urlPDF.'">Apri il PDF</a>';
           }
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
    
    public function printTableAgende($agende){
        $header = array(
            'ID',
            'Nome',
            'Caricata',
            'Utente',
            'PDF',
            'Azioni'
        );
        
        $bodyTable = $this->printBodyTable($agende);
        parent::printTableHover($header, $bodyTable);
    }
    
    protected function printBodyTable($array) {
        parent::printBodyTable($array);
        $html = "";
        if($array != null){
            foreach($array as $item){
                $a = new Agenda();
                $a = $item;

                $html.='<tr>';            
                //ID Agenda
                $html.='<td>'.parent::printTextField(null, $a->getID()).'</td>';
                //nome
                $html.='<td>'.parent::printTextField(null, $a->getNome()).'</td>';
                //Caricata
                $html.='<td>'.parent::printTextField(null, getTime($a->getData())).'</td>';
                //Utente
                if($a->getIdUtente() != 0){
                    $user = get_userdata($a->getIdUtente());
                    $html.='<td>'.parent::printTextField(null, $user->user_nicename).'</td>';
                }
                else{
                    $html.='<td></td>';
                }
                //PDF
                if($a->getPdf() != null){
                    $html.='<td><a target="_blank" href="'.$a->getPdf().'">Apri il PDF</a></td>';
                }
                else{
                    $html.='<td></td>';
                }
                //dettagli
                $html.='<td><a href="'. get_admin_url().'admin.php?page=pr_pagina_dettaglio&type=A&id='.$a->getID().'">Vedi dettagli</a></td>';

                $html.='</tr>';
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
        </form>
    <?php        
            
        }
        else{
            echo '<p>Agenda non presente nel sistema.</p>';
        }
    }
    
    public function printDettaglioAgendaPublic(Agenda $a){
        
        $array = $this->aC->createAgenda($a);
        $ingredienti = $this->aC->createListaIngredienti($a, $a->getDose());
        //print_r($ingredienti);
        
        $tps = $this->tpC->getTipologiaPasti();
        $arrayPasti = array();
        
        array_push($arrayPasti, 'Preparazione');
        
        foreach($tps as $tipo){
            $tp = new TipologiaPasto();
            $tp = $tipo;
            array_push($arrayPasti, $tp->getNome());
        }
        //print_r($array);
        
        //creo un array di risultati 
        $result = array();
        foreach($array as $keyG => $valueG){
            //scorro il giorno
            $giorno = array();
            foreach($valueG as $keyP => $valueP){ 
                //vado nel primo array: i pasti
                foreach($arrayPasti as $pasto){                    
                    //controllo se il pasto è una preparazione perchè ha una formattazione particolare
                    if($pasto == 'Preparazione' && $keyP == 'Preparazione'){
                        $countPrep = 0;
                        foreach($valueP as $keyPrep => $valuePrep){
                           
                                $giorno[$keyG][$pasto][$countPrep] = $valuePrep[0];
                                $countPrep++;
                        }                       
                    }
                    else{
                        if($pasto == $keyP){
                            $countRicetta = 0;
                            foreach($valueP[0] as $ricetta){
                                $giorno[$keyG][$pasto][$countRicetta] = $ricetta;
                                $countRicetta++;
                            }                            
                        }
                    }                   
                }
            }            
            foreach($giorno as $keyP => $pasto){                
                foreach($arrayPasti as $p ){
                    if(!isset($pasto[$p])){
                        $giorno[$keyP][$p] = array();
                    }
                }
            }
            array_push($result, $giorno);
        }
    ?>
        <button id="printbutton" onclick="window.print();">STAMPA</button>
        <h3>Calendario</h3>
        <div class="container-agenda-public">
            <div class="container-tp">
                <div class="tp"></div>                
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
            foreach($result as $giorni){
                foreach($giorni as $keyG => $valueG){
                    //print_r($valueG);
    ?>
            <div class="giorno">
                <div class="tp">
                    <?php echo $keyG ?>
                </div>
    <?php
                    foreach($arrayPasti as $pasto){
    ?>
                <div class="tp <?php echo strtolower($pasto) ?>">
                            <?php
                               foreach($valueG[$pasto] as $item){
                                   echo $item.'<br>';
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
        </div>
        <div class="clear"></div>
        <h3>Ingredienti</h3>
        <div class="container-ingredienti">
    <?php
            foreach($ingredienti as $key => $value){
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
            <div class="col-xs-12 col-sm-6 ingrediente">
                <?php echo $string ?>
            </div>
    <?php
            }
    ?>
        </div>
    <?php         
        
         
    }
    
    
    public function printSelectTemplate(){
        $temp = $this->taC->getTemplateAgenda();
        $result = array();
        foreach($temp as $item){
            $ta = new TemplateAgenda();
            $ta = $item;
            $result[$ta->getID()] = $ta->getNome();
        }
    ?>
        
        <?php parent::printSelectFormField('ricerca-template', 'Scegli un\'agenda già fatta', $result) ?>
        <div class="col-xs-12">
            <button class="btn btn-secondary carica-template">Carica</button>
        </div>
       
    <?php
    }
   
}
