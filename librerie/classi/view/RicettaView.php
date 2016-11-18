<?php

/**
 * Description of RicettaView
 *
 * @author Alex
 */
class RicettaView extends PrinterView {    
    private $rC;
    private $form;
    private $label;
    
    function __construct() {
        parent::__construct();
        $this->rC = new RicettaController();
        
        //tipologia ricetta
        global $FORM_TR_NOME, $FORM_TR_DESCRIZIONE, $FORM_TR_SUBMIT;
        $this->form['tr-nome'] = $FORM_TR_NOME;
        $this->form['tr-descrizione'] = $FORM_TR_DESCRIZIONE;
        $this->form['tr-submit'] = $FORM_TR_SUBMIT;
        
        global $LABEL_TR_NOME, $LABEL_TR_DESCRIZIONE, $LABEL_SUBMIT;
        $this->label['tr-nome'] = $LABEL_TR_NOME;
        $this->label['tr-descrizione'] = $LABEL_TR_DESCRIZIONE;
        $this->label['submit'] = $LABEL_SUBMIT;
        
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

}
