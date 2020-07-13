<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start(); // Initialize Session data
ob_start(); // Turn on output buffering

require("includes/class/connection.class.php");  // Inclui a class de conexao
require("includes/class/funcoes.class.php");  // Inclui a class de conexao
require_once('includes/class/pdf/tcpdf.php');

$conexao = new connection; 					// Cria o objeto
$conexao -> conectar();						// Inicia a conexao
$conexao -> selecionardb(); 				// Seleciona a conexao
$funcoes = new funcoes;

$linhasAux = "";
$precoTotal = 0;
$precoParcela = 0;
$quantidadeParcelas = 0;

//$codConcessionaria = $funcoes->retira_aspas($_GET['codConcessionaria']);
$codConcessionaria = $funcoes->retira_aspas(245);

//$codCheckList = $funcoes->retira_aspas($_GET['codCheckList']);
$codCheckList = $funcoes->retira_aspas(300010);

//$checklist = $funcoes->retira_aspas($_GET['checklist']);
$checklist = $funcoes->retira_aspas(26);

if ((strlen($codCheckList) > 0) && (strlen($checklist) > 0))
{
	$conexao -> sql("SELECT *
					 FROM checklist
					 WHERE checklist = $checklist");

	$rsTabela = $conexao -> query();
	$n_rowsTabela = mysqli_num_rows($rsTabela);

	if ($n_rowsTabela > 0)
	{
		while($rsTabelax = mysqli_fetch_array($rsTabela))
		{
			$tabela = $rsTabelax['tabela'];
		}

		$opcTipoServico= "";

		if($checklist == 7 || ($checklist == 26))
		{
			$opcTipoServico = ", CASE tipo_servico WHEN '1' THEN CASE temDiagnostico WHEN '1' THEN 'Revisão - Diagnóstico / Reparo / Outros' else 'Revisão' END WHEN '2' THEN  CASE temDiagnostico WHEN '1'  THEN 'Boas-Vindas - Diagnóstico / Reparo / Outros' else 'Boas-Vindas' END WHEN '3' THEN 'Diagnóstico / Reparo / Outros' END AS tipo_servico, m_observacoes as obsMecanico ";
		}

		if($checklist == 2)
		{
			$opcTipoServico = ", CASE tipo_servico WHEN '1' THEN 'Oficina' WHEN '2' THEN 'Express Service' END AS tipo_servico, '' as obsMecanico  ";
		}

		if($checklist == 6 || $checklist == 11 || $checklist == 13 || $checklist == 18 || $checklist == 19 || $checklist == 20)
		{ 
			$opcTipoServico = ", tipo_os AS tipo_servico, '' as obsMecanico  ";
		}

		
		$conexao -> sql("SELECT ck.codChecklist, ck.cliente, ck.email, ck.telefone, c.nome, ck.os, ck.placa, ck.modelo 
						 FROM $tabela as ck 
						 JOIN consultor as c on ck.codConsultor = c.codConsultor 
				 		 WHERE ck.codCheckList = $codCheckList ");
		$rs = $conexao -> query();
		$n_rows = mysqli_num_rows($rs);
		if($n_rows > 0)
		{
			while($rsx = mysqli_fetch_array($rs))
			{
				$nomeCliente = $rsx['cliente'];
				$emailCliente = $rsx['email'];
				$telefoneCliente = $rsx['telefone'];
				$nomeConsultor = $rsx['nome'];
				$os = $rsx['os'];
				$placaVeiculo = $rsx['placa'];
				$modeloVeiculo = $rsx['modelo'];
				$tipoServico = $rsx['tipo_servico'];
				$obsMecanico = $rsx['obsMecanico'];
			}
		}
	
	}else{
		ob_end_clean();
		die('Nenhum registro encontrado para o Checklist Informado.');
		exit();
	}



	if(strlen($codConcessionaria) > 0 ){
		$conexao -> sql("SELECT c.codMarca, c.codGrupo, c.nomeFantasia, c.cnpj, c.telefone, c.endereco, m.logomarca 
						 FROM concessionaria as c 
						 JOIN marca as m on c.codMarca = m.codMarca
						 WHERE codConcessionaria = $codConcessionaria");
		$rs = $conexao -> query();
		$n_rows = mysqli_num_rows($rs);
		if($n_rows  > 0)
		{
			while($rsx = mysqli_fetch_array($rs))
			{
				$codMarca = $rsx['codMarca'];
				$codGrupo = $rsx['codGrupo'];
				$nomeFantasia = $rsx['nomeFantasia'];
				$cnpjConcessionaria = $rsx['cnpj'];
				$telefoneConcessionaria = $rsx['telefone'];
				$enderecoConcessionaria = $rsx['endereco'];
				$logo = $rsx['logomarca'];
			}
		}

		$conexao -> sql("SELECT co.codigo, co.item, co.codigoTMO, co.tempoMO, co.precoTotal, co.parcelas, co.dataAprovacao 
						 FROM checklist_orcamento as co 
						 WHERE co.codConcessionaria = $codConcessionaria 
						 AND co.checklist = $checklist 
						 AND co.codCheckList = $codCheckList 
						 AND co.dataAprovacao is not null ");
		$rs = $conexao -> query();
		$n_rows = mysqli_num_rows($rs);
		if($n_rows  > 0)
		{
			$itensAprovados = array(); 
			while($rsx = mysqli_fetch_array($rs))
			{
				$item = array(
					'codigoItem' => $rsx['codigo'],
					'descricaoItem' => $rsx['item'],
					'codigoTMO' => $rsx['codigoTMO'],
					'tempoMO' => $rsx['tempoMO'],
					'precoTotal' => $rsx['precoTotal'],
					'parcelas' => $rsx['parcelas'],
					'dataAprovacao' => $rsx['dataAprovacao']
				);
				array_push($itensAprovados, $item);
			}
		}

		$item = "";

		$conexao -> sql("SELECT co.item, co.precoTotal, co.motivoReprovacao
						 FROM checklist_orcamento as co 
						 WHERE co.codConcessionaria = $codConcessionaria 
						 AND co.checklist = $checklist 
						 AND co.codCheckList = $codCheckList 
						 AND co.dataReprovacao is not null");
		$rs = $conexao -> query();
		$n_rows = mysqli_num_rows($rs);
		if($n_rows  > 0)
		{
			$itensReprovados = array(); 
			while($rsx = mysqli_fetch_array($rs))
			{
				$item = array(
					'descricaoItem' => $rsx['item'],
					'precoTotal' => $rsx['precoTotal'],
					'motivoReprovacao' => $rsx['motivoReprovacao']
				);
				array_push($itensReprovados, $item);
			}
		}

		$conexao -> fecharconn();
	}
	else
	{
		ob_end_clean();
		die('Parametro concessionaria não encontrado.');
		exit();
	}
}else{
	ob_end_clean();
	die('Parametro codChecklist ou checklist não foram encontrados.');
	exit();
}


//classe MYPDF extendendo TCPDF para customização do cabeçalho e rodapé
class MYPDF extends TCPDF {
	//Cabeçalho
	private $nomeFantasia;
	private $cnpjConcessionaria;
	private $enderecoConcessionaria;
	private $telefoneConcessionaria;
	private $logo;

	public function GetNomeFantasia(){
		return $this->nomeFantasia;
	}
	public function SetNomeFantasia($nomeFantasia){
		$this->nomeFantasia = $nomeFantasia;
	}

	public function GetCnpjConcessionaria(){
		return $this->cnpjConcessionaria;
	}
	public function SetCnpjConcessionaria($cnpjConcessionaria){
		$this->cnpjConcessionaria = $cnpjConcessionaria;
	}

	public function GetEnderecoConcessionaria(){
		return $this->enderecoConcessionaria;
	}
	public function SetEnderecoConcessionaria($enderecoConcessionaria){
		$this->enderecoConcessionaria = $enderecoConcessionaria;
	}
	
	public function GetTelefoneConcessionaria(){
		return $this->telefoneConcessionaria;
	}
	public function SetTelefoneConcessionaria($telefoneConcessionaria){
		$this->telefoneConcessionaria = $telefoneConcessionaria;
	}

	public function GetLogo(){
		return $this->logo;
	}
	public function SetLogo($logo){
		$this->logo = $logo;
	}

  	public function Header() {
	    // Logo
  		if(($this->logo == "NULL") || ($this->logo == ""))
  		{
		    $logo = K_PATH_IMAGES.'logo_example.jpg';
  			$this->Image($logo, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
  		}else{
  			$logo = '../img_marca/'.$this->logo;
		    $this->Image($logo, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
  		}

	    // Set font
	    $this->SetFont('helvetica', '',10);
	    $this->SetY(15);
	    $this->SetX(50);
	    $this->Cell(0, 15, 'ORÇAMENTO', 0, false, 'L', 0, '', 0, false, 'M', 'M');
	    $this->SetFont('helvetica', '',9);
	    $this->SetY(10);
	    $this->Cell(0, 15, $this->nomeFantasia, 0, false, 'R', 0, '', 0, false, 'M', 'M');
	    $this->SetY(14);
	    $this->Cell(0, 15, $this->cnpjConcessionaria, 0, false, 'R', 0, '', 0, false, 'M', 'M');
	    $this->SetY(18);
	    $this->Cell(0, 15, $this->enderecoConcessionaria, 0, false, 'R', 0, '', 0, false, 'M', 'M');
	    $this->SetY(22);
	    $this->Cell(0, 15, $this->telefoneConcessionaria, 0, false, 'R', 0, '', 0, false, 'M', 'M');
	    
	}
	// Rodapé
	public function Footer() {}
}

//Setando variaveis e configurações do pdf  e header
if(($itensAprovados != null) || ($itensAprovados != "") ){
	$dataAprovacao = $itensAprovados[0]['dataAprovacao'];
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Lucas de Aguiar');
$pdf->SetNomeFantasia($nomeFantasia);
$pdf->SetCnpjConcessionaria($cnpjConcessionaria);
$pdf->SetEnderecoConcessionaria($enderecoConcessionaria);
$pdf->SetTelefoneConcessionaria($telefoneConcessionaria);
$pdf->SetLogo($logo);
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

// -------------------Informações primárias-------------------------------------
$tbl = <<<EOD
<table cellspacing="0" cellpadding="4" border="1">
    <tr>
        <td>Nome Cliente:</td>
        <td colspan="2">$nomeCliente</td>
    </tr>
    <tr>
        <td>Telefone:</td>
        <td colspan="2">$telefoneCliente</td>
    </tr>
    <tr>
        <td>E-mail:</td>
        <td colspan="2">$emailCliente</td>
    </tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');
//-------------------------------------------------------------------------------

//---------------------Informações Adicionais------------------------------------
$tb2 = <<<EOD
<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td>Informações Adicionais:</td>
        <td></td>
    </tr>
    <tr>
        <td>Modelo Veículo: $modeloVeiculo</td>
        <td>OS: $os</td>
    </tr>
    <tr>
        <td>Placa: $placaVeiculo</td>
        <td>Data Aprovação: $dataAprovacao</td>
    </tr>
    <tr>
        <td>Consultor: $nomeConsultor</td>
    </tr>
</table>
EOD;
$pdf->writeHTML($tb2, true, false, false, false, '');
//-------------------------------------------------------------------------------

//---------------------Serviços Aprovados----------------------------------------
$tb3 =<<<EOD
<table cellspacing="0" cellpadding="4" border="0">
    <tr>
      <td colspan = "3">Serviços Aprovados</td>
    </tr>
</table>
<br>
<table cellspacing="0" cellpadding="4" border="1">
    <tr>
        <td>Serviço</td>
        <td>Código Peça</td>
        <td>Código TMO</td>
        <td>Tempo MO</td>
        <td>Preço Total</td>
    </tr>
EOD;

$linhasAux = "";

for ($i=0; $i < count($itensAprovados); $i++) { 
	$linha = "<tr><td>".$itensAprovados[$i]["descricaoItem"]."</td><td>".$itensAprovados[$i]["codigoItem"]."</td><td>".$itensAprovados[$i]["codigoTMO"]."</td><td>".$itensAprovados[$i]["tempoMO"]."</td><td> R\$ ".$itensAprovados[$i]["precoTotal"]."</td></tr>";
	$linhasAux = $linhasAux.$linha;
	$aux = str_replace(",",".", $itensAprovados[$i]["precoTotal"]);
	$precoTotal = (double)$precoTotal + (double)$aux;
}

$quantidadeParcelas = (int)$itensAprovados[0]["parcelas"];
if($quantidadeParcelas != 0){
	$precoParcela = (double)$precoTotal/$quantidadeParcelas;
}

$tb4 = <<<EOD
	$linhasAux
	</table>
EOD;
$pdf->writeHTML($tb3.$tb4, true, false, false, false, '');
//--------------------------------------------------------------------------------

//-------------------------Parcelas e Total --------------------------------------
$precoParcela = number_format($precoParcela, 2, ',', '');
$precoTotal = number_format($precoTotal, 2, ',', '');

if ($quantidadeParcelas == 0) {
# code...
$tb5 = <<<EOD
<table cellspacing="0" cellpadding="4" border="0">
    <tr>
      <td align="left"></td>
      <td align="right">Total: R\$ $precoTotal</td>
    </tr>
</table>
EOD;
$pdf->writeHTML($tb5, true, false, false, false, '');
}else{
$tb5 = <<<EOD
<table cellspacing="0" cellpadding="4" border="0">
    <tr>
      <td align="left"></td>
      <td align="right">Total: $quantidadeParcelas X R\$ $precoParcela</td>
    </tr>
</table>
EOD;
$pdf->writeHTML($tb5, true, false, false, false, '');
}
//--------------------------------------------------------------------------------

//--------------------Serviços Reprovados-----------------------------------------
$linhasAux ="";
$linha = "";

$tb6 =<<<EOD
<table cellspacing="0" cellpadding="4" border="0">
    <tr>
      <td colspan = "3">Serviços Reprovados</td>
    </tr>
</table>
<br>
<table cellspacing="0" cellpadding="4" border="1">
    <tr>
        <td>Serviço</td>
        <td>Motivo</td>
        <td>Preço Total</td>
    </tr>
EOD;

for ($i=0; $i < count($itensReprovados); $i++) { 
	$linha = "<tr><td>".$itensReprovados[$i]["descricaoItem"]."</td><td>".$itensReprovados[$i]["motivoReprovacao"]."</td><td> R\$ ".$itensReprovados[$i]["precoTotal"]."</td></tr>";
	$linhasAux = $linhasAux.$linha;
}

$tb7 = <<<EOD
	$linhasAux
	</table>
EOD;

$pdf->writeHTML($tb6.$tb7, true, false, false, false, '');
//-----------------------------------------------------------------------------

//-------------------------Assinatura------------------------------------------
$tb8 = <<<EOD
<table cellspacing="0" cellpadding="4" border="0">
    <tr>
      <td align="left" height="60px"></td>
      <td></td>
    </tr>
    <tr>
      <td align="left"><hr>Assinatura do Cliente</td>
      <td></td>
    </tr>
</table>
EOD;
$pdf->writeHTML($tb8, true, false, false, false, '');
//------------------------------------------------------------------------------

//Fechando e gerando arquivo pdf
ob_end_clean();
$pdf->Output('orcamento.pdf', 'I');

?>
