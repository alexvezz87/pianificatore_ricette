<?php


/**
 * Description of PdfController
 *
 * @author Alex
 */
require_once 'fpdf/fpdf.php';

class PdfController extends FPDF {
    
    function __construct(){
        parent::__construct();
    }
}
