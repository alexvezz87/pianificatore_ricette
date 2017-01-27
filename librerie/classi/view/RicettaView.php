<?php
namespace pianificatore_ricette;
/**
 * Description of RicettaView
 *
 * @author Alex
 */
class RicettaView extends PrinterView {    
    private $rC;
    private $iC;    
    private $form;
    private $label;
    
    function __construct() {
        parent::__construct();
        $this->rC = new RicettaController();
        $this->iC = new IngredienteController();        
        
        //tipologia ricetta
        global $FORM_TR_NOME, $FORM_TR_DESCRIZIONE, $FORM_TR_SUBMIT;
        $this->form['tr-nome'] = $FORM_TR_NOME;
        $this->form['tr-descrizione'] = $FORM_TR_DESCRIZIONE;
        $this->form['tr-submit'] = $FORM_TR_SUBMIT;
        
        global $LABEL_TR_NOME, $LABEL_TR_DESCRIZIONE, $LABEL_SUBMIT;
        $this->label['tr-nome'] = $LABEL_TR_NOME;
        $this->label['tr-descrizione'] = $LABEL_TR_DESCRIZIONE;
        $this->label['submit'] = $LABEL_SUBMIT;
        
        //ricetta
        global $FORM_R_NOME, $FORM_R_TIPOLOGIA, $FORM_R_INGREDIENTE, $FORM_R_QT_INGREDIENTE, $FORM_R_UM_INGREDIENTE, $FORM_R_PREPARAZIONE, $FORM_R_DURATA, $FORM_R_SUBMIT, $FORM_R_DOSE, $FORM_R_FOTO, $FORM_R_APPROVATA;
        $this->form['r-nome'] = $FORM_R_NOME;
        $this->form['r-tipologia'] = $FORM_R_TIPOLOGIA;
        $this->form['r-ingrediente'] = $FORM_R_INGREDIENTE;
        $this->form['r-qt-ingrediente'] = $FORM_R_QT_INGREDIENTE;
        $this->form['r-um-ingrediente'] = $FORM_R_UM_INGREDIENTE;
        $this->form['r-preparazione'] = $FORM_R_PREPARAZIONE;
        $this->form['r-durata'] = $FORM_R_DURATA;
        $this->form['r-dose'] = $FORM_R_DOSE;
        $this->form['r-foto'] = $FORM_R_FOTO;
        $this->form['r-approvata'] = $FORM_R_APPROVATA;
        $this->form['r-submit'] = $FORM_R_SUBMIT;        
        
        global $LABEL_R_NOME, $LABEL_R_TIPOLOGIA, $LABEL_R_INGREDIENTE, $LABEL_R_QT_INGREDIENTE, $LABEL_R_UM_INGREDIENTE, $LABEL_R_PREPARAZIONE, $LABEL_R_DURATA, $LABEL_R_DOSE, $LABEL_R_FOTO, $LABEL_R_APPROVATA;
        $this->label['r-nome'] = $LABEL_R_NOME;
        $this->label['r-tipologia'] = $LABEL_R_TIPOLOGIA;
        $this->label['r-ingrediente'] = $LABEL_R_INGREDIENTE;
        $this->label['r-qt-ingrediente'] = $LABEL_R_QT_INGREDIENTE;
        $this->label['r-um-ingrediente'] = $LABEL_R_UM_INGREDIENTE;
        $this->label['r-preparazione'] = $LABEL_R_PREPARAZIONE;
        $this->label['r-durata'] = $LABEL_R_DURATA;
        $this->label['r-dose'] = $LABEL_R_DOSE;  
        $this->label['r-foto'] = $LABEL_R_FOTO;
        $this->label['r-approvata'] = $LABEL_R_APPROVATA;
    }
    
    
    //TIPOLOGIA RICETTE
    public function printAddTipologiaRicettaForm(){
    ?>
        <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-tr" method="POST">
            <div class="col-sm-6">
                <?php parent::printTextFormField($this->form['tr-nome'], $this->label['tr-nome'], true) ?>
                <?php parent::printTextAreaFormField($this->form['tr-descrizione'], $this->label['tr-descrizione']) ?>
            </div>
            <div class="clear"></div>
            <?php parent::printSubmitFormField($this->form['tr-submit'], $this->label['submit']) ?>
            
        </form>
    <?php    
    } 
    
    public function listenerAddTipologiaRicettaForm(){
        if(isset($_POST[$this->form['tr-submit']])){
            $tr = $this->checkTipologiaRicettaFormFields();
            if($tr == null){
                //errore, termino l'operazione
                return;
            }
            //salvo la tipologia ricetta
            if($this->rC->saveTipologia($tr) == false){
                parent::printErrorBoxMessage('Tipologia ricetta non salvata nel Sistema!');
                return;
            }
            parent::printOkBoxMessage('Tipologia ricetta salvata con successo!');
            unset($_POST);
            return;
        }
    }
    
    
    protected function checkTipologiaRicettaFormFields(){
        $errors = 0;
        $tr = new Tipologia();
        if(isset($_POST['id-tr'])){
            //il campo esiste se siamo nella pagina dettaglio
            $tr->setID($_POST['id-tr']);
        }
        
        //nome - CAMPO OBBLIGATORIO
        if(parent::checkRequiredSingleField($this->form['tr-nome'], $this->label['tr-nome'])!=false){
            $tr->setNome(parent::checkRequiredSingleField($this->form['tr-nome'], $this->label['tr-nome']));
        }
        else{
            $errors++;
        }
        
        //descrizione - CAMPO NON OBBLIGATORIO
        if(parent::checkSingleField($this->form['tr-descrizione'])){
            $tr->setDescrizione(parent::checkSingleField($this->form['tr-descrizione']));
        }
        
        if($errors > 0){
            return null;
        }
        
        return $tr;
    } 
    
    
    public function printAllTipologieRicetta(){
        return $this->printTableTipologiaRicette($this->rC->getTipologie());
    }
    
    public function printTableTipologiaRicette($tr){
        $header = array(
            $this->label['tr-nome'],            
            $this->label['tr-descrizione'],
            'Azioni'
        );
        
        $bodyTable = $this->printTrBodyTAble($tr);
        parent::printTableHover($header, $bodyTable);
    }
    
    
    protected function printTrBodyTAble($array){
        parent::printBodyTable($array);
        
        $html = "";
        if(count($array) > 0){
            foreach($array as $item){
                $tr = new Tipologia();
                $tr = $item;
                $html.='<tr>';
                //nome tipologia pasto
                $html.='<td>'.parent::printTextField(null, $tr->getNome()).'</td>';
                //descrizione
                $html.='<td>'.parent::printTextField(null, $tr->getDescrizione()).'</td>';
                $html.='<td><a href="'. get_admin_url().'admin.php?page=pr_pagina_dettaglio&type=TR&id='.$tr->getID().'">Vedi dettagli</a></td>';
                $html.='</tr>';
            }
        }
        return $html;        
    }
    
    public function printDettaglioTipologiaRicetta($ID){
        $tr = new Tipologia();
        $tr = $this->rC->getTipologiaByID($ID);
       
        if($tr != null){
    ?>
            <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-dettaglio-TR" method="POST" >
               <div class="col-sm-6">
                   <?php echo parent::printHiddenFormField('id-tr', $tr->getID()) ?>
                   <?php echo parent::printDisabledTextFormField('id-tr', 'ID', $tr->getID()) ?>
                   <?php echo parent::printTextFormField($this->form['tr-nome'], $this->label['tr-nome'], true, $tr->getNome()) ?>
                   <?php echo parent::printTextAreaFormField($this->form['tr-descrizione'], $this->label['tr-descrizione'], false, $tr->getDescrizione()) ?>
               </div>
                <div class="clear"></div>
                <?php echo parent::printUpdateDettaglio('tr') ?>
                <?php echo parent::printDeleteDettaglio('tr') ?>
            </form>
    <?php
        }
        else{
            echo '<p>Tipologia Pasto non presente nel sistema</p>';
        }
    }
    
    public function listenerDettaglioTipologiaRicetta(){
        //1. Aggiornamento
        if(isset($_POST['update-tr'])){
            $tr = $this->checkTipologiaRicettaFormFields();
            if($tr == null){
                parent::printErrorBoxMessage('Tipologia Ricetta non aggiornata!');
                return;
            }
            if($this->rC->updateTipologia($tr) == false){
                parent::printErrorBoxMessage('Tipologia Ricetta non aggiornata!');
                return;
            }
            else{
                parent::printOkBoxMessage('Tipologia ricetta aggiornata con successo!');
                unset($_POST);
                return;
            }
        }
        
        //2. Cancellazione
        if(isset($_POST['delete-tr'])){
            //print_r($_POST);
            $flag = $this->rC->deleteTipologia($_POST['id-tr']);
            if($flag == true){
                parent::printOkBoxMessage('Tipologia ricetta eliminata con successo!');
                unset($_POST);
                return;
                
            }
            else if($flag == -1){
                parent::printErrorBoxMessage('Errore nella cancellazione. La tipologia ricetta è presente in una o più ricette salvate nel sistema.');
                return;
            }
            else{
                parent::printErrorBoxMessage('Errore nella cancellazione');
                return;
            }
        }
    }
    
    //RICETTE
    public function printAddRicettaForm(){        
    ?>        
        <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-ricetta" method="POST" enctype="multipart/form-data">
            <?php parent::printHiddenFormField('user-id', get_current_user_id()) ?>
            <div class="col-sm-12">
                <div class="col-sm-12">
                    <?php parent::printTextFormField($this->form['r-nome'], $this->label['r-nome'], true) ?>
                    <?php parent::printMultiSelectFormField($this->form['r-tipologia'], $this->label['r-tipologia'], $this->getArraySelectTipologieRicetta(), true) ?>
                </div>
                <div class="clear"></div>
                
                <?php $this->printFormListaIngredienti() ?>
                
                <div class="clear" style="height: 50px"></div>
                <div class="col-sm-12">
                    <?php parent::printTextAreaFormField($this->form['r-preparazione'], $this->label['r-preparazione'], true) ?>
                    <?php parent::printNumberFormField($this->form['r-durata'], $this->label['r-durata'], true) ?>
                    <?php parent::printNumberFormField($this->form['r-dose'], 'Per quante persone è questa ricetta?', true) ?>
                    <?php parent::printInputFileFormField($this->form['r-foto'], $this->label['r-foto']) ?>
                </div>
                <div class="clear"></div>
                <?php parent::printSubmitFormField($this->form['r-submit'], $this->label['submit']) ?>
            </div>
        </form>
    <?php
    }
    
    
    protected function printFormListaIngredienti($arrayIR = null){
        //ottengo l'array con i nomi degli ingredienti
        $ingredienti = $this->getNomeIngredienti();
    ?>
        <script>
            jQuery( function($) {
                var ingredienti = [<?php echo parent::printArraySuggestion($ingredienti) ?>];                
                $(document.body).on('focus', '.nome-ingrediente input', function(){
                    $(this).autocomplete({
                        source: ingredienti                                              
                    });
                });
          } );
        </script>
        
        <h4>Ingredienti</h4>
        <div class="lista-ingredienti">
        <?php    
            //questa funzione gestisce la visualizzazione dei campi degli ingredienti
            if($arrayIR == null){
                $countRicette = $this->getNumIngredienti();                   
                for($i=1; $i <= $countRicette; $i++){                
                    $this->printFormIngrediente($i);                
                } 
            }
            else{
                $counter = 1;
                foreach($arrayIR as $item){
                    $ir = new IngredienteRicetta();
                    $ir = $item;
                    $this->printFormDettaglioIngrediente($ir, $counter);                    
                    $counter++;
                }
            }
        ?>
        </div>
        <div class="add-ingrediente clear">
            <a>Aggiungi Ingrediente</a>
        </div>
    <?php
    }
    
    /**
     * La funzione stampa i campi di un singolo ingrediente
     * @param type $counter
     */
    protected function printFormIngrediente($counter){
    ?>
        <div class="ingrediente" data-num="<?php echo $counter ?>">                        
            <div class="qt">
                <?php parent::printNumberFormField($this->form['r-qt-ingrediente'].'-'.$counter, $this->label['r-qt-ingrediente']) ?>
            </div>
            <div class="um">
                <?php parent::printTextFormField($this->form['r-um-ingrediente'].'-'.$counter, $this->label['r-um-ingrediente']) ?>
            </div>
            <div class="nome-ingrediente ui-widget">
                <?php parent::printTextFormField($this->form['r-ingrediente'].'-'.$counter, $this->label['r-ingrediente'], true) ?>               
            </div>
            <div class="rimuovi-ingrediente">
                <a></a>
            </div>
            <div class="clear"></div>
        </div> 
    <?php    
    }
    
    protected function printFormDettaglioIngrediente(IngredienteRicetta $ir, $counter){ 
        //ottengo il nome dell'ingrediente
        $i = new Ingrediente();
        $i = $this->iC->getIngredienteByID($ir->getIdIngrediente());
        
    ?>
        <div class="ingrediente">            
            <div class="qt">
                <?php parent::printNumberFormField($this->form['r-qt-ingrediente'].'-'.$counter, $this->label['r-qt-ingrediente'], false, $ir->getQuantita()) ?>
            </div>
            <div class="um">
                <?php parent::printTextFormField($this->form['r-um-ingrediente'].'-'.$counter, $this->label['r-um-ingrediente'], false, $ir->getUnitaMisura()) ?>
            </div>
            <div class="nome-ingrediente ui-widget">
                <?php parent::printTextFormField($this->form['r-ingrediente'].'-'.$counter, $this->label['r-ingrediente'], true, $i->getNome()) ?>
            </div>
            <div class="rimuovi-ingrediente">
                <a></a>
            </div>
            <div class="clear"></div>
        </div>
    <?php
    }
   
    public function listenerAddRicettaForm(){
        if(isset($_POST[$this->form['r-submit']])){
            
            //print_r($_POST);
            //die();
                        
            //si fa prima il check sugli argomenti della ricetta
            $r = $this->checkRicettaFormFields() ;
            if($r == null){
                return;
            }
            
            //faccio un check sugli ingredienti da assegnare alla ricetta
            $irs = $this->checkIngredientiRicettaFormFields();
            if($irs == null){
                return;
            }
            
            //faccio check sulle tipologie da assegnare alla ricetta
            $tipologie = array();
            if(isset($_POST[$this->form['r-tipologia']])){
                foreach($_POST[$this->form['r-tipologia']] as $item){
                    array_push($tipologie, $item);
                }
            }
            else{
                parent::printErrorBoxMessage('Campo '.$this->label['r-tipologia'].' mancante o non corretto.');
                return;
            }
                       
            
            //in caso di successo, salvo la ricetta
            if($this->rC->saveRicetta($r, $irs, $tipologie)== false){
                parent::printErrorBoxMessage('Ricetta non salvata nel Sistema!');
                return;
            }
            parent::printOkBoxMessage('Ricetta salvata con successo!');
            unset($_POST);
            unset($_FILES);
            return;            
        }
    }
    
    /**
     * La funzione restituisce un array di oggetti IngredienteRicetta, null in caso di errore
     * @return array
     */
    protected function checkIngredientiRicettaFormFields(){
        $errors = 0;
        $result = array();        
        
        foreach($_POST as $key => $value){                
            if (strpos($key, 'r-ingrediente') !== false) {
                
                if (strpos($key, 'qt') !== false) {
                    $ir = new IngredienteRicetta();
                    if(isset($_POST['id-r'])){
                        $ir->setIdRicetta($_POST['id-r']);
                    }
                    $ir->setQuantita(number_format((float) $value, 2));                                       
                }
                if (strpos($key, 'um') !== false) {
                    $ir->setUnitaMisura(trim($value));
                }
                if (strpos($key, 'nome') !== false) {
                    //questo campo va elaborato, trovando l'id dell'ingrediente, conoscendone il nome
                    $id = $this->iC->getIngredienteByNome($value);
                    if($id == null){
                        //MODIFICA DEL COMPORTAMENTO
                        //se l'ingrediente non viene trovato, nel caso sia un utente esterno ad aggiungerlo, 
                        //bisogna inserirlo nuovo nel database
                        
                        $i = new Ingrediente();
                        $i->setNome(trim($value));
                        $idIngrediente = $this->iC->saveIngrediente($i);
                        
                        $ir->setIdIngrediente($idIngrediente);
                        //carico l'oggetto nell'array
                        array_push($result, $ir);
                        //spacco l'oggetto
                        unset($ir);
                        
                        //$errors++;
                        //parent::printErrorBoxMessage('Ingrediente: '.$value.' non riconosciuto!' );
                    }
                    else{
                        $ir->setIdIngrediente($id);
                        //carico l'oggetto nell'array
                        array_push($result, $ir);
                        //spacco l'oggetto
                        unset($ir);
                    }                    
                }
                //echo $key.' = '.$value.'<br>';
            }
        }
        
        if($errors > 0){
            return null;
        }
        return $result;
    }


    /**
     * La funzione controlla tutti i campi ricetta e restituisce un oggetto ricetta in caso di successo, null altrimenti
     * @return \Ricetta
     */
    protected function checkRicettaFormFields(){
        global $ADMIN_ID;
        $errors = 0;
        $r = new Ricetta();
        
        //faccio un check se è stata caricata la foto
        if(isset($_FILES)){
            if($_FILES[$this->form['r-foto']]["name"] != '' && $_FILES[$this->form['r-foto']]["tmp_name"] != ''){
                $upload = wp_upload_bits($_FILES[$this->form['r-foto']]["name"], null, file_get_contents($_FILES[$this->form['r-foto']]["tmp_name"]));
                if($upload['error'] != false){
                   parent::printErrorBoxMessage($upload['error']);              
                   $errors++;
                }
                if($upload != null){
                    $r->setFoto($upload['url']);
                }
            }
            else if(isset($_POST['url-foto'])){
                $r->setFoto($_POST['url-foto']);
            }
        }
                
        if(isset($_POST['id-r'])){
            //il campo esiste se siamo nella pagina dettaglio
            $r->setID($_POST['id-r']);
        }
        
        //user id - CAMPO OBBLIGATORIO
        if(parent::checkRequiredSingleField('user-id', 'Utente corrente') != false){
            $r->setIdUtente(parent::checkRequiredSingleField('user-id', 'Utente corrente'));
            //imposto l'approvazione della ricetta
            //se l'utente è amministratore, la ricetta è pubblicata di default, altrimenti no
            if(isset($_POST[$this->form['r-approvata']])){
                $r->setApprovata($_POST[$this->form['r-approvata']]);
            }
            else{
                /*
                 * modifica del comportamento, la ricetta è sempre approvata
                if($r->getIdUtente() == $ADMIN_ID){
                    $r->setApprovata(1);
                }
                else{                   
                    $r->setApprovata(0);                    
                }
                */
                $r->setApprovata(1);
            }
            
            
        }
        else{
            $errors++;
        }
        
        //nome - CAMPO OBBLIGATORIO
        if(parent::checkRequiredSingleField($this->form['r-nome'], $this->label['r-nome'])!=false){
            $r->setNome(parent::checkRequiredSingleField($this->form['r-nome'], $this->label['r-nome']));
        }
        else{
            $errors++;
        }
        
        //preparazione - CAMPO OBBLIGATORIO
        if(parent::checkRequiredSingleField($this->form['r-preparazione'], $this->label['r-preparazione'])!= false){
            $r->setPreparazione(parent::checkRequiredSingleField($this->form['r-preparazione'], $this->label['r-preparazione']));
        }
        else{
            $errors++;
        }
        
        //dose - CAMPO OBBLIGATORIO
        if(parent::checkRequiredSingleField($this->form['r-dose'], $this->label['r-dose'])!=false){
            $r->setDose(parent::checkRequiredSingleField($this->form['r-dose'], $this->label['r-dose']));
        }
        else{
            $errors++;
        }
        
        //durata - CAMPO NON OBBLIGATORIO
        if(parent::checkSingleField($this->form['r-durata'])!=false){
            $r->setDurata(parent::checkSingleField($this->form['r-durata']));
        }
        
        if($errors > 0){
            return null;
        }
        
        return $r;
        
    }
            
    /**
     * La funzione conta gli ingredienti che sono memorizzati in $_POST
     * @return int
     */
    private function getNumIngredienti(){
        $countIngr = 1;
        if(isset($_POST[$this->form['r-submit']])){
            $countIngr = 0;
            foreach($_POST as $key => $value){
                if (strpos($key, 'r-ingrediente-nome') !== false) {
                    if(trim($value) != ''){                    
                        $countIngr++;
                    }
                }
            }
        }        
        return $countIngr;
    }
    
    
    
    private function getArraySelectTipologieRicetta(){
        $result = array();
        $trs = $this->rC->getTipologie();
        if($trs != null){
            foreach($trs as $tipologia){
                $tr = new Tipologia();
                $tr = $tipologia;
                $result[$tr->getID()] = $tr->getNome();
            }
        }
        return $result;
        
    }
    
    /**
     * Funzione che restiuisce un array di nome ingredienti
     * @return array
     */
    private function getNomeIngredienti(){
        $result = array();
        $is = $this->iC->getAllIngredienti();
        foreach($is as $it){
            $i = new Ingrediente();
            $i = $it;
            array_push($result, $i->getNome());            
        }
        return $result;
    }
    
    /**
     * Funzione che restituisce un array di nome ricette
     * @return array
     */
    private function getNomeRicette(){
        $result = array();
        $rs = $this->rC->getAllRicette();
        foreach($rs as $it){
            $r = new Ricetta();
            $r  = $it;
            array_push($result, $r->getNome());
        }
        return $result;
    }
    
    public function printAllRicette(){        
        return $this->printTableRicette($this->rC->getAllRicette());
    }
    
    public function printRicetteApprovate(){
        $query = array(
            array(
                'campo'     => 'approvata',
                'valore'    => 1,
                'formato'   => 'INT'
            )
        );
        
        return $this->printTableRicette($this->rC->getRicetteByParameters($query));
    }
    
    public function printRicetteNonApprovate(){
        $query = array(
            array(
                'campo'     => 'approvata',
                'valore'    => 0,
                'formato'   => 'INT'
            )
        );
        
        return $this->printTableRicette($this->rC->getRicetteByParameters($query));
    }
    
    public function printRicetteByUtente($idUtente){        
        return $this->printTableRicette($this->rC->getRicetteByUtente($idUtente));
    }
    
    
    public function printTableRicette($ricette){
        global $ADMIN_ID;
        
        if(get_current_user_id() == $ADMIN_ID){
            $header = array(
                'ID',
                $this->label['r-nome'],            
                $this->label['r-tipologia'],
                'Utente',
                'Caricata',
                'Approvata',
                'Azioni'
            );
        }
        else{
            $header = array(
                'Ricetta',
                $this->label['r-nome'],            
                $this->label['r-tipologia'],                
                'Caricata',
                'Pubblicata',
                'Azioni'
            );
        }
        
        $bodyTable = $this->printBodyTable($ricette);
        parent::printTableHover($header, $bodyTable);
    }
    
    protected function printBodyTable($array) {
        global $ADMIN_ID;
        parent::printBodyTable($array);
        $html = "";
        $counter = 1;
        foreach($array as $item){
            $r = new Ricetta();
            $r = $item;
            
            $html.='<tr>';
            
            if(get_current_user_id() == $ADMIN_ID){
                $html.='<td>'.parent::printTextField(null, $r->getID()).'</td>';
            }
            else{
                //contatore
                $html.='<td>'.parent::printTextField(null, $counter).'</td>';
            }
            
            //nome ricetta
            $html.='<td>'.parent::printTextField(null, $r->getNome()).'</td>';
            
            //tipologia 
            $html.='<td>'.$this->printCommaString($r->getTipologie()).'</td>';
            
            if(get_current_user_id() == $ADMIN_ID){
                //utente
                $utente = get_userdata($r->getIdUtente());
                $html.='<td>'.$utente->display_name.'</td>';
            }
            
            //caricamento
            $html.='<td>'. getTime($r->getData()).'</td>';
            
            //pubblicata
            if($r->getApprovata() == 1){
                $string = '<span style="color:green; font-weight:bold">SI</span>';
            }
            else{
                if(get_current_user_id() == $ADMIN_ID){
                    $string = '<span style="color:red; font-weight:bold">NO</span>';
                }
                else{
                    $string = '<span>in attesa di approvazione...</span>';
                }
            }
            $html.='<td>'.$string.'</td>';
            
            //Azioni
            if(get_current_user_id() == $ADMIN_ID){
                $html.='<td><a href="'. get_admin_url().'admin.php?page=pr_pagina_dettaglio&type=R&id='.$r->getID().'">Vedi dettagli</a></td>';
            }
            else{
                $html.='<td><a href="'. home_url().'/ricetta?id='.$r->getID().'">Vedi dettagli</a></td>';
            }
            
            $html.='</tr>';
            $counter++;
        }
        
        return $html;
        
    }
    
    /**
     * Funzione interna che stampa un array di nomi in un'unica stringa, separata da virgole
     * @param type $array
     * @return string
     */
    private function printCommaString($array){
        $count = 0;
        $string = "";
        foreach($array as $item){
            if($count == count($array)-1){
                $string.=$item->getNome();
            }
            else{
                $string.=$item->getNome().', ';
            }
            $count++;
        }
        return $string;
    }
    
    
    public function printDettaglioRicetta($ID){
        global $ADMIN_ID;
        $r = new Ricetta();
        $r = $this->rC->getRicettaByID($ID);
        //print_r($r);
        if($r != null){
    ?>
            <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-ricetta" method="POST" enctype="multipart/form-data">
                <?php parent::printHiddenFormField('id-r', $r->getID()) ?>     
                <?php parent::printHiddenFormField('user-id', $r->getIdUtente()) ?>
                <div class="col-sm-10">
                    <div class="col-sm-8">
                        <?php parent::printTextFormField($this->form['r-nome'], $this->label['r-nome'], true, $r->getNome()) ?>
                        <?php 
                            $array = array();
                            foreach($r->getTipologie() as $tipologia){
                                array_push($array, $tipologia->getID());
                            }
                            parent::printMultiSelectFormField($this->form['r-tipologia'], $this->label['r-tipologia'], $this->getArraySelectTipologieRicetta(), true, $array); 
                                    
                        ?>
                    </div>
                    <div class="clear"></div>
                    
                    <?php $this->printFormListaIngredienti($r->getIngredienti()) ?>
                    
                    <div class="clear" style="height: 50px"></div>
                    <div class="col-sm-8">
                        <?php parent::printTextAreaFormField($this->form['r-preparazione'], $this->label['r-preparazione'], true, $r->getPreparazione()) ?>
                        <?php parent::printNumberFormField($this->form['r-durata'], $this->label['r-durata'], true, $r->getDurata()) ?>
                        <?php parent::printNumberFormField($this->form['r-dose'], $this->label['r-dose'], true, $r->getDose()) ?>
                        
                        <?php 
                            if($r->getFoto() != null){
                                parent::printImage($this->label['r-foto'], $r->getFoto());
                                parent::printHiddenFormField('url-foto', $r->getFoto());
                            }                                    
                        ?> 
                        <?php parent::printInputFileFormField($this->form['r-foto'], $this->label['r-foto']) ?>
                        <?php
                            if(get_current_user_id() == $ADMIN_ID){
                                $array = array(0 => 'No', 1 => 'Si');
                                parent::printSelectFormField($this->form['r-approvata'], $this->label['r-approvata'], $array, true, $r->getApprovata());
                            }
                        ?>
                    </div>
                    <div class="clear"></div>
                    <?php echo parent::printUpdateDettaglio('r') ?>
                    <?php echo parent::printDeleteDettaglio('r') ?>
                </div>
            </form>
    <?php
        }
        else{
            echo '<p>Ricetta non presente nel sistema.</p>';
        }
    }
    
    public function listenerDettaglioRicetta(){
        
        //1. Aggiornamento
        if(isset($_POST['update-r'])){
            //faccio un check della ricetta salvata
            $r = $this->checkRicettaFormFields();            
            if($r == null){
                parent::printErrorBoxMessage('Ricetta non aggiornata!');
                return;
            }
            //faccio un check sugli ingredienti da assegnare alla ricetta
            $irs = $this->checkIngredientiRicettaFormFields();
            if($irs == null){
                parent::printErrorBoxMessage('Ricetta non aggiornata! Errore negli ingredienti');
                return;
            }
            
            //faccio check sulle tipologie da assegnare alla ricetta
            $tipologie = array();
            if(isset($_POST[$this->form['r-tipologia']])){
                foreach($_POST[$this->form['r-tipologia']] as $item){
                    array_push($tipologie, $item);
                }
            }
            else{
                parent::printErrorBoxMessage('Campo '.$this->label['r-tipologia'].' mancante o non corretto.');
                return;
            }
           
            if(count($tipologie) == 0){
                parent::printErrorBoxMessage('Campo '.$this->label['r-tipologia'].' mancante o non corretto.');
                return;
            }
            
            $update = $this->rC->updateRicetta($r, $irs, $tipologie);
            
            if($update === -1){
                parent::printErrorBoxMessage('Ricetta non aggiornata! Errore nell\'aggiornamento della ricetta!');
                return;
            }
            else if($update === -2){
                parent::printErrorBoxMessage('Ricetta non aggiornata! Errore nella cancellazione dei vecchi ingredienti.');
                return;
            }
            else if($update === -3){
                parent::printErrorBoxMessage('Ricetta non aggiornata! Errore nel salvare un ingrediente nel database.');
                return;
            }
            else if($update === -4){
                parent::printErrorBoxMessage('Ricetta non aggiornata! Errore nella cancellazione delle vecchie tipologie.');
                return;
            }
            else if($update === -5){
                parent::printErrorBoxMessage('Ricetta non aggiornata! Errore nel salvare le tipologie nel database.');
                return;
            }
            else if($update === true){
                parent::printOkBoxMessage('Ricetta aggiornata con successo!');
                unset($_POST);
                return;
            }
            
        }
        //2. Cancellazione
        if(isset($_POST['delete-r'])){
            $delete = $this->rC->deleteRicetta($_POST['id-r']);
            if($delete === false){
                parent::printErrorBoxMessage('Errore nella cancellazione');
                return;
            }
            else if($delete === -1){
                parent::printErrorBoxMessage('Errore nella cancellazione. La ricetta è presente in almeno un template agenda.');
                return;
            }
            else{
                parent::printOkBoxMessage('Ricetta eliminata con successo!');
                unset($_POST);
                return;
            }
            
        }
    }    
    
    /**
     * La funzione stampa a video 6 ricette random create da un utente admin
     * @global type $ADMIN_ID
     * @global type $URL_IMG
     */
    public function printShowPublicRicette($mode=null){
        //mostro le ricette pubblicate dall'amministratore
        global $ADMIN_ID, $IMG_NOT_FOUND;
       
        $query = array();
        array_push($query, array(
                'campo'     => 'approvata',
                'valore'    => 1,
                'formato'   => 'INT'
            ));
        
        if($mode == 's'){
            array_push($query, array(
                'campo'     => 'id_utente',
                'valore'    => $ADMIN_ID,
                'formato'   => 'INT'
            ));
        }
        
        /*
        $query = array(
            array(
                'campo'     => 'approvata',
                'valore'    => 1,
                'formato'   => 'INT'
            )
        );
        */
        
        $ricette = $this->rC->getRicetteByParameters($query, true, 6);
        $count = 1;
        foreach($ricette as $ricetta){
            $r = new Ricetta();
            $r = $ricetta;
            
            $urlFoto = $IMG_NOT_FOUND;
            if($r->getFoto() != null && $r->getFoto() != ''){
                $urlFoto = $r->getFoto();
            }
    ?>
        <div class="ricetta col-xs-12 col-sm-4" >
            <div class="col-xs-6"><?php echo $this->printCommaString($r->getTipologie()) ?></div>
            <div clasS="col-xs-6"><?php echo $r->getDurata() ?> minuti</div>
            <div class="clear"></div>
            <a href="<?php echo home_url() ?>/ricetta?id=<?php echo $r->getID() ?>">
                <div class="foto" title="<?php echo $r->getNome() ?>" style="background: url('<?php echo $urlFoto ?>')"></div>
                <div class="descrizione">
                    <span class="titolo"><?php echo $r->getNome() ?></span>                
                </div>
            </a>
            <div class="aggiungi-ricetta-pubblica">
               <a class="add-recipe">Aggiungi Ricetta</a>
               <input type="hidden" name="id-r" value="<?php echo $r->getID() ?>">
               <input type="hidden" name="nome-r" value="<?php echo $r->getNome() ?>">
            </div>
        </div>
    <?php
        if($count % 3 == 0){
            echo '<div class="clear" style="height:1px; padding-top:20px; padding-bottom:20px;"></div>';
        }
    
        $count++;    
       }
    }
    
    
    public function printPublicRicetta($id){
        global $IMG_NOT_FOUND;
        $ricetta = new Ricetta();
        $ricetta = $this->rC->getRicettaByID($id);
        
        $user_info = get_userdata($ricetta->getIdUtente());
        $dose = "persona";
        if($ricetta->getDose() > 1){
            $dose = "persone";
        }
        
        $urlFoto = $IMG_NOT_FOUND;
        if($ricetta->getFoto() != null && $ricetta->getFoto() != ''){
            $urlFoto = $ricetta->getFoto();
        }
        
    ?>
        <div class="row container">
            <div class="container-ricetta-pubblica">
                <div class="titolo col-xs-12">
                    <h2><?php echo $ricetta->getNome() ?></h2>
                    <div class="cuoco">by <?php echo $user_info->display_name ?></div>
                </div>
                <div class="clear"></div>
                <div class="col-xs-12 col-sm-4 tipologia">
                    <?php echo $this->printCommaString($ricetta->getTipologie()) ?>
                </div>
                <div class="col-xs-12 col-sm-4 dose">
                    per <?php echo $ricetta->getDose().' '.$dose ?>
                </div>
                <div class="col-xs-12 col-sm-4 durata">
                    Tempo di preparazione: <?php echo $ricetta->getDurata() ?> minuti
                </div>
                <div class="clear"></div>
                <div class="col-sm-6 col-sm-push-6 foto hidden-xs">
                    <img src="<?php echo $urlFoto ?>" />
                </div>
                
                    <img class="col-xs-12 visible-xs" src="<?php echo $urlFoto ?>" />
                
                <div class="col-sm-6 col-sm-pull-6 ingredienti hidden-xs">
                    <h3>Ingredienti</h3>
                    <ul>   
                <?php
                    foreach($ricetta->getIngredienti() as $ingRic){
                        $ir = new IngredienteRicetta();
                        $ir = $ingRic;
                        
                        $i = new Ingrediente();
                        $i = $this->iC->getIngredienteByID($ir->getIdIngrediente());
                        
                        $string = "";
                        if($ir->getQuantita() != '0'){
                            $string.= str_replace('.00', '', $ir->getQuantita()).' ';
                        }
                        if($ir->getUnitaMisura() != ''){
                            $string.= $ir->getUnitaMisura().' ';
                        }
                        
                        $string.= $i->getNome();
                ?>
                        <li class="ingrediente col-xs-12">
                            <?php echo $string ?>
                        </li>
                <?php    
                        
                    }
                ?>
                    </ul>
                </div>
                
                <div class="col-xs-12 ingredienti visible-xs">
                    <h3>Ingredienti</h3>
                    <ul>   
                <?php
                    foreach($ricetta->getIngredienti() as $ingRic){
                        $ir = new IngredienteRicetta();
                        $ir = $ingRic;
                        
                        $i = new Ingrediente();
                        $i = $this->iC->getIngredienteByID($ir->getIdIngrediente());
                        
                        $string = "";
                        if($ir->getQuantita() != '0'){
                            $string.= str_replace('.00', '', $ir->getQuantita()).' ';
                        }
                        if($ir->getUnitaMisura() != ''){
                            $string.= $ir->getUnitaMisura().' ';
                        }
                        
                        $string.= $i->getNome();
                ?>
                        <li class="ingrediente col-xs-12">
                            <?php echo $string ?>
                        </li>
                <?php    
                        
                    }
                ?>
                    </ul>
                </div>    
                    
                <div class="col-xs-12 preparazione">
                    <h3>Preparazione</h3>
                    <p>
                        <?php echo  str_replace("\r", "<br>", $ricetta->getPreparazione()) ?>
                    </p>
                </div>

            </div>
        </div>
    <?php   
    }
    
    
    public function printFormRicerca($mode = null){
        //ottengo l'array con i nomi degli ingredienti
        $ingredienti = $this->getNomeIngredienti();
        $ricette = $this->getNomeRicette();
        $tipologie = $this->getArraySelectTipologieRicetta();
        
        //La chiamata verrà fatta in ajax per evitare il refresh della pagina e perdere i dati dell'agenda
    ?>
        <script>
            jQuery( function($) {
                var ingredienti = [<?php echo parent::printArraySuggestion($ingredienti) ?>];                
                var ricette = [<?php echo parent::printArraySuggestion($ricette) ?>];
                
                $(document.body).on('focus', '.nome-ingrediente input', function(){
                    $(this).autocomplete({
                        source: ingredienti,
                        appendTo:$(this).siblings('.suggerimenti')
                    });
                });
                
                $(document.body).on('focus', '.nome-ricetta input', function(){
                    $(this).autocomplete({
                        source: ricette,
                        appendTo:$(this).siblings('.suggerimenti')
                    });
                });                
            });
        </script>
       
        <?php parent::printHiddenFormField('url-home', home_url()) ?>
        <div class="container-ricerca">
            <?php parent::printHiddenFormField('mode', $mode) ?>
            <div class="nome-ricetta ui-widget">
                <?php parent::printSuggestTextFormField('nome-ricetta', 'Nome Ricetta') ?>                
            </div>  
            <div class="clear"></div>
            <div class="tipologia-ricetta">
                <?php parent::printMultiSelectFormField('tipologia-ricetta', 'Tipologia Ricetta', $tipologie) ?>
            </div>
            <div class="clear"></div>
            <div class="nome-ingrediente">
                <?php parent::printSuggestTextFormField('nome-ingrediente', 'Ingredienti') ?>                  
                <?php parent::printDisabledTextFormField('lista-ingredienti', '', null) ?>
                <input type="button" name="cancella-ingredienti" value="RIMUOVI INGREDIENTI"/>                
            </div>
            
            <div class="clear"></div>
            <div class="tempo">
            <?php 
                $tempo = array(
                    '15' => 'meno di 15 min',
                    '30' => 'meno di 30 min',
                    '60' => 'meno di 60 min'
                );
                parent::printRadioFormField('tempo-ricetta', 'Tempo', $tempo);
            ?>
            </div>
            
            <div class="clear"></div>
            <input type="hidden" name="ajax-url" value="<?php echo get_home_url() ?>/wp-admin/admin-ajax.php" />
            <?php parent::printSeachButton('ricerca-ricette', 'Cerca ricette') ?>
            
        </div>
        
    <?php    
    }
    
}
