<?php
require('../../../public/vendor/fpdf/fpdf.php');
require('../../../config/config.php');

// ✅ Fetch All Patients
$result = $conn->query("SELECT * FROM users WHERE role='patient' ORDER BY created_at DESC");

// ✅ Generate PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'All Patients Report',0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,10,'ID',1);
$pdf->Cell(40,10,'Name',1);
$pdf->Cell(40,10,'Phone',1);
$pdf->Cell(30,10,'Gender',1);
$pdf->Cell(30,10,'Age',1);
$pdf->Cell(40,10,'Registered',1);
$pdf->Ln();

$pdf->SetFont('Arial','',9);
while($row = $result->fetch_assoc()){
    $pdf->Cell(10,10,$row['id'],1);
    $pdf->Cell(40,10,$row['name'],1);
    $pdf->Cell(40,10,$row['phone'],1);
    $pdf->Cell(30,10,ucfirst($row['gender']),1);
    $pdf->Cell(30,10,($row['age'] ?? 'N/A'),1);
    $pdf->Cell(40,10,$row['created_at'],1);
    $pdf->Ln();
}

$pdf->Output();
?>
