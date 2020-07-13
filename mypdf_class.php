<?php

//importando biblioteca
require_once('./TCPDF-master/examples/tcpdf_include.php');
require_once('./TCPDF-master/tcpdf_import.php');

//classe MYPDF extendendo TCPDF para customização do cabeçalho e rodapé
class MYPDF extends TCPDF {
  //Cabeçalho
  public function Header() {
    // Logo
    $image_file = K_PATH_IMAGES.'logo_example.jpg';
    $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    // Set font
    $this->SetFont('helvetica', '',10);
    
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

  // Rodapé
  public function Footer() {
    
  }
}

?>