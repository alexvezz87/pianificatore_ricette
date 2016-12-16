<?php
namespace pianificatore_ricette;
/**
 * Description of PdfController
 *
 * @author Alex
 */
require_once 'fpdf/fpdf.php';

class PdfController extends FPDF {
    
    private $tpC;
    var $widths;
    var $aligns;    
    
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
    }
    
    
    public function createCalendarioHeader($name){
        $this->SetFont('Arial','B',18);
        $this->Cell($this->GetPageWidth()-20,15, 'Agenda personalizzata di '.$name,0,'C');
        
    }
    
    public function printListaIngredienti($ingredienti){
        
        $this->Ln();
        $count = 1;
        foreach($ingredienti as $key => $value){  
            
            $string = "";
            
            $this->SetFont('Arial','',10);
            
            if($value['qt']!= '' && $value['qt']!= '0'){
                $string.= $value['qt'].' ';
            }
            
            if($value['um']!='' && $value['um']!='q.b.'){
                $string.= $value['um'].' ';
            }           
            
            if($key != ''){
                $string.= $key;
            } 
            $this->Cell(round(($this->GetPageWidth()-20)/2), 8, utf8_decode('- '.$string), 0, 0);
            if($count == 2){
                $this->Ln();
                $count = 0;
            }
            $count++;
        }
        
    }
    
    
    public function printCalendario($calendario){
        
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
    
    
    public function printCalendario2($calendario){
        //ottengo le tipologie pasto e le inserisco in un array
        $tps = $this->tpC->getTipologiaPasti();
        $arrayPasti = array();        
        array_push($arrayPasti, 'Preparazione');        
        foreach($tps as $tipo){
            $tp = new TipologiaPasto();
            $tp = $tipo;
            array_push($arrayPasti, $tp->getNome());
        }       
        
        //ottengo la dimensione di ogni singola cella
        $maxWidth = $this->GetPageWidth() - 20;
        $singleCell = round($maxWidth / (count($arrayPasti) + 1));
                
        $arrayAltezza = array();
        for($i=0; $i < count($arrayPasti) +1; $i++){
            array_push($arrayAltezza, $singleCell);
        }
        
        //stampo la prima riga, le tipologie di pasto
        //prima colonna vuota
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell($singleCell,12,' ',1,0,'C');
        foreach($arrayPasti as $pasto){
            $this->Cell($singleCell, 12, utf8_decode($pasto),1,0,'C');
        }
        //vado a capo
        $this->Ln();
        
        //stampo il calendario
        if($calendario > 0){
            foreach($calendario as $giorni){
                $arrayGiorno = array();
                foreach($giorni as $keyG => $valueG){
                    
                    //$this->Cell($singleCell, 12, utf8_decode($keyG),1,0,'C');
                    array_push($arrayGiorno, utf8_decode($keyG));
                    
                    foreach($arrayPasti as $pasto){
                        $string = "";
                        $count = 0;
                        foreach($valueG[$pasto] as $item){
                            if($item != null && $item != ''){
                                $string.='- '.utf8_decode($item).PHP_EOL;    
                            }
                        }
                        //$this->Cell($singleCell, 12, $string,1,0,'C');
                        array_push($arrayGiorno, $string);
                    }
                    //$this->Ln();
                }
                $this->SetWidths($arrayAltezza);
                $this->Row($arrayGiorno);
            }
        }
    }
    
    function SetWidths($w){
	//Set the array of column widths
	$this->widths=$w;
    }

    function SetAligns($a){
	//Set the array of column alignments
	$this->aligns=$a;
    }

    function Row($data){
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=6*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border
		$this->Rect($x,$y,$w,$h);
		//Print the text
                $fontSize = 8;
                $hCell = 5;
                if($i==0){
                    $fontSize = 10;
                    $hCell = 6;
                }
                $this->SetFont('Arial','',$fontSize);
		$this->MultiCell($w,$hCell,$data[$i],0,$a);
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
    }

    function CheckPageBreak($h){
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
    }

    function NbLines($w,$txt){
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
    }

}
