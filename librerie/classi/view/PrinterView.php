<?php
namespace pianificatore_ricette;
/**
 * Description of PrinterView
 *
 * @author Alex
 */
class PrinterView {
    //put your code here
    private $mesi;
    private $giorni;
    private $annoCorrente;
    private $meseCorrente;
    private $giornoCorrente;
    private $settimanaCorrente;
    
    function __construct() {
        $this->mesi = array(
            '01' => 'Gennaio',
            '02' => 'Febbraio',
            '03' => 'Marzo',
            '04' => 'Aprile',
            '05' => 'Maggio',
            '06' => 'Giugno',
            '07' => 'Luglio',
            '08' => 'Agosto',
            '09' => 'Settembre',
            '10' => 'Ottobre',
            '11' => 'Novembre',
            '12' => 'Dicembre'
        );
        
        $this->giorni = array(
            '1'  => 'Lunedì',
            '2'  => 'Martedì',
            '3'  => 'Mercoledì',
            '4'  => 'Giovedì',
            '5'  => 'Venerdì',
            '6'  => 'Sabato',
            '7'  => 'Domenica'
        );
        
        date_default_timezone_set('Europe/Rome');
        $this->annoCorrente = intval(date('Y'));
        $this->meseCorrente = date('m');
        $this->giornoCorrente = intval(date('d'));
        
        
        $date = new \DateTime($this->annoCorrente.'-'.$this->meseCorrente.'-'.$this->giornoCorrente);
        $this->settimanaCorrente = $date->format("W");
    }
    
    /**
     * Funzione che stampa secondo canoni bootstrap un input text
     * @param type $nameField
     * @param type $label
     */
    protected function printTextFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
        
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">            
                <input class="form-control" type="text" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired ?> />
            </div>
        </div>
    <?php  
    }
    
    protected function printSuggestTextFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
        
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">            
                <input class="form-control" type="text" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired ?> />
                <div class="suggerimenti"></div>
            </div>
        </div>
    <?php  
    }
    
    /**
     * Funzione che stampa una input text disabilitata
     * @param type $nameField
     * @param type $label
     * @param type $value
     */
    protected function printDisabledTextFormField($nameField, $label, $value){       
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">            
                <input class="form-control" type="text" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" disabled />
            </div>
        </div>
    <?php  
    }
    
    
          
    /**
     * Funzione che stampa secondo i canoni bootstrap una textarea
     * @param type $nameField
     * @param type $label
     */
    protected function printTextAreaFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
        
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">    
                <textarea class="form-control" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="" <?php echo $optRequired ?>><?php echo $value ?></textarea>           
            </div>
        </div>
    <?php      
        
    }
    
    protected function printDisabledTextAreaFormField($nameField, $label, $value){
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">    
                <textarea class="form-control" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="" disabled ><?php echo $value ?></textarea>           
            </div>
        </div>
    <?php       
    }
    
    protected function printNumberFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }  
       
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">            
                <input class="form-control" type="number" step="any" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired ?> />
            </div>
        </div>
    <?php     
    }
    
    /**
     * Funzione che stampa secondo i canoni bootstrap una input email
     * @param type $nameField
     * @param type $label
     */
    protected function printEmailFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value==null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">            
                <input class="form-control" type="email" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired ?> />
            </div>
        </div>
    <?php      
    }
    
    /**
     * La funzione stampa il link al documento di iscrizione/rinnovo
     * @param type $nameField
     * @param type $label
     * @param type $required
     * @param type $value
     */
    protected function printImageLinkDocument($nameField, $label, $required=false, $value=null, $testoModulo){
        global $URL_IMG;
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value==null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">            
                <a title="<?php echo $testoModulo ?>" href="<?php echo $value ?>" target="_blank">
                    <img title="<?php echo $testoModulo ?>" class="modulo-iscrizione" alt="iscrizione-rinnovo" src="<?php echo $URL_IMG ?>/ico_document.png" /> 
                    <span style="padding-left:10px;"><?php echo $testoModulo ?></span>
                </a>
            </div>
        </div>
    <?php      
    }
    
    /**
     * Funzione che stampa secondo i canoni di bootstrap una input file upload
     * @param type $nameField
     * @param type $label
     * @param type $required
     */
    protected function printInputFileFormField($nameField, $label, $required=false){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">    
                <input name="<?php echo $nameField ?>" type="file" class="file" <?php echo $optRequired ?> />
            </div>
        </div>
    <?php
    }
    
    
    protected function printImage($label, $url){
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10"> 
                <img id="<?php echo $label ?>" src="<?php echo $url ?>" alt="<?php echo $label ?>" class="img-responsive" />
            </div>
        </div>
    <?php
    }
    
    /**
     * Funzione che stampa a video una input hidden
     * @param type $nameField
     * @param type $value
     */
    protected function printHiddenFormField($nameField, $value){
    ?>
        <input type="hidden" name="<?php echo $nameField ?>" value="<?php echo $value ?>" />
    <?php
    }
    
    /**
     * Funzione che stampa secondo canoni bootstrap una select box
     * @param type $nameField
     * @param type $label
     * @param type $array
     */
    protected function printSelectFormField($nameField, $label, $array, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">
                <select name="<?php echo $nameField ?>" id="<?php echo $nameField ?>" <?php echo $optRequired ?> >
                <!-- campo vuoto -->
                <option value=""></option>
                <?php
                    foreach($array as $k => $v){
                        if($value == $k){
                            echo '<option value="'.$k.'" selected >'.$v.'</option>';
                        }
                        else{
                            echo '<option value="'.$k.'">'.$v.'</option>';
                        }                        
                    }
                ?>
                </select>
            </div>
        </div>
    <?php      
    }
    
    
    protected function printMultiSelectFormField($nameField, $label, $array, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }       
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">
                <select multiple name="<?php echo $nameField ?>[]" id="<?php echo $nameField ?>" <?php echo $optRequired ?> >                             
                <?php
                    if($value != null){
                        foreach($array as $k => $v){
                            $trovato = false;
                            foreach($value as $item){
                                if($item == $k){
                                    $trovato = $k;
                                }
                            }                            
                            if($trovato != false){
                                echo '<option value="'.$k.'" selected >'.$v.'</option>';
                            }
                            else{
                                echo '<option value="'.$k.'">'.$v.'</option>'; 
                            }
                        }
                    }
                    else{
                        foreach($array as $k => $v){
                            if($value == $k){
                                echo '<option value="'.$k.'" selected >'.$v.'</option>';
                            }
                            else{
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }                        
                        }
                    }
                    
                ?>
                </select>
            </div>
        </div>
    <?php      
    }
    
    /**
     * Funzione che stampa secondo i canoni di bootstrap una input radio button
     * @param type $nameField
     * @param type $label
     * @param type $array
     * @param type $required
     * @param type $value
     */
    protected function printRadioFormField($nameField, $label, $array, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
        
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
        <?php
            foreach($array as $k => $v){
                if($value == $k){
                    echo '<label class="radio-inline"><input type="radio" value="'.$k.'" name="'.$nameField.'" '.$optRequired.' checked>'.$v.'</label>';
                }
                else{
                    echo '<label class="radio-inline"><input type="radio" value="'.$k.'" name="'.$nameField.'" '.$optRequired.'>'.$v.'</label>';
                }
            }
        ?>
        
        
        </div>
    <?php
    }
    
    /**
     * Funzione che stampa un datepicker semplice sulla data di nascita
     * @param type $nameField
     * @param type $label
     * @param type $required
     * @param type $value
     */
    protected function printDateBirthdayFormField($nameField, $label, $required=false, $value=null){
        //il formato da assumere è yyyy-mm-dd
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        $date = array();
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
        if($value != null){
            //spacco il valore
            $temp = explode('-', $value);
            if(count($temp) > 0){
                $date['d'] = intval($temp[2]);
                $date['m'] = $temp[1];
                $date['y'] = intval($temp[0]);
            }
        }
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">
                <!-- giorni -->
                <select class="col-sm-2" name="<?php echo $nameField ?>-d" <?php echo $optRequired ?> >
                <?php
                    for($i=1; $i <= 31; $i++){
                        if($i == $date['d']){
                            echo '<option value="'.$i.'" selected >'.$i.'</option>';
                        }
                        else{
                            echo '<option value="'.$i.'">'.$i.'</option>';
                        }
                    }
                ?>
                </select>
                <!-- mesi -->
                <select class="col-sm-4" name="<?php echo $nameField ?>-m" <?php echo $optRequired ?> >
                    <?php
                        foreach($this->mesi as $k => $v){
                            if($date['m'] == $k){
                                echo '<option value="'.$k.'" selected >'.$v.'</option>';
                            }
                            else{
                                echo '<option value="'.$k.'" >'.$v.'</option>';
                            }
                        }
                    ?>
                </select>
                <!-- anni -->
                <select class="col-sm-3" name="<?php echo $nameField ?>-y" <?php echo $optRequired ?> >
                    <?php
                        for($i= ($this->annoCorrente-100); $i <= $this->annoCorrente; $i++){
                            if($date['y'] == $i){
                                echo '<option value="'.$i.'" selected>'.$i.'</option>';
                            }
                            else{
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
    <?php
    }
    
    /**
     * Funzione che stampa un secondo tipo di datepicker che punta sulla data odierna di default
     * @param type $nameField
     * @param type $label
     * @param type $required
     * @param type $value
     */
    protected function printDateFormField($nameField, $label, $required=false, $value=null){
        //il formato da assumere è yyyy-mm-dd
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        $date = array();
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
        if($value != null){
            //spacco il valore
            $temp = explode('-', $value);
            if(count($temp) > 0){
                $date['d'] = intval($temp[2]);
                $date['m'] = $temp[1];
                $date['y'] = intval($temp[0]);
            }
        }
        
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">
                <!-- giorni -->
                <select class="col-sm-2" name="<?php echo $nameField ?>-d" <?php echo $optRequired ?> >
                <?php
                    for($i=1; $i <= 31; $i++){
                        if(count($date) > 0){
                            //Se è un valore già ottenuto
                            if($i == $date['d']){
                                echo '<option value="'.$i.'" selected >'.$i.'</option>';
                            }
                            else{
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                        }
                        else{
                            //altrimenti mostro il giorno corrente
                            if($i == intval($this->giornoCorrente)){
                                echo '<option value="'.$i.'" selected >'.$i.'</option>';
                            }
                            else{
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }                            
                        }
                    }
                ?>
                </select>
                <!-- mesi -->
                <select class="col-sm-4" name="<?php echo $nameField ?>-m" <?php echo $optRequired ?> >
                    <?php
                        foreach($this->mesi as $k => $v){
                            if(count($date) > 0){
                                if($date['m'] == $k){
                                    echo '<option value="'.$k.'" selected >'.$v.'</option>';
                                }
                                else{
                                    echo '<option value="'.$k.'" >'.$v.'</option>';
                                }
                            }
                            else{
                                if($k == $this->meseCorrente){
                                    echo '<option value="'.$k.'" selected >'.$v.'</option>';
                                }
                                else{
                                    echo '<option value="'.$k.'" >'.$v.'</option>';
                                }                                
                            }
                        }
                    ?>
                </select>
                <!-- anni -->
                <select class="col-sm-3" name="<?php echo $nameField ?>-y" <?php echo $optRequired ?> >
                    <?php
                        for($i= ($this->annoCorrente-1); $i <= $this->annoCorrente+1; $i++){
                            if(count($date) > 0){                            
                                if($date['y'] == $i){
                                    echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                }
                                else{
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                            }
                            else{
                                if($i == $this->annoCorrente){
                                    echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                }
                                else{
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }                                    
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
    <?php
    }
    
    
    protected function printSeachButton($nameField, $label){
    ?>
        <button type="button" class="btn <?php echo $nameField ?>"><?php echo $label ?></button>
    <?php
    }
    
    protected function printSubmitFormField($nameField, $label){
    ?>
        <input name="<?php echo $nameField ?>" type="submit" class="btn btn-success" value="<?php echo $label ?>" />
    <?php
    }
    
    
    protected function printActionDettaglio($nameField){
    ?>
        <input name="update-<?php echo $nameField ?>" type="submit" class="btn btn-primary" value="Aggiorna" />
        <input name="delete-<?php echo $nameField ?>" type="submit" class="btn btn-danger" value="Cancella" />
    <?php
    }
    
    protected function printDeleteDettaglio($nameField){
    ?>        
        <input name="delete-<?php echo $nameField ?>" type="submit" class="btn btn-danger" value="Elimina" />
    <?php    
    }
    
    protected function printUpdateDettaglio($nameField){
    ?>
        <input name="update-<?php echo $nameField ?>" type="submit" class="btn btn-primary" value="Aggiorna" />
    <?php    
    }
    
    /**
     * Stampa un box di messaggio di errore
     * @param type $message
     */
    protected function printErrorBoxMessage($message){
    ?>
        <div class="alert alert-danger">
            <strong>Errore! </strong> <?php echo $message ?>
        </div>
    <?php    
    }
    
    /**
     * Stampa un box di messaggio di ok
     * @param type $message
     */
    protected function printOkBoxMessage($message){
    ?>
        <div class="alert alert-success">
            <strong>OK! </strong> <?php echo $message ?>
        </div>
    <?php    
    }
    
    /**
     * Stampa una tabella di Bootstrap con effetto hover
     * @param type $header
     * @param type $bodyTable
     */
    protected function printTableHover($header, $bodyTable){
        //bodytable è un html del corpo della tabella
        //è diverso per ogni oggetto e viene descritto nelle classi view corrispettive
    ?>
        <table class="table table-hover">
            <thead>
                <tr>
    <?php
            foreach($header as $h){
    ?>                
                    <th><?php echo $h ?></th>
    <?php
            }
    ?>
                </tr>
            </thead>
            <tbody>
                <?php echo $bodyTable ?>
            </tbody>
        </table>
    <?php    
    }
    
    protected function printBodyTable($array){
        
    }
    
    /**
     * Restituisce un campo text in due modalità: non editabile ed editabile
     * @param type $formField
     * @param type $text
     * @param type $edit
     * @return type
     */
    protected function printTextField($formField, $text, $edit=false){
        
        $result = "";
        if($edit == true){
            //campo editabile 
           $result = '<input type="text" name="'.$formField.'" value="'.$text.'" />';
        }
        else{
            $result = $text;
        }
        
        return $result;
    }
    
    /**
     * Restituisce l'html per un piccolo form di aggiornamento di un campo
     * @param type $id
     * @param type $fieldUPDATE
     * @return string
     */
    protected function printUpdateFieldForm($id, $nameField, $fieldUPDATE){
        $html = "";
        $html.= '<form action="'.curPageURL().'" method="POST">';
        $html.= '<input type="hidden" name="id" value="'.$id.'" />';
        $html.= $fieldUPDATE;
        $html.= '<input type="submit" name="update-'.$nameField.'" class="btn btn-primary" value="AGGIORNA">';
        $html.= '</form>';
        
        return $html;
    }
    
    /**
     * Restituisce l'html per un piccolo form di cancellazione di un record dal database
     * @param type $id
     * @return string
     */
    protected function printDeleteForm($id, $nameField){
        $html = "";
        $html.= '<form action="'.curPageURL().'" method="POST">';
        $html.= '<input type="hidden" name="id" value="'.$id.'" />';        
        $html.= '<input type="submit" name="delete-'.$nameField.'" class="btn btn-danger" value="CANCELLA">';
        $html.= '</form>';
        
        return $html;
    }

    
    /**
     * La funzione controlla il valore di un campo obbligatorio e lo restituisce in caso di successo, false in caso di errore
     * @param type $nameField
     * @return boolean
     */
    protected function checkRequiredSingleField($nameField, $labelField){
        if(isset($_POST[$nameField]) && trim($_POST[$nameField]) != ''){
            return trim($_POST[$nameField]);
        }
        $this->printErrorBoxMessage('Campo '.$labelField.' mancante o non corretto.');
        return false;        
    }
    
    /**
     * La funzione controlla il valore di un campo e lo restitusce se questo è stato compilato
     * @param type $nameField
     * @return boolean
     */
    protected function checkSingleField($nameField){
        if(isset($_POST[$nameField]) && trim($_POST[$nameField]) != ''){
            return trim($_POST[$nameField]);
        }
        return false;
    }
    
    public function translateDate($date){       
        //la data si suddivide in nome del giorno, numero e mese        
        $temp = explode('-', $date);
        $giorno = $this->giorni[$temp[0]];
        $mese = $this->mesi[$temp[2]];
        
        return $giorno.' '.$temp[1].' '.$mese;
    }
    
    protected function printArraySuggestion($array){
        $count = 0;
        $html = "";
        foreach($array as $item){
            if($count < count($array) -1){
                $html.= '"'.$item.'",';
            }
            else{
                $html.= '"'.$item.'"';
            }
            $count++;
        }
        return $html;
    }
}
