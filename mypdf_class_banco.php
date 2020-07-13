<?php

//importando biblioteca
require_once('./TCPDF-master/examples/tcpdf_include.php');
require_once('./TCPDF-master/tcpdf_import.php');

//classe MYPDF extendendo TCPDF para customização do cabeçalho e rodapé
class MYPDF extends TCPDF {
  //Cabeçalho
  public function Header() {
    // Logo

    session_start();

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $method = $_SERVER['REQUEST_METHOD'];

    if($method != 'GET')
    {
      // para nao abrir duas x no dispositvo
      die();
    }

    require("../../includes/config.php");
    require("../../includes/seguranca.php");
    require("../../includes/funcoes.php");

    $conexao = new connection;          // Cria o objeto
    $conexao -> conectar();           // Inicia a conexao
    $conexao -> selecionardb();         // Seleciona a conexao
    $funcoes = new funcoes;

    if (isset($_GET["codCheckList"]))
    {
      $conexao -> sql(" Select * From concessionaria 
                        where codConcessionaria = '".$dadosChecklist['codConcessionaria']."'
                      ");

      $rs = $conexao -> query();
      $n_rows = mysqli_num_rows($rs);
      if($n_rows  > 0)
      {
        $concessionaria = array();
        $row = mysqli_fetch_assoc($rs);  
        $concessionaria = $row;
      }
    }
    else
    {
      die('sem codCheckList');
    }


    $image_file = K_PATH_IMAGES.'logo_example.jpg';
    $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    // Set font
    $this->SetFont('helvetica', '',10);
    
    $this->SetY(15);
    $this->SetX(50);
    $this->Cell(0, 15, 'ORÇAMENTO', 0, false, 'L', 0, '', 0, false, 'M', 'M');
    $this->SetFont('helvetica', '',9);
    $this->SetY(10);
    $this->Cell(0, 15,  $concessionaria['nomeFantasia'] , 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetY(14);
    $this->Cell(0, 15, $concessionaria['cnpj'], 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetY(18);
    $this->Cell(0, 15, $concessionaria['endereco'], 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetY(22);
    $this->Cell(0, 15, $concessionaria['cep'], 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetY(26);
    $this->Cell(0, 15, $concessionaria['telefone'], 0, false, 'R', 0, '', 0, false, 'M', 'M');
  }

  // Rodapé
  public function Footer() {
    
  }
}

?>