<?php

namespace pianificatore_ricette;

/**
 * Description of TemplateAgendaView
 *
 * @author Alex
 */
class TemplateAgendaView extends PrinterView {
    private $taC;
    private $aC;
    private $form;
    private $label;
    
    private $mesi;
    
    function __construct() {
        parent::__construct();
        $this->taC = new TemplateAgendaController();
        $this->aC = new AgendaController();
        
        global $FORM_TA_DESCRIZIONE, $FORM_TA_IDAGENDA, $FORM_TA_NOME, $FORM_TA_SUBMIT, $FORM_TA_INIZIO, $FORM_TA_FINE;
        $this->form['nome'] = $FORM_TA_NOME;
        $this->form['descrizione'] = $FORM_TA_DESCRIZIONE;
        $this->form['idAgenda'] = $FORM_TA_IDAGENDA;
        $this->form['submit'] = $FORM_TA_SUBMIT;
        $this->form['inizio'] = $FORM_TA_INIZIO;
        $this->form['fine'] = $FORM_TA_FINE;
        
        global $LABEL_TA_DESCRIZIONE, $LABEL_TA_IDAGENDA, $LABEL_TA_NOME, $LABEL_SUBMIT, $LABEL_TA_INIZIO, $LABEL_TA_FINE;
        $this->label['nome'] = $LABEL_TA_NOME;
        $this->label['descrizione'] = $LABEL_TA_DESCRIZIONE;
        $this->label['idAgenda'] = $LABEL_TA_IDAGENDA;
        $this->label['submit'] = $LABEL_SUBMIT;
        $this->label['inizio'] = $LABEL_TA_INIZIO;
        $this->label['fine'] = $LABEL_TA_FINE;
        
        $this->mesi = array(
            1  => 'Gennaio',
            2  => 'Febbraio',
            3  => 'Marzo',
            4  => 'Aprile',
            5  => 'Maggio',
            6  => 'Giugno',
            7  => 'Luglio',
            8  => 'Agosto',
            9  => 'Settembre',
            10 => 'Ottobre',
            11 => 'Novembre',
            12 => 'Dicembre'
        );
    }
    
    public function printAddTemplateAgendaForm(){
    ?>
        <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-ta" method="POST">
            <div class="col-sm-6">
                <?php parent::printTextFormField($this->form['nome'], $this->label['nome'], true) ?>
                <?php parent::printTextAreaFormField($this->form['descrizione'], $this->label['descrizione']) ?>
                <?php parent::printSelectFormField($this->form['idAgenda'], $this->label['idAgenda'], $this->getArrayAgenda(), true) ?>
                <?php parent::printSelectFormField($this->form['inizio'], $this->label['inizio'], $this->mesi); ?>
                <?php parent::printSelectFormField($this->form['fine'], $this->label['fine'], $this->mesi); ?>                
            </div>
            <div class="clear"></div>
            <?php parent::printSubmitFormField($this->form['submit'], $this->label['submit']) ?>
            
        </form>
    <?php
    }

    private function getArrayAgenda(){
        global $ADMIN_ID; 
        $result = array();
        $agende = $this->aC->getAgendaByUtente($ADMIN_ID);
        
        if($agende != null){
            foreach($agende as $agenda){
                $a = new Agenda();
                $a = $agenda;
                if($a->getNome() != null){
                    $result[$a->getID()] = $a->getNome();
                }
                else{
                    $result[$a->getID()] = 'ID: '.$a->getID();
                }
            }
        }
        
        return $result;
    }
    
    public function listenerAddTemplateAgendaForm(){
        if(isset($_POST[$this->form['submit']])){
            $ta = $this->checkTemplateAgendaFormFileds();
            if($ta == null){
                //errori sopraggiunti
                return;
            }
            
            //salvo il template
            if($this->taC->saveTemplateAgenda($ta) == false){
                parent::printErrorBoxMessage('Template Agenda non salvato nel Sistema!');
                return;
            }
            parent::printOkBoxMessage('Template Agenda salvato con successo!');
            unset($_POST);
            return;
        }
    }
    
    protected function checkTemplateAgendaFormFileds(){
        $errors = 0;
        $ta = new TemplateAgenda();
        
        if(isset($_POST['id-ta'])){
            //il campo esiste se siamo nella pagina dettaglio
            $ta->setID($_POST['id-ta']);
        }
        
        //nome - CAMPO OBBLIGATORIO
        if(parent::checkRequiredSingleField($this->form['nome'], $this->label['nome']) != false){
            $ta->setNome(parent::checkRequiredSingleField($this->form['nome'], $this->label['nome']));
        }
        else{
            $errors++;
        }
        
        //idAgenda - CAMPO OBBLIGATORIO
        if(parent::checkRequiredSingleField($this->form['idAgenda'], $this->label['idAgenda'])!=false){
            $ta->setIdAgenda(parent::checkRequiredSingleField($this->form['idAgenda'], $this->label['idAgenda']));
        }
        else{
            $errors++;
        }
        
        //descrizione - CAMPO NON OBBLIGATORIO
        if(parent::checkSingleField($this->form['descrizione'])){
            $ta->setDescrizione(parent::checkSingleField($this->form['descrizione']));
        }
        
        //inizio - CAMPO NON OBBLIGATORIO
        if(parent::checkSingleField($this->form['inizio'])){
            $ta->setInizio(parent::checkSingleField($this->form['inizio']));
        }
        
        //fine - CAMPO NON OBBLIGATORIO
        if(parent::checkSingleField($this->form['fine'])){
            $ta->setFine(parent::checkSingleField($this->form['fine']));
        }
        
        if($errors > 0){
            return null;
        }
        
        return $ta;
    }
    
    public function printAllTemplateAgenda(){
        return $this->printTableTemplateAgenda($this->taC->getTemplateAgenda());
    }
    
    public function printTableTemplateAgenda($ta){
        $header = array(
            $this->label['nome'],
            $this->label['descrizione'],
            'Periodo',
            'Azioni'
        );
        $bodyTable = $this->printBodyTable($ta);
        parent::printTableHover($header, $bodyTable);
        
    }
    
    protected function printBodyTable($array){
        parent::printBodyTable($array);
        
        $html = "";
        if(count($array) > 0){
            foreach($array as $item){
                $ta = new TemplateAgenda();
                $ta = $item;
                $html.='<tr>';
                //nome
                $html.='<td>'.parent::printTextField(null, $ta->getNome()).'</td>';
                //descrizione
                $html.='<td>'.parent::printTextField(null, $ta->getDescrizione()).'</td>';
                //periodo
                $periodo = "";
                if($ta->getInizio() != 0 && $ta->getFine() != 0){
                    $periodo = $this->mesi[$ta->getInizio()].' - '.$this->mesi[$ta->getFine()];
                }                
                $html.='<td>'.$periodo.'</td>';
                $html.='<td><a href="'. get_admin_url().'admin.php?page=pr_pagina_dettaglio&type=TA&id='.$ta->getID().'">Vedi dettagli</a></td>';
                $html.='</tr>';
            }
        }
        return $html;
    }
    
    
    public function printDettaglioTemplateAgenda($ID){
        $ta = new TemplateAgenda();
        $ta = $this->taC->getTemplateAgendaByID($ID);
        
        if($ta != null){
        ?>
            <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-dettaglio-TA" method="POST" >
                <div class="col-sm-6">
                    <?php echo parent::printHiddenFormField('id-ta', $ta->getID()) ?>
                    <?php echo parent::printDisabledTextFormField('id-ta', 'ID', $ta->getID()) ?>
                    <?php echo parent::printTextFormField($this->form['nome'], $this->label['nome'], true, $ta->getNome()) ?>
                    <?php echo parent::printTextAreaFormField($this->form['descrizione'], $this->label['descrizione'], false, $ta->getDescrizione()) ?>
                    <?php echo parent::printSelectFormField($this->form['idAgenda'], $this->label['idAgenda'], $this->getArrayAgenda(), true, $ta->getIdAgenda()) ?>
                    <?php echo parent::printSelectFormField($this->form['inizio'], $this->label['inizio'], $this->mesi, false, $ta->getInizio()) ?>
                    <?php echo parent::printSelectFormField($this->form['fine'], $this->label['fine'], $this->mesi, false, $ta->getFine()) ?>
                </div>
                <div class="clear"></div>
                <?php echo parent::printUpdateDettaglio('ta') ?>
                <?php echo parent::printDeleteDettaglio('ta') ?>
            </form>
        <?php
        }
        else{
            echo '<p>Template Agenda non presente nel sistema</p>';
        }
    }
    
    
    public function listenerDettaglioTemplateAgenda(){
        //1. Aggiornamento
        if(isset($_POST['update-ta'])){
            $ta = $this->checkTemplateAgendaFormFileds();
            if($ta == null){
                parent::printErrorBoxMessage('Template Agenda non aggiornato!');
                return;
            }
            if($this->taC->updateTemplateAgenda($ta) == false){
                parent::printErrorBoxMessage('Template Agenda non aggiornato!');
                return;
            }
            else{
                parent::printOkBoxMessage('Template Agenda aggiornato con successo!');
                unset($_POST);
                return;
            }
        }
        //2. Cancellazione
        if(isset($_POST['delete-ta'])){
            if($this->taC->deleteTipologiaAgenda($_POST['id-ta']) == true){
                parent::printOkBoxMessage('Template Agenda eliminato con succeso!');
                unset($_POST);
                return;
            }
            else{
                parent::printErrorBoxMessage('Errore nella cancellazione!');
                return;
            }
        }
    }
}
