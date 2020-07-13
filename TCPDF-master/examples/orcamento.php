<?php
//============================================================+
// File name   : example_048.php
// Begin       : 2009-03-20
// Last Update : 2013-05-14
//
// Description : Example 048 for TCPDF class
//               HTML tables and table headers
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: HTML tables and table headers
 * @author Nicola Asuni
 * @since 2009-03-20
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

  //Page header
  public function Header() {
    // Logo
    $image_file = K_PATH_IMAGES.'logo_example.jpg';
    $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    // Set font
    $this->SetFont('helvetica', '',10);
    // Title
    $this->SetY(15);
    $this->SetX(50);
    $this->Cell(0, 15, 'ORÇAMENTO', 0, false, 'L', 0, '', 0, false, 'M', 'M');
    $this->SetFont('helvetica', '',9);
    $this->SetY(10);
    $this->Cell(0, 15, 'Honda Faberge - Mogi das Cruzes', 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetY(14);
    $this->Cell(0, 15, 'CNPJ: 06.900.979/0001-30', 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetY(18);
    $this->Cell(0, 15, 'Rua Basílio Batalha, 297 - Vila Vitória - Mogi das Cruzes,', 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetY(22);
    $this->Cell(0, 15, '08730-090', 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetY(26);
    $this->Cell(0, 15, '(11) 98545-6818', 0, false, 'R', 0, '', 0, false, 'M', 'M');
  }

  // Page footer
  public function Footer() {
    
  }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Lucas de Aguiar');
$pdf->SetTitle('Orcamento');
$pdf->SetSubject('Orcamento');
$pdf->SetKeywords('Orcamento, PDF, atria');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/bra.php')) {
	require_once(dirname(__FILE__).'/lang/bra.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------


// add a page
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 10);
$pdf->SetY(30);
$pdf->SetX(15);
// -----------------------------------------------------------------------------

$tbl = <<<EOD
<table cellspacing="0" cellpadding="4" border="1">
    <tr>
        <td>Nome Cliente:</td>
        <td colspan="2">Lucas de Aguiar Pereira</td>
    </tr>
    <tr>
        <td>Telefone:</td>
        <td colspan="2">(31) 99642-4959</td>
    </tr>
    <tr>
        <td>E-mail:</td>
        <td colspan="2">de.lucas73@gmail.com</td>
    </tr>
</table>
<br><br>
<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td>Informações Adicionais:</td>
        <td></td>
    </tr>
    <tr>
        <td>Modelo Veículo: Uno</td>
        <td>OS:</td>
    </tr>
    <tr>
        <td>Revisão: Promoção</td>
        <td>Data: 6/7/2020 - 8:31:39</td>
    </tr>
     <tr>
        <td>Placa: ABC-2312</td>
        <td>Consultor: Honda Faberge - Mogi das Cruzes</td>
    </tr>
</table>
<br><br>
<table cellspacing="0" cellpadding="4" border="0">
    <tr>
      <td colspan = "3">Itens de Revisão - Código Promoção</td>
    </tr>
</table>
<br>
<table cellspacing="0" cellpadding="4" border="1">
    <tr>
        <td></td>
        <td colspan="2">1 - Pacote Promocional - Faberge</td>
    </tr>
</table>
<br><br>
<table cellspacing="0" cellpadding="4" border="0">
    <tr>
      <td colspan = "3">Serviços de Manutenção - Código 518</td>
    </tr>
</table>
<br>
<table cellspacing="0" cellpadding="4" border="1">
    <tr>
        <td>Peça</td>
        <td>MO</td>
        <td>Serviço</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr><tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
<br><br>
<table cellspacing="0" cellpadding="4" border="0">
    <tr>
      <td align="left" height="50px"><br><br><br><hr>Assinatura do Cliente</td>
      <td align="right">Total: 8 x R$ 125,00</td>
    </tr>
</table>


EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');



//Close and output PDF document
$pdf->Output('orcamento.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>