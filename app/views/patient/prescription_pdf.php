<?php
require_once '../../../config/config.php';
require_once '../../../public/vendor/fpdf/fpdf.php';

if (!isset($_GET['id'])) {
    die("Invalid Request");
}

$prescription_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT p.*, u.name AS doctor_name, u.specialization, 
                               (SELECT name FROM users WHERE id = p.patient_id) AS patient_name
                        FROM prescriptions p
                        JOIN users u ON p.doctor_id = u.id
                        WHERE p.id = ?");
$stmt->bind_param("i", $prescription_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Prescription not found!");
}

// ✅ Generate PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Prescription', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Doctor: ' . $data['doctor_name'] . ' (' . $data['specialization'] . ')', 0, 1);
$pdf->Cell(0, 10, 'Patient: ' . $data['patient_name'], 0, 1);
$pdf->Cell(0, 10, 'Date: ' . $data['created_at'], 0, 1);
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, "Prescription:\n" . $data['prescription']);
$pdf->Output();
?>
