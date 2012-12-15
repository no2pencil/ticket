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
        $pdf->SetFont('Arial', 'B', 23);
        $pdf->Cell(0, 10, 'INVOICE', 'B', 1, 'C');
        $pdf->Ln(10);
        
        $pdf->SetFont('Arial', '', '13');

        $cols = array(
            array("Bill to", "L"),
            array("Ship to", "L")
        );
        $data = array(
            array("Name: Sean Grant", "Name: Sean Grant"),
            array("My adress 1234 Street City, State", )
        );

        $cols = array(
            array("Order Number", "C"),
            array("Invoice Date", "C"),
            array("Delivered Date", "C"),
            array("Delivered Via", "C")
        );
        $data = array(
            array(1234, date('m/d/y'), '11/11/11', 'Email: Business@name.com')
        );
        $this->table($pdf, $cols, $data);
        $pdf->Ln(5);
        
        $cols = array(
            array("Item", "L"),
            array("Price", "R")
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
     * header should contain arrays like: array(TITLE, TEXT-ALIGN) - Example: array('price', 'R')
     * Data should also contain arrays inside of it's array. The arrays inside should contain the same amount of values as there are columns.
     */
    private function table($pdf, $header, $data){
        // Calculate widths
        foreach($data as $row){
            foreach($row as $key => $col){
                $dat_w = $pdf->GetStringWidth($col);
                $col_w = $pdf->GetStringWidth($header[$key][0]);
                if($dat_w >= $col_w){
                    if($header[$key][2] < ($dat_w + 5)){
                        $header[$key][2] = $dat_w + 5;
                    }
                } else {
                    if($header[$key][2] < ($col_w + 5)){
                        $header[$key][2] = $col_w + 5;
                    }
                }
            }
        }
        
        // Draw header
        foreach($header as $key => $col){
            $pdf->Cell($col[2], 7, $col[0], 1, 0, $col[1]);
        }
        $pdf->Ln();
        
        // Draw data
        foreach($data as $row){
            foreach($row as $key => $col){
                $pdf->Cell($header[$key][2], 7, $col, 'LR', 0, $header[$key][1]);
            }
            $pdf->Ln();
        }
        
        // Bottom line
        foreach($header as $h){
            $width += $h[2];
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
