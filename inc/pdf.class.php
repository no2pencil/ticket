<?php
/*
 * pdf.class.php
 * Helps create PDFs out of information - mainly invoices.
 */
class pdf extends framework {
    public $error;
    private $fpdf;
    
    /*
     * invoice($invoice_number)
     * 
     */
    public function invoice($invoice_number){
        $pdf = $this->getFPDF();
        if(!$pdf){
            return false;
        }
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Image('logo.png', 10, 6, 30);
        
        $pdf->Output();
    }
    
    public function getFPDF(){
        if(!isset($this->fpdf)){
            if((include 'inc/libs/fpdf/fpdf.php') == 1){
                $this->fpdf = new FPDF(); // Default settings: A4 paper, unit of measure is millimeters.
            } else {
                $this->error = "Error setting FPDF object";
                return false;
            }
        }
        return $this->fpdf;
    }
}
?>