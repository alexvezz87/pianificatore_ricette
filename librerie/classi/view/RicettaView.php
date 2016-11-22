<?php

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
        global $FORM_R_NOME, $FORM_R_TIPOLOGIA, $FORM_R_INGREDIENTE, $FORM_R_QT_INGREDIENTE, $FORM_R_UM_INGREDIENTE, $FORM_R_PREPARAZIONE, $FORM_R_DURATA, $FORM_R_SUBMIT, $FORM_R_DOSE, $FORM_R_FOTO;
        $this->form['r-nome'] = $FORM_R_NOME;
        $this->form['r-tipologia'] = $FORM_R_TIPOLOGIA;
        $this->form['r-ingrediente'] = $FORM_R_INGREDIENTE;
        $this->form['r-qt-ingrediente'] = $FORM_R_QT_INGREDIENTE;
        $this->form['r-um-ingrediente'] = $FORM_R_UM_INGREDIENTE;
        $this->form['r-preparazione'] = $FORM_R_PREPARAZIONE;
        $this->form['r-durata'] = $FORM_R_DURATA;
        $this->form['r-dose'] = $FORM_R_DOSE;
        $this->form['r-foto'] = $FORM_R_FOTO;
        $this->form['r-submit'] = $FORM_R_SUBMIT;        
        
        global $LABEL_R_NOME, $LABEL_R_TIPOLOGIA, $LABEL_R_INGREDIENTE, $LABEL_R_QT_INGREDIENTE, $LABEL_R_UM_INGREDIENTE, $LABEL_R_PREPARAZIONE, $LABEL_R_DURATA, $LABEL_R_DOSE, $LABEL_R_FOTO;
        $this->label['r-nome'] = $LABEL_R_NOME;
        $this->label['r-tipologia'] = $LABEL_R_TIPOLOGIA;
        $this->label['r-ingrediente'] = $LABEL_R_INGREDIENTE;
        $this->label['r-qt-ingrediente'] = $LABEL_R_QT_INGREDIENTE;
        $this->label['r-um-ingrediente'] = $LABEL_R_UM_INGREDIENTE;
        $this->label['r-preparazione'] = $LABEL_R_PREPARAZIONE;
        $this->label['r-durata'] = $LABEL_R_DURATA;
        $this->label['r-dose'] = $LABEL_R_DOSE;  
        $this->label['r-foto'] = $LABEL_R_FOTO;
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
                $html.='<td><a href="'. get_admin_url().'admin.php?page=pagina_dettaglio&type=TR&id='.$tr->getID().'">Vedi dettagli</a></td>';
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
            <div class="col-sm-10">
                <div class="col-sm-8">
                    <?php parent::printTextFormField($this->form['r-nome'], $this->label['r-nome'], true) ?>
                    <?php parent::printSelectFormField($this->form['r-tipologia'], $this->label['r-tipologia'], $this->getArraySelectTipologieRicetta(), true) ?>
                </div>
                <div class="clear"></div>
                
                <?php $this->printFormListaIngredienti() ?>
                
                <div class="clear" style="height: 50px"></div>
                <div class="col-sm-8">
                    <?php parent::printTextAreaFormField($this->form['r-preparazione'], $this->label['r-preparazione'], true) ?>
                    <?php parent::printNumberFormField($this->form['r-durata'], $this->label['r-durata'], true) ?>
                    <?php parent::printNumberFormField($this->form['r-dose'], $this->label['r-dose'], true) ?>
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
                var ingredienti = [
    <?php
                $count = 0;
                foreach($ingredienti as $i){
                    if($count < count($ingredienti) -1){
                        echo '"'.$i.'",';
                    }
                    else{                        
                        echo '"'.$i.'"';
                    }
                    $count++;
                }
    ?>
                ];
                
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
            <a>+ Aggiungi ingrediente</a>
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
                <a>- Rimuovi ingrediente</a>
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
                <a>- Rimuovi ingrediente</a>
            </div>
            <div class="clear"></div>
        </div>
    <?php
    }
   
    public function listenerAddRicettaForm(){
        if(isset($_POST[$this->form['r-submit']])){
                        
            //si fa prima il check sugli argomenti della ricetta
            $r = $this->checkRicettaFormFields() ;
            if($r == null){
                return;
            }
            
            //print_r($r);
            
            //faccio un check sugli ingredienti da assegnare alla ricetta
            $irs = $this->checkIngredientiRicettaFormFields();
            if($irs == null){
                return;
            }
            
            //print_r($irs);
            
            //in caso di successo, salvo la ricetta
            if($this->rC->saveRicetta($r, $irs)== false){
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
                        $errors++;
                        parent::printErrorBoxMessage('Ingrediente: '.$value.' non riconosciuto!' );
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
        
        //tipologia ricetta - CAMPO OBBLIGATORIO
        if(parent::checkRequiredSingleField($this->form['r-tipologia'], $this->label['r-tipologia']) != false){
            $r->setIdTipologia(parent::checkRequiredSingleField($this->form['r-tipologia'], $this->label['r-tipologia']));
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
    
    
    public function printAllRicette(){        
        return $this->printTableRicette($this->rC->getAllRicette());
    }
    
    public function printTableRicette($ricette){
        $header = array(
            $this->label['r-foto'],
            $this->label['r-nome'],            
            $this->label['r-tipologia'],
            'Utente',
            'Caricata',
            'Azioni'
        );
        
        $bodyTable = $this->printBodyTable($ricette);
        parent::printTableHover($header, $bodyTable);
    }
    
    protected function printBodyTable($array) {
        parent::printBodyTable($array);
        $html = "";
        foreach($array as $item){
            $r = new Ricetta();
            $r = $item;
            
            $html.='<tr>';
            
            //foto ricetta
            $html.='<td><div class="image-preview" style="background:url(\''.$r->getFoto().'\'"></div></td>';
            
            //nome ricetta
            $html.='<td>'.parent::printTextField(null, $r->getNome()).'</td>';
            
            //tipologia
            $tr = $this->rC->getTipologiaByID($r->getIdTipologia());
            $nomeTipologia = "";
            if($tr != null){
                $nomeTipologia = $tr->getNome();
            }
            $html.='<td>'.$nomeTipologia.'</td>';
            
            //utente
            $utente = get_userdata($r->getIdUtente());
            $html.='<td>'.$utente->user_nicename.'</td>';
            
            //caricamento
            $html.='<td>'. getTime($r->getData()).'</td>';
            
            //Azioni
            $html.='<td><a href="'. get_admin_url().'admin.php?page=pagina_dettaglio&type=R&id='.$r->getID().'">Vedi dettagli</a></td>';
            
            $html.='</tr>';
        }
        
        return $html;
        
    }
    
    
    public function printDettaglioRicetta($ID){
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
                        <?php parent::printSelectFormField($this->form['r-tipologia'], $this->label['r-tipologia'], $this->getArraySelectTipologieRicetta(), true, $r->getIdTipologia()) ?>
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
            
            $update = $this->rC->updateRicetta($r, $irs);
            
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
            else if($update === true){
                parent::printOkBoxMessage('Ricetta aggiornata con successo!');
                unset($_POST);
                return;
            }
            
        }
        //2. Cancellazione
        if(isset($_POST['delete-r'])){
            if($this->rC->deleteRicetta($_POST['id-r'])==false){
                parent::printErrorBoxMessage('Errore nella cancellazione');
                return;
            }
            else{
                parent::printOkBoxMessage('Ricetta eliminata con successo!');
                unset($_POST);
                return;
            }
            
        }
    }
    

}
