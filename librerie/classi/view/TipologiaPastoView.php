<?php

/**
 * Description of TipologiaPastoView
 *
 * @author Alex
 */
class TipologiaPastoView extends PrinterView {
    private $tpC;
    private $form;
    private $label;
    
    function __construct() {
        parent::__construct();
        $this->tpC = new TipologiaPastoController();
        
        //Inserisco le variabili globali
        //FORM
        global $FORM_TP_NOME, $FORM_TP_DESCRIZIONE, $FORM_TP_SUBMIT;
        $this->form['nome'] = $FORM_TP_NOME;
        $this->form['descrizione'] = $FORM_TP_DESCRIZIONE;
        $this->form['submit'] = $FORM_TP_SUBMIT;
        
        //LABEL
        global $LABEL_TP_NOME, $LABEL_TP_DESCRIZIONE, $LABEL_SUBMIT;
        $this->label['nome'] = $LABEL_TP_NOME;
        $this->label['descrizione'] = $LABEL_TP_DESCRIZIONE;
        $this->label['submit'] = $LABEL_SUBMIT;
    }
    
    
    public function printAddTipologiaPastoForm(){
    ?>
        <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-tp" method="POST">
            <div class="col-sm-6">
                <?php parent::printTextFormField($this->form['nome'], $this->label['nome'], true) ?>
                <?php parent::printTextAreaFormField($this->form['descrizione'], $this->label['descrizione']) ?>
            </div>
            <div class="clear"></div>
            <?php parent::printSubmitFormField($this->form['submit'], $this->label['submit']) ?>
            
        </form>
    <?php
    }
    
    public function listenerAddTipologiaPastoForm(){
        if(isset($_POST[$this->form['submit']])){
            
            //ottengo la tipologia pasto
            $tp = $this->checkTipologiaPastoFormFields();
            if($tp == null){
                //se ci sono stati errori, termino l'operazione
                return;
            }
            
            //salvo la tipologia pasto
            if($this->tpC->saveTipologiaPasto($tp) == false){
                parent::printErrorBoxMessage('Tipologia pasto non salvata nel Sistema!');
                return;
            }
            parent::printOkBoxMessage('Tipologia pasto salvata con successo!');
            unset($_POST);
            return;
        }
    }
    
    /**
     * La funzione controlla i campi post ricevuti e restituisce un oggetto Tipologia pasto in caso di successo, null altrimenti
     * @return \TipologiaPasto
     */
    protected function checkTipologiaPastoFormFields(){
        $errors = 0;
        $tp = new TipologiaPasto();
        
        if(isset($_POST['id-tp'])){
            //il campo esiste se siamo nella pagina dettaglio
            $tp->setID($_POST['id-tp']);
        }
        
        //nome - CAMPO OBBLIGATORIO
        if(parent::checkRequiredSingleField($this->form['nome'], $this->label['nome'])!=false){
            $tp->setNome(parent::checkRequiredSingleField($this->form['nome'], $this->label['nome']));
        }
        else{
            $errors++;
        }
        
        //descrizione - CAMPO NON OBBLIGATORIO
        if(parent::checkSingleField($this->form['descrizione'])){
            $tp->setDescrizione(parent::checkSingleField($this->form['descrizione']));
        }
        
        if($errors > 0){
            return null;
        }
        
        return $tp;
    }
    
    
    public function printAllTipologiePasto(){
        return $this->printTableTipologiaPasto($this->tpC->getTipologiaPasti());
    }
    
    public function printTableTipologiaPasto($tp){
        $header = array(
            $this->label['nome'],            
            $this->label['descrizione'],
            'Azioni'
        );
        $bodyTable = $this->printBodyTable($tp);
        parent::printTableHover($header, $bodyTable);
        
    }
    
    protected function printBodyTable($array){
        parent::printBodyTable($array);
        
        $html = "";
        if(count($array) > 0){
            foreach($array as $item){
                $tp = new TipologiaPasto();
                $tp = $item;
                $html.='<tr>';
                //nome tipologia pasto
                $html.='<td>'.parent::printTextField(null, $tp->getNome()).'</td>';
                //descrizione
                $html.='<td>'.parent::printTextField(null, $tp->getDescrizione()).'</td>';
                $html.='<td><a href="'. get_admin_url().'admin.php?page=pagina_dettaglio&type=TP&id='.$tp->getID().'">Vedi dettagli</a></td>';
                $html.='</tr>';
            }
        }
        return $html;        
    }

    
    public function printDettaglioTipologiaPasto($ID){
        $tp = new TipologiaPasto();
        $tp = $this->tpC->getTipologiaPastoByID($ID);
       
        if($tp != null){
    ?>
            <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-dettaglio-TP" method="POST" >
               <div class="col-sm-6">
                   <?php echo parent::printHiddenFormField('id-tp', $tp->getID()) ?>
                   <?php echo parent::printDisabledTextFormField('id-tp', 'ID', $tp->getID()) ?>
                   <?php echo parent::printTextFormField($this->form['nome'], $this->label['nome'], true, $tp->getNome()) ?>
                   <?php echo parent::printTextAreaFormField($this->form['descrizione'], $this->label['descrizione'], false, $tp->getDescrizione()) ?>
               </div>
                <div class="clear"></div>
                <?php echo parent::printUpdateDettaglio('tp') ?>
                <?php echo parent::printDeleteDettaglio('tp') ?>
            </form>
    <?php
        }
        else{
            echo '<p>Tipologia Pasto non presente nel sistema</p>';
        }
    }
    
    public function listenerDettaglioTipologiaPasto(){
        //1. Aggiornamento
        if(isset($_POST['update-tp'])){
            $tp = $this->checkTipologiaPastoFormFields();
            if($tp == null){
                parent::printErrorBoxMessage('Tipologia Pasto non aggiornata!');
                return;
            }
            if($this->tpC->updateTipologiaPasto($tp) == false){
                parent::printErrorBoxMessage('Tipologia Pasto non aggiornata!');
                return;
            }
            else{
                parent::printOkBoxMessage('Tipologia pasto aggiornata con successo!');
                unset($_POST);
                return;
            }
        }
        
        //2. Cancellazione
        if(isset($_POST['delete-tp'])){
            //print_r($_POST);
            $flag = $this->tpC->deleteTipologiaPasto($_POST['id-tp']);
            if($flag == true){
                parent::printOkBoxMessage('Tipologia pasto eliminata con successo!');
                unset($_POST);
                return;
                
            }
            else if($flag == -1){
                parent::printErrorBoxMessage('Errore nella cancellazione. La tipologia Pasto è presente in uno o più pasti salvati nel sistema.');
                return;
            }
            else{
                parent::printErrorBoxMessage('Errore nella cancellazione');
                return;
            }
        }
    }
}
