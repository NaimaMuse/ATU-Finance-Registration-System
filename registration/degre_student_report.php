<?php
require('../fpdf186/fpdf.php');
include('../db.php');

// Create a PDF instance
$pdf = new FPDF('P', 'mm', 'A4'); // A4 Page size
$pdf->AddPage();

// University Logo and Header
$pdf->Image('../image/atulogo_small.jpeg', 10, 10, 30);
$pdf->SetFont('Arial', 'B', 24);
$pdf->Cell(0, 10, 'Abaarso Tech University', 0, 1, 'C');
$pdf->Ln(1);
$pdf->SetFont('Arial', 'I', 14);
$pdf->Cell(0, 10, 'Imagine. Inspire. Innovate.', 0, 1, 'C');
$pdf->Ln(10);

// Title for the Report
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetTextColor(138, 29, 57);  // Set color to #8a1d39
$pdf->Cell(0, 10, 'Degree Students Registration Report', 0, 1, 'C');
$pdf->Ln(10);

// Date of Report
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);  // Reset color to black for date
$pdf->Cell(0, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'C');
$pdf->Ln(10);

// Faculty-wise Student Summary
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Faculty-wise Registration Summary:', 0, 1);
$pdf->SetFont('Arial', '', 12);

// Table Header Styling
$pdf->SetFillColor(138, 29, 57); // Set fill color to #8a1d39 for header
$pdf->SetTextColor(255, 255, 255); // Set text color to white for header
$pdf->Cell(110, 10, 'Faculty', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Number of Students', 1, 1, 'C', true);

// Reset text color for table content
$pdf->SetTextColor(0, 0, 0);

// Fetch Faculty Data
$sql = "SELECT faculty_enrolled, COUNT(*) AS total_students 
        FROM tbldegreestudents 
        GROUP BY faculty_enrolled";
$stmt = $conn->query($sql);

// Initialize Total Counter
$totalStudents = 0;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $faculty = $row['faculty_enrolled'];
    $students = $row['total_students'];
    $totalStudents += $students;

    // Add Faculty Data to Table
    $pdf->Cell(110, 10, $faculty, 1, 0, 'C');
    $pdf->Cell(50, 10, $students, 1, 1, 'C');
}

// Add Total Number of Students
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(255, 255, 255);  // Set white background for the total row
$pdf->Cell(110, 10, 'Total Students', 1, 0, 'C', true);
$pdf->Cell(50, 10, $totalStudents, 1, 1, 'C');

// Output the PDF
$pdf->Output();
?>
