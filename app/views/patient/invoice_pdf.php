<?php
require_once '../../../config/config.php';
require_once '../../../public/vendor/fpdf/fpdf.php';

if (!isset($_GET['id'])) {
    die("Invalid Request");
}

$bill_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT b.*, 
                               p.name AS patient_name, 
                               d.name AS doctor_name
                        FROM billing b
                        JOIN users p ON b.patient_id = p.id
                        JOIN users d ON b.doctor_id = d.id
                        WHERE b.id = ?");
$stmt->bind_param("i", $bill_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Bill not found!");
}

// ✅ Generate PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Patient: ' . $data['patient_name'], 0, 1);
$pdf->Cell(0, 10, 'Doctor: ' . $data['doctor_name'], 0, 1);
$pdf->Cell(0, 10, 'Date: ' . $data['created_at'], 0, 1);
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Consultation Fee: ' . $data['consultation_fee'] . ' TK', 0, 1);
$pdf->Cell(0, 10, 'Medicine Cost: ' . $data['medicine_cost'] . ' TK', 0, 1);
$pdf->Cell(0, 10, 'Bed Charge: ' . $data['bed_charge'] . ' TK', 0, 1);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Total: ' . $data['total_amount'] . ' TK', 0, 1);

$pdf->Output();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);

body{
    font-family: 'Rajdhani', sans-serif;

}
</style>
</head>
<body>
    
</body>
</html>