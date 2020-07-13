<?php

//importando biblioteca
require_once('./TCPDF-master/examples/tcpdf_include.php');
require_once('./TCPDF-master/tcpdf_import.php');
require_once('mypdf_class.php');

$nome_cliente = "";
$telefone_cliente = "";
$email_cliente = "";
$modelo_veiculo = "";
$revisao_veiculo = "";
$placa_veiculo = "";
$os = "";
$data_os = "";
$consultor = "";
$itens_revisao = [];
$servicos_manutencao = [];

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Lucas de Aguiar');
$pdf->SetTitle('Orcamento');
$pdf->SetSubject('Orcamento');
$pdf->SetKeywords('Orcamento, PDF, Atria');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__).'/lang/bra.php')) {
	require_once(dirname(__FILE__).'/lang/bra.php');
	$pdf->setLanguageArray($l);
}
// ---------------------------------------------------------
// Adicionando Página
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);
$pdf->SetY(30);
$pdf->SetX(15);
// -----------------------------------------------------------------------------
$tbl = <<<EOD
<table cellspacing="0" cellpadding="4" border="1">
    <tr>
        <td>Nome Cliente:</td>
        <td colspan="2">$nome_cliente</td>
    </tr>
    <tr>
        <td>Telefone:</td>
        <td colspan="2">$telefone_cliente</td>
    </tr>
    <tr>
        <td>E-mail:</td>
        <td colspan="2">$email_cliente</td>
    </tr>
</table>
<br><br>
<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td>Informações Adicionais:</td>
        <td></td>
    </tr>
    <tr>
        <td>Modelo Veículo: $modelo_veiculo</td>
        <td>OS: $os</td>
    </tr>
    <tr>
        <td>Revisão: $revisao_veiculo</td>
        <td>Data: $data_os</td>
    </tr>
     <tr>
        <td>Placa: $placa_veiculo</td>
        <td>Consultor: $consultor</td>
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

?>