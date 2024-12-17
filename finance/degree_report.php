<?php
require('../fpdf186/fpdf.php');
include('../db.php');

$pdf = new FPDF('P', 'mm', array(290, 400));
$pdf->AddPage();

// University Logo
$pdf->Image('../image/atulogo_small.jpeg', 4, 8, 30);

// University Title
$pdf->SetFont('Arial', 'B', 24);
$pdf->SetTextColor(138, 29, 57); // Your color code
$pdf->Cell(0, 10, 'Abaarso Tech University', 0, 1, 'C');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'I', 14);
$pdf->Cell(0, 10, 'Imagine. Inspire. Innovate', 0, 1, 'C');
$pdf->Ln(10);

// Report Title
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 10, 'Degree Students Payment Status Report', 0, 1, 'C');
$pdf->Ln(10);

// Paid Students Section
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Verified (Paid) Students:', 0, 1);
$pdf->Ln(5);

// Table Header Style
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(138, 29, 57); // Your color code for header background
$pdf->SetTextColor(255, 255, 255); // White text on header
$pdf->Cell(30, 15, 'Student ID', 1, 0, 'L', true);
$pdf->Cell(50, 15, 'Student Name', 1, 0, 'L', true);
$pdf->Cell(100, 15, 'Faculty', 1, 0, 'L', true);
$pdf->Cell(46, 15, 'Registration Date', 1, 0, 'L', true);
$pdf->Cell(35, 15, 'Payment Status', 1, 1, 'L', true);

// Fetch Paid Students
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0); // Reset text color to black
$sqlVerified = "SELECT * FROM tbldegreestudents WHERE payment_status = 'Paid'";
$stmt = $conn->query($sqlVerified);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(30, 15, $row['StudentID'], 1, 0, 'L');
    $pdf->Cell(50, 15, $row['FullName'], 1, 0, 'L');
    $pdf->Cell(100, 15, $row['faculty_enrolled'], 1, 0, 'L');
    $pdf->Cell(46, 15, $row['registration_date'], 1, 0, 'L');
    $pdf->Cell(35, 15, $row['payment_status'], 1, 1, 'L');
}

$pdf->Ln(10);

// Unpaid Students Section
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Not Verified (Unpaid) Students:', 0, 1);
$pdf->Ln(5);

// Table Header Style for Unpaid
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(138, 29, 57); // Your color code for header background
$pdf->SetTextColor(255, 255, 255); // White text
$pdf->Cell(30, 15, 'Student ID', 1, 0, 'L', true);
$pdf->Cell(50, 15, 'Student Name', 1, 0, 'L', true);
$pdf->Cell(100, 15, 'Faculty', 1, 0, 'L', true);
$pdf->Cell(46, 15, 'Registration Date', 1, 0, 'L', true);
$pdf->Cell(35, 15, 'Payment Status', 1, 1, 'L', true);

// Fetch Unpaid Students
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0); // Reset text color to black
$sqlNotVerified = "SELECT * FROM tbldegreestudents WHERE payment_status = 'Unpaid'";
$stmt = $conn->query($sqlNotVerified);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(30, 15, $row['StudentID'], 1, 0, 'L');
    $pdf->Cell(50, 15, $row['FullName'], 1, 0, 'L');
    $pdf->Cell(100, 15, $row['faculty_enrolled'], 1, 0, 'L');
    $pdf->Cell(46, 15, $row['registration_date'], 1, 0, 'L');
    $pdf->Cell(35, 15, $row['payment_status'], 1, 1, 'L');
}

$pdf->Output();
?>
