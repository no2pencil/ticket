<?php
/*
 * pdf.class.php
 * Helps create PDFs out of information - mainly invoices.
 */
class pdf extends framework {
    public $error;
    private $fpdf;
    
    public function __construct(){
        error_reporting(1);
    }
    
    /*
     * invoice($invoice_number)
     * 
     */
    public function invoice($invoice_number){
        $pdf = $this->getFPDF();
        if(!$pdf){
            return false;
        }
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'INVOICE', 'B', 1, 'C');
        $pdf->Ln(10);
        
        $pdf->SetFont('Arial', '', '13');
        $cols = array(
            array("Item", 40, "L"),
            array("Price", 30, "R")
        );
        $data = array(
            array("Web design", "$99"),
            array("Domain", "$100"),
            array("Hosting", "$150")
        );
        $this->table($pdf, $cols, $data);
        
        $pdf->SetY(-31);
        $pdf->SetFont("Arial", 'I', 8);
        $pdf->Cell(0, 10, 'Copyright 2012', 0, 0, 'C');
        
        $pdf->Output();
    }
    
    /*
     * table(fpdf $fpdf, array $header, array $data)
     * Creates a table in $fpdp with the data provided
     * header should contain arrays like: array(TITLE, WIDTH, TEXT-ALIGN) - Example: array('price', 40, 'R')
     * Data should also contain arrays inside of it's array. The arrays inside should contain the same amount of values as there are columns.
     */
    private function table($pdf, $header, $data){
        foreach($header as $col){
            $pdf->Cell($col[1], 7, $col[0], 1, 0, $col[2]);
        }
        $pdf->Ln();
        foreach($data as $row){
            foreach($row as $key => $col){
                $pdf->Cell($header[$key][1], 7, $col, 'LR', 0, $header[$key][2]);
            }
            $pdf->Ln();
        }
        foreach($header as $h){
            $width += $h[1];
        }
        $pdf->Cell($width, 0, '', 'T');
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