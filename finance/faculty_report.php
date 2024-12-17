<?php
require('../fpdf186/fpdf.php');
include('../db.php');

// Create PDF instance
$pdf = new FPDF('P', 'mm', array(240, 297));
$pdf->AddPage();

// Add University Logo and Title
$pdf->Image('../image/atulogo_small.jpeg', 4, 8, 30);
$pdf->SetFont('Arial', 'B', 24);
$pdf->SetTextColor(138, 29, 57); // Title Color #8a1d39
$pdf->Cell(0, 10, 'Abaarso Tech University', 0, 1, 'C');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'I', 14);
$pdf->SetTextColor(0, 0, 0); // Reset text color to black
$pdf->Cell(0, 10, 'Imagine. Inspire. Innovate', 0, 1, 'C');
$pdf->Ln(5);

// Payment Status Report Title
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetTextColor(138, 29, 57); // Title Color #8a1d39
$pdf->Cell(0, 10, 'Students Payment Status Report', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0); // Reset to black
$pdf->Cell(0, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'C');
$pdf->Ln(10);

// Function to Display Faculty-Wise Payment Data
function displayPaymentData($pdf, $conn, $tableName, $programType) {
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetTextColor(138, 29, 57); // Section Title Color
    $pdf->Cell(0, 10, $programType . ' Faculty-wise Payment Status:', 0, 1);
    $pdf->Ln(5);

    // Table Headers
    $pdf->SetFillColor(138, 29, 57); // Header Background Color #8a1d39
    $pdf->SetTextColor(255, 255, 255); // Header Text Color (White)
    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(80, 10, 'Faculty', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Paid Students', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Unpaid Students', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Total Students', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0); // Reset text color to black

    // Query for Faculty-wise Data
    $sqlFaculty = "SELECT faculty_enrolled, 
                          SUM(CASE WHEN payment_status = 'Paid' THEN 1 ELSE 0 END) as paid,
                          SUM(CASE WHEN payment_status = 'Unpaid' THEN 1 ELSE 0 END) as unpaid,
                          COUNT(*) as total
                   FROM $tableName
                   GROUP BY faculty_enrolled";
    $stmt = $conn->query($sqlFaculty);

    $totalPaid = 0;
    $totalUnpaid = 0;
    $totalStudents = 0;

    // Loop through faculties and add rows to the table
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $faculty = $row['faculty_enrolled'];
        $paid = $row['paid'];
        $unpaid = $row['unpaid'];
        $total = $row['total'];

        $totalPaid += $paid;
        $totalUnpaid += $unpaid;
        $totalStudents += $total;

        $pdf->Cell(80, 10, $faculty, 1, 0, 'C');
        $pdf->Cell(40, 10, $paid, 1, 0, 'C');
        $pdf->Cell(40, 10, $unpaid, 1, 0, 'C');
        $pdf->Cell(40, 10, $total, 1, 1, 'C');
    }

    // Add Overall Totals Row
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(200, 200, 200); // Light gray background for totals
    $pdf->Cell(80, 10, 'Total', 1, 0, 'C', true);
    $pdf->Cell(40, 10, $totalPaid, 1, 0, 'C', true);
    $pdf->Cell(40, 10, $totalUnpaid, 1, 0, 'C', true);
    $pdf->Cell(40, 10, $totalStudents, 1, 1, 'C', true);
    $pdf->Ln(40);
}

// Display Payment Data for Degree Students
displayPaymentData($pdf, $conn, 'tbldegreestudents', 'Degree');

// Display Payment Data for Master Students
displayPaymentData($pdf, $conn, 'tblmasterstudents', 'Master');

// Save and Output PDF
$pdf->Output();
?>
