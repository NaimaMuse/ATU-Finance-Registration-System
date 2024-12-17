<?php
require('../fpdf186/fpdf.php');
include('../db.php');

$pdf = new FPDF('P', 'mm', array(290, 400)); 
$pdf->AddPage();

// University Logo and Header
$pdf->Image('../image/atulogo_small.jpeg', 4, 8, 30);
$pdf->SetFont('Arial', 'B', 24);
$pdf->SetTextColor(138, 29, 57); // Set title text color (#8A1D39)
$pdf->Cell(0, 10, 'Abaarso Tech University', 0, 1, 'C'); 
$pdf->Ln(1);  

$pdf->SetFont('Arial', 'I', 14);
$pdf->SetTextColor(0, 0, 0); // Reset text color
$pdf->Cell(0, 10, 'Imagine. Inspire. Innovate', 0, 1, 'C');  
$pdf->Ln(10);  

$pdf->SetFont('Arial', 'B', 20);
$pdf->SetTextColor(138, 29, 57); // Report Title Color
$pdf->Cell(0, 10, 'Master Students Payment Status Report', 0, 1, 'C');
$pdf->Ln(10);

// Table for Verified Students
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 10, 'Verified (Paid) Students:', 0, 1);

// Set Header Background Color
$pdf->SetFillColor(138, 29, 57); // Header Background (#8A1D39)
$pdf->SetTextColor(255, 255, 255); // Header Text Color (White)
$pdf->SetFont('Arial', 'B', 12);

// Header Cells
$pdf->Cell(30, 15, 'Student ID', 1, 0, 'L', true);
$pdf->Cell(50, 15, 'Student Name', 1, 0, 'L', true);
$pdf->Cell(100, 15, 'Faculty', 1, 0, 'L', true);
$pdf->Cell(46, 15, 'Registration Date', 1, 0, 'L', true);
$pdf->Cell(35, 15, 'Payment Status', 1, 1, 'L', true);

$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0); // Reset text color
$sqlVerified = "SELECT * FROM tblmasterstudents WHERE payment_status = 'Verified'";
$stmt = $conn->query($sqlVerified);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(30, 15, $row['StudentID'], 1, 0, 'L');
    $pdf->Cell(50, 15, $row['FullName'], 1, 0, 'L');
    $pdf->Cell(100, 15, $row['faculty_enrolled'], 1, 0, 'L');
    $pdf->Cell(46, 15, $row['registration_date'], 1, 0, 'L');
    $pdf->Cell(35, 15, $row['payment_status'], 1, 1, 'L');
}

$pdf->Ln(10);

// Table for Not Verified Students
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 10, 'Not Verified (Unpaid) Students:', 0, 1);

$pdf->SetFillColor(138, 29, 57); // Header Background (#8A1D39)
$pdf->SetTextColor(255, 255, 255); // Header Text Color (White)
$pdf->SetFont('Arial', 'B', 12);

// Header Cells
$pdf->Cell(30, 15, 'Student ID', 2, 0, 'L', true);
$pdf->Cell(50, 15, 'Student Name', 2, 0, 'L', true);
$pdf->Cell(100, 15, 'Faculty', 2, 0, 'L', true);
$pdf->Cell(46, 15, 'Registration Date', 2, 0, 'L', true);
$pdf->Cell(35, 15, 'Payment Status', 2, 1, 'L', true);

$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0); // Reset text color
$sqlNotVerified = "SELECT * FROM tblmasterstudents WHERE payment_status = 'UnVerified'";
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
