<?php
    require('./fpdf.php');
    class myPDF extends FPDF
    {
    }
$pdf = new myPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$h=15; // default height of each MultiCell
$w=100;// Width of each MultiCell
$y=$pdf->GetY(); // Getting Y or vertical position
$x=$pdf->GetX(); // Getting X or horizontal position
$pdf->MultiCell($w,$h,'X='.round($x).',Y='.round($y),LRTB,L,false);
$y=$pdf->GetY();
$x=$pdf->GetX();
$pdf->MultiCell($w,$h,'X='.round($x).',Y='.round($y),LRTB,L,false);
$y=$pdf->GetY();
$x=$pdf->GetX();
$pdf->MultiCell($w,$h,'X='.round($x).',Y='.round($y),LRTB,L,false);
$y=$pdf->GetY();
$x=$pdf->GetX();
$pdf->MultiCell($w,$h,'X='.round($x).',Y='.round($y).' ( Added more text inside MultiCell for text to Wrap around )',LRTB,L,false);
$y=$pdf->GetY();
$x=$pdf->GetX();
$pdf->MultiCell($w,$h,'X='.round($x).',Y='.round($y),LRTB,L,false);

$pdf->Output('my_file.pdf','I'); // send to browser and display
?>

