<?php


/**
 * Description of PdfController
 *
 * @author Alex
 */
require_once 'fpdf/fpdf.php';

class PdfController extends FPDF {
    
    private $tpC;
    
    function __construct(){
        parent::__construct();
        $this->tpC = new TipologiaPastoController();
    }
    
    public function setPage(){
        $this->AliasNbPages();
        $this->AddPage();
        $this->SetFont('Times','',12);
        
    }    
    
    
    public function savePDF($path){       
        //salvo il file nel file system
        $this->Output($path, 'F');   
    }
    
    
    
    public function createListaHeader(){
        $this->SetFont('Arial','B',18);
        $this->Cell($this->GetPageWidth()-20,15, 'Lista ingredienti',0,0,'C');
        $this->Ln();
    }
    
    
    public function createCalendarioHeader($name){
        $this->SetFont('Arial','B',18);
        $this->Cell($this->GetPageWidth()-20,15, 'Agenda personalizzata di '.$name,0,'C');
        
    }
    
    public function printCalendario($calendario){
        //imposto il bordo
        $border = 0;
        
        $tps = $this->tpC->getTipologiaPasti();
        
        //stampo il giorno        
        foreach($calendario as $data => $dataValue){ 
            $this->Ln();
            $this->SetFont('Arial','',15);
            $this->SetFillColor(191, 207, 255);
            $this->MultiCell($this->GetPageWidth()-20, 12, utf8_decode($data), 1, 'C', true);
            //$this->Ln();
            //stampo le preparazioni
            $this->SetFont('Arial','',12);
            if(isset($calendario[$data]['Preparazione'])){   
                $this->SetFillColor(223, 191, 255);
                $string="";
                for($i=0; $i < 5; $i++){
                    $string.=" ";
                }
                $string.="Preparazioni";
                $this->MultiCell($this->GetPageWidth()-20, 8, $string, 1, 'L', true);
                //$this->Ln();
                //$this->Rect($this->GetX(), $this->GetY(), $this->GetPageWidth()-20, (count($calendario[$data]['Preparazione'])*6));
                //ciclo le preparazioni
                foreach($calendario[$data]['Preparazione'] as $preparazioni){
                    
                    foreach($preparazioni as $preparazione){                        
                        $this->SetFont('Arial','',8);  
                        $string="";
                        for($i=0; $i < 15; $i++){
                            $string.=" ";
                        }
                        $string.=$preparazione;
                        $this->MultiCell($this->GetPageWidth()-20, 6, utf8_decode($string), 1);
                        //$this->Ln();
                    }
                }                
            }
            
            foreach($tps as $tipoPasto){
                $tp = new TipologiaPasto();
                $tp = $tipoPasto;
                if(isset($calendario[$data][$tp->getNome()])){                    
                    $this->SetFont('Arial','',12);   
                    $this->SetFillColor(223, 191, 255);
                    $string="";
                    for($i=0; $i < 5; $i++){
                        $string.=" ";
                    }
                    $string.=$tp->getNome();
                    
                    $this->MultiCell($this->GetPageWidth()-20, 8, utf8_decode($string), 1, 'L', true);
                    //$this->Ln();
                    //$this->Rect($this->GetX(), $this->GetY(), $this->GetPageWidth()-20, (count($calendario[$data][$tp->getNome()][0])*6));
                
                    foreach($calendario[$data][$tp->getNome()] as $tipi){
                        foreach($tipi as $tipo ){
                           
                            $this->SetFont('Arial','',8);  
                            $string="";
                            for($i=0; $i < 15; $i++){
                                $string.=" ";
                            }
                            $string.=$tipo;
                            
                            $this->MultiCell($this->GetPageWidth()-20, 6, utf8_decode($string), 1);
                            //$this->Ln();
                        }
                    }
                }
            }
            
           
            
            
            
           
        }
    }
}
