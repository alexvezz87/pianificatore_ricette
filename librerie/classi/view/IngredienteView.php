<?php

/**
 * Description of IngredienteView
 *
 * @author Alex
 */
class IngredienteView extends PrinterView{
    private $iC; 
    private $form;
    private $label;
    
    function __construct() {
        parent::__construct();        
        $this->iC = new IngredienteController(); 
        
        //inserisco le variabili globali
        //FORM
        global $FORM_ING_NOME, $FORM_ING_GIORNI_ANTICIPO, $FORM_ING_DESCRIZIONE, $FORM_ING_SUBMIT;
        $this->form['nome'] = $FORM_ING_NOME;
        $this->form['anticipo'] = $FORM_ING_GIORNI_ANTICIPO;
        $this->form['descrizione'] = $FORM_ING_DESCRIZIONE;
        $this->form['submit'] = $FORM_ING_SUBMIT;
        
        //LABEL
        global $LABEL_ING_NOME, $LABEL_ING_GIORNI_ANTICIPO, $LABEL_ING_DESCRIZIONE, $LABEL_SUBMIT;
        $this->label['nome'] = $LABEL_ING_NOME;     
        $this->label['anticipo'] = $LABEL_ING_GIORNI_ANTICIPO;
        $this->label['descrizione'] = $LABEL_ING_DESCRIZIONE;
        $this->label['submit'] = $LABEL_SUBMIT;
        
        
    }
    
    /**
     * La funzione stampa il form di inserimento degli ingredienti
     */
    public function printAddIngredienteForm(){        
    ?>
        <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-ingrediente" method="POST">
            <div class="col-sm-6">
                <?php parent::printTextFormField($this->form['nome'], $this->label['nome'], true) ?>
                <h4>Preparazione</h4>
                <?php parent::printNumberFormField($this->form['anticipo'], $this->label['anticipo']) ?>
                <?php parent::printTextAreaFormField($this->form['descrizione'], $this->label['descrizione']) ?>
            </div>
            <div class="clear"></div>
            <?php parent::printSubmitFormField($this->form['submit'], $this->label['submit']) ?>
            
        </form>
    <?php
    }
    
    
    public function listenerAddIngredienteForm(){
        if(isset($_POST[$this->form['submit']])){
            
            //ottengo l'ingrediente
            $ingrediente = $this->checkIngredienteFormFields();
            if($ingrediente == null){
                //se ci sono stati degli errori di compilazione conlcudo l'operazione
                return;
            }
            
            //salvo l'ingrediente
            $save = $this->iC->saveIngrediente($ingrediente);
            if($save === false){
                parent::printErrorBoxMessage('Ingrediente non salvato nel Sistema!');
                return;
            }
            else if($save === -1){
               parent::printErrorBoxMessage('Ingrediente non salvato nel Sistema! L\'ingrediente è già presente nel DB');
                return; 
            }
            else if($save === true){
                parent::printOkBoxMessage('Ingrediente salvato con successo!');
                //Pulisco la variabile $_POST
                unset($_POST);
                return;
            }
        }
    }
    
    
    /**
     * La funzione controlla i campi ricevuti in post dalla form e restituisce un oggetto ingrediente in caso di compilazione a buon fine
     * @return \Ingrediente
     */
    protected function checkIngredienteFormFields(){
        $errors = 0;
        $i = new Ingrediente();
        
        //print_r($_POST);
        
        if(isset($_POST['id-ingrediente'])){
            //il campo esiste se siamo nella pagina dettaglio
            $i->setID($_POST['id-ingrediente']);
        }
        
        //nome - CAMPO OBBLIGATORIO
        if(parent::checkRequiredSingleField($this->form['nome'], $this->label['nome'])!= false){
            $i->setNome(parent::checkRequiredSingleField($this->form['nome'], $this->label['nome']));
        }
        else{
            $errors++;            
        }        
        
        //preparazione - CAMPO NON OBBLIGATORIO
        if(parent::checkSingleField($this->form['anticipo']) != false && parent::checkSingleField($this->form['descrizione']) !=false){
            $p = new Preparazione();
            
            if(isset($_POST['id-preparazione'])){
                //il campo si trova se siamo nella pagina dettaglio
                $p->setID($_POST['id-preparazione']);
            }
            
            if(isset($_POST['id-ingrediente'])){
                //il campo si trova se siamo nella pagina dettaglio
                $p->setIdIngrediente($_POST['id-ingrediente']);
            }            
            $p->setGiorniAnticipo(parent::checkSingleField($this->form['anticipo']));
            $p->setDescrizione(parent::checkSingleField($this->form['descrizione']));
            $preparazioni = array();
            array_push($preparazioni, $p);
            $i->setPreparazioni($preparazioni);
        }
        
        if($errors > 0){
            return null;
        }
        
        return $i;
        
    }
    
    public function printAllIngredienti(){       
        return $this->printTableIngredienti($this->iC->getAllIngredienti());
    }
    
    /**
     * Stampa il form di dettaglio di un ingrediente specifico
     * @param type $ID
     */
    public function printDettaglioIngrediente($ID){
        
        $i = new Ingrediente();
        $i = $this->iC->getIngredienteByID($ID);
        if($i != null){
       
    ?>    
            <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-dettaglio-ingrediente" method="POST" >
                <!-- campo id ingrediente -->
                <div class="col-sm-6">
                    <?php echo parent::printHiddenFormField('id-ingrediente', $i->getID()) ?>
                    <?php echo parent::printDisabledTextFormField('id-ingrediente', 'ID', $i->getID()) ?>
                    <?php echo parent::printTextFormField($this->form['nome'], $this->label['nome'], true, $i->getNome()) ?>
                    <h4>Preparazione</h4>
                    <?php 
                        if($i->getPreparazioni()!=null){
                            foreach($i->getPreparazioni() as $preparazione){
                                $p = new Preparazione();
                                $p = $preparazione;
                                parent::printHiddenFormField('id-preparazione', $p->getID());
                                parent::printNumberFormField($this->form['anticipo'], $this->label['anticipo'], false, $p->getGiorniAnticipo());
                                parent::printTextAreaFormField($this->form['descrizione'], $this->label['descrizione'], false, $p->getDescrizione());                                        
                            }
                        }
                        else{
                            //stampo una preparazione vuota
                            parent::printNumberFormField($this->form['anticipo'], $this->label['anticipo'], false);
                            parent::printTextAreaFormField($this->form['descrizione'], $this->label['descrizione'], false);
                        }
                    ?>
                </div>
                <div class="clear"></div>
                <?php echo parent::printUpdateDettaglio('ingrediente') ?>
                <?php echo parent::printDeleteDettaglio('ingrediente') ?>
            </form>
    <?php   
        }
        else{
            echo '<p>Ingrediente non presente nel sistema.</p>';
        }
    }
    
    
    public function listenerDettaglioIngrediente(){
        
        //1. Aggiornamento
        if(isset($_POST['update-ingrediente'])){
            //faccio un check dell'ingrediente salvato
            $ingrediente = $this->checkIngredienteFormFields();
            if($ingrediente == null){
                parent::printErrorBoxMessage('Ingrediente non aggiornato!');
                return;
            }
            if($this->iC->updateIngrediente($ingrediente) == false){
                parent::printErrorBoxMessage('Ingrediente non aggiornato!');
                return;
            }
            else{
                parent::printOkBoxMessage('Ingrediente aggiornato con successo!');
                unset($_POST);
                return;
            }
            
        }
        
        //2. Cancellazione
        if(isset($_POST['delete-ingrediente'])){
            $delete = $this->iC->deleteIngrediente($_POST['id-ingrediente']);
            
            if( $delete === false){
                parent::printErrorBoxMessage('Errore nella cancellazione');
                return;
            }
            else if($delete === -1){
                parent::printErrorBoxMessage('Errore nella cancellazione. L\'ingrediente è presente in almeno una ricetta');
                return;
            }
            else if($delete === true){
                parent::printOkBoxMessage('Ingrediente eliminato con successo!');
                unset($_POST);
                return;
            }
        }
    }
    
    /**
     * La funzione stampa a video una tabella di ingredienti
     * @param type $ingredienti
     */
    public function printTableIngredienti($ingredienti){
        
        $header = array(
            $this->label['nome'],
            $this->label['anticipo'],
            $this->label['descrizione'],
            'Azioni'
        );
        
        $bodyTable = $this->printBodyTable($ingredienti);
        parent::printTableHover($header, $bodyTable);
    }
    
    protected function printBodyTable($array) {
        parent::printBodyTable($array);
        
        $html = "";
        foreach($array as $item){
            $i = new Ingrediente();
            $i = $item;
            
            $html.='<tr>';
            //nome ingrediente
            $html.='<td>'.parent::printTextField(null, $i->getNome()).'</td>';            
            
            if($i->getPreparazioni() != null){
                foreach($i->getPreparazioni() as $item2){
                    $p = new Preparazione();
                    $p = $item2;
                    //giorni anticipo
                    $html.='<td>'.parent::printTextField(null, $p->getGiorniAnticipo()).'</td>';
                    //descrizione
                    $html.='<td>'.parent::printTextField(null, $p->getDescrizione()).'</td>';
                }
            }
            else{
                $html.='<td></td><td></td>';
            }
            
            $html.='<td><a href="'. get_admin_url().'admin.php?page=pagina_dettaglio&type=ING&id='.$i->getID().'">Vedi dettagli</a></td>';
            $html.='</tr>';
            
        }
        
        return $html;
    }
    
    

}
