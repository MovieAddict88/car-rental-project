<?php
require_once('includes/header.php');
require_once('../includes/fpdf/fpdf.php');

class PaymentsPDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'Payments Report',0,1,'C');
        $this->Ln(2);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }
    function TableHeader() {
        $this->SetFont('Arial','B',10);
        $this->Cell(18,8,'Pay ID',1);
        $this->Cell(22,8,'Booking',1);
        $this->Cell(45,8,'User',1);
        $this->Cell(45,8,'Car',1);
        $this->Cell(25,8,'Amount',1,0,'R');
        $this->Cell(25,8,'Status',1);
        $this->Cell(35,8,'Date',1);
        $this->Ln();
    }
}

$pdf = new PaymentsPDF();
$pdf->AddPage('L');
$pdf->TableHeader();
$pdf->SetFont('Arial','',9);

$stmt = $pdo->query(
    "SELECT p.id, p.booking_id, u.name AS user_name,
            CONCAT(c.brand,' ',c.model) AS car,
            p.amount, p.status, p.payment_date
     FROM payments p
     JOIN bookings b ON p.booking_id = b.id
     JOIN users u ON b.user_id = u.id
     JOIN cars c ON b.car_id = c.id
     ORDER BY p.payment_date DESC"
);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(18,8,$row['id'],1);
    $pdf->Cell(22,8,'#'.$row['booking_id'],1);
    $pdf->Cell(45,8,$row['user_name'],1);
    $pdf->Cell(45,8,$row['car'],1);
    $pdf->Cell(25,8,number_format((float)$row['amount'],2),1,0,'R');
    $pdf->Cell(25,8,$row['status'],1);
    $pdf->Cell(35,8,$row['payment_date'],1);
    $pdf->Ln();
}

$pdf->Output('D', 'payments_report.pdf');
exit;
