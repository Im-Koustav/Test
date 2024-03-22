<?php

require 'vendor/autoload.php';

class PDFGenerator {
    public static function generatePDF($full_name, $email_address, $number, $image, $marks_data): void {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, "Name: $full_name", 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Email Address: $email_address", 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Phone Number: $number", 0, 1, 'L');
        $pdf->SetFillColor(200, 220, 255);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Subject', 1, 0, 'C', 1);
        $pdf->Cell(60, 10, 'Marks', 1, 1, 'C', 1);
        $pdf->SetFont('Arial', '', 12);
        $rows = explode("\n", trim($marks_data));
        foreach ($rows as $row) {
            list($subject, $marks) = explode("|", $row);
            $pdf->Cell(60, 10, $subject, 1, 0, 'C');
            $pdf->Cell(60, 10, $marks, 1, 1, 'C');
        }
        $imageWidth = 50;
        $imageX = $pdf->GetPageWidth() - $imageWidth - 10;
        $pdf->Image($image, $imageX, 10, $imageWidth);
        $pdfFilePath = "docs/$email_address.pdf";
        // Saves the generated pdf file on the server in the docs folder.
        $pdf->Output("$pdfFilePath",'F');
        // Gives the option to download the pdf file.
        $pdf->Output();
    }
}
