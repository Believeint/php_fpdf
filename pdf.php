<?php
require 'dbconfig.php';

$id = $_REQUEST['gerar_pdf'];

$stmt = $DB_con->prepare('SELECT nome_completo, endereco, contato, imagemUsuario, educacao, idiomas, habilidades_competencias, experiencias FROM tbl_usuario WHERE idUsuario=:idU');
$stmt->execute(['idU' => $id]);
$usuario = $stmt->fetch();

$nome = $usuario['nome_completo'];
$endereco = $usuario['endereco'];
$contato = $usuario['contato'];
$imagem = $usuario['imagemUsuario'];
$educacao = $usuario['educacao']; 
$idiomas = $usuario['idiomas'];
$hab_comp = $usuario['habilidades_competencias'];
$experiencias = $usuario['experiencias'];

require 'fpdf.php';

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

$pdf->SetXY(80, 20);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0,0, 60);
$pdf->Cell(0, 10, "Curriculum Vitae", 1, 1, "Right text", 0, 0, 'C');
$pdf->GetX(100);

$pdf->SetXY(80, 35);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 10, "$nome", 0, 0, 'L');

$pdf->SetXY(80, 34);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 30, "$endereco");

$pdf->SetXY(80, 42);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 30, "$contato");

$pdf->Image("imagem_usuarios/$imagem", 10, 20, 50, 50);


$pdf->SetXY(10, 90);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0,0, 60);
$pdf->Cell(0, 10, " Educação", 1, 1, "center text", 0, 0, 'C');
$pdf->GetX(100);



$pdf->SetXY(20, 100);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 30, "$educacao");




$pdf->SetXY(10, 130);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0,0, 60);
$pdf->Cell(0, 10, " Idiomas", 1, 1, "center text", 0, 0, 'C');
$pdf->GetX(100);



$pdf->SetXY(20, 140);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(80, 80, 80);
$pdf->Multicell(0, 10, "$idiomas");


$pdf->SetXY(10, 190);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0,0, 60);
$pdf->Cell(0, 10, " Habilidades e Competências", 1, 1, "center text", 0, 0, 'C');
$pdf->GetX(100);


$pdf->SetXY(20, 200);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(80, 80, 80);
$pdf->Multicell(0, 10, "$hab_comp");
//$pdf->WriteHTML("<h1>OI</h1>");

$pdf->SetXY(10, 240);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0,0, 60);
$pdf->Cell(0, 10, " Experiências", 1, 1, "center text", 0, 0, 'C');
$pdf->GetX(100);

$pdf->SetXY(20, 250);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(80, 80, 80);
$pdf->Multicell(0, 10, "$experiencias");

$pdf->Output();
