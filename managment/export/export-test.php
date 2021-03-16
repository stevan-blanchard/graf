<?php
try{
    require('./fpdf.php');
    require "../../sql/config.php";
    class myPDF extends FPDF
    {
        //en-tête du tableau.
        function headerTable(){
            $this->setFont('Times', 'B',12);

            $this->SetFillColor(250,179,86);
            $this->Cell(70,15,'Description',1,0,'C',1);
            
            $this->SetFillColor(51,153,255);
            $this->Cell(40,15,'Auteur',1,0,'C',1);
            
            $this->SetFillColor(243,54,98);
            $this->Cell(20,15,utf8_decode('Durée'),1,0,'C',1);
            
            $this->SetFillColor(117,117,117);
            $this->Cell(20,15,utf8_decode('Priorité'),1,0,'C',1);
            
            $this->SetFillColor(108,226,116);
            $this->Cell(30,15,'DeadLine',1,0,'C',1);

            $this->SetFillColor(250,179,86);
            $this->Cell(70,15,'Observation',1,0,'C',1);
            
            $this->SetFillColor(255,145,0);
            $this->Cell(10,15,'1/3',1,0,'C',1);
            $this->Cell(10,15,'2/3',1,0,'C',1);
            $this->Cell(10,15,'3/3',1,0,'C',1);
            
            
            
            $this->Ln();
        }
        //$auteur : le nom de l'auteur en paramètre car etrangement il est imposible d'éxécuter des requêtes sql dans les fonctions
        //$display liste des élement a afficher doit être un array().
        function viewTable($auteur, $display){
                
                $x=$this->GetX();
                $this->SetFillColor(250,179,86);
                $this->SetFont('Times','B',8);
                $y = $this->GetY();
                $x = $this->GetX();
                $width = 25;
                $width_multi_line_desc = 5;
                $width_multi_line_obs = 5;

                $description = utf8_decode($display['description']);
                $observation = utf8_decode($display['observation']);
                for($i=(strlen($description))/55; $i<5; $i++){
                    $end = strlen($description);
                    $description[$end]="\n";
                }

                for($i=(strlen($observation))/55; $i<5; $i++){
                    $end = strlen($observation);
                    $observation[$end]="\n";
                }


                $this->MultiCell(70,$width_multi_line_desc,$description,1,'LTRB',1);
                $this->SetXY($x + 70, $y);
                $this->SetFont('Times','B',12);

                $x=$this->GetX();
                $this->SetFillColor(51,153,255);
                $this->myCell(40,$width,$x,$auteur);

                $x=$this->GetX();
                $this->SetFillColor(243,54,98);
                $this->myCell(20,$width,$x,$display['duration']);

                $x=$this->GetX();
                $this->SetFillColor(117,117,117);
                $this->myCell(20,$width,$x,$display['priority']);

                $x=$this->GetX();
                $this->SetFillColor(108,226,116);
                $x=$this->GetX();
                $this->myCell(30,$width,$x,$display['deadline']);

                $this->SetFont('Times','B',8);
                $this->SetFillColor(250,179,86);
                $y = $this->GetY();
                $x = $this->GetX();
                $this->MultiCell(70,$width_multi_line_obs,$observation,1,1,1);
                $this->SetXY($x + 70, $y);

                $x=$this->GetX();
                $this->SetFillColor(255,145,0);
                $this->myCell(10,$width,$x,$display['un_tiers']);
                $x=$this->GetX();
                $this->myCell(10,$width,$x,$display['deux_tiers']);
                $x=$this->GetX();
                $this->myCell(10,$width,$x,$display['trois_tiers']);

                
                $this->Ln();
        }
        function myCell($w,$h,$x,$t){
            $height= $h/3;
            $first= $height+2;
            $second= $height+$height+$height+3;
            $len= strlen($t);
            $split = 57;
            if($len>$split){
                //recherche d'espace
                $i = $split;
                while($t[$i]!=" "){
                    $i--;
                }
                $txt=str_split($t,$i);
                //$this->SetTextColor(0,0,0);
                $this->SetX($x);
                $this->Cell($w,$first,utf8_decode($txt[0]),'','','');
                $this->SetX($x);
                $this->Cell($w,$second,utf8_decode($txt[1]),'','','');
                $this->SetX($x);
                $this->Cell($w,$h,'','LTRB',0,'L');
            }
            else{
                $this->SetX($x);
                $this->Cell($w,$h,utf8_decode($t),'LTRB',0,'C',1);
            }
        }

    }
    $projetID = $_GET["id"];
    $projet = $dbh->prepare("SELECT * FROM includeInProjects WHERE id='". $projetID."'; ");
    $projet->execute();
    $projetName = $projet->fetch()[1];

    //Recupération des RAF pour un projet 
    $element = $dbh->prepare("SELECT * FROM RAF WHERE includeInProject_id='". $projetID."' ORDER BY deadline ; ");
    $element->execute();

    $pdf = new myPDF();
    $today = date("Y-m-d H:i:s");
    $pdf->AddPage('L', 'A4', 0);
    if(file_exists('./img/logo.png')){
        $image='./img/logo.png';
        $pdf->Image($image,180,6,60);
    }
    else if(file_exists('./img/logo.jpg')){
        $image='./img/logo.jpg';
        $pdf->Image($image,180,6,60);
    }
    else if(file_exists('./img/logo.jpeg')){
        $image='./img/logo.jpeg';
        $pdf->Image($image,180,6,60);
    }
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,"Projet : ".$projetName);
    $pdf->Ln();
    $pdf->Cell(40,10,"Date d'exportation : ".$today);
    $pdf->Ln();
    $pdf->Cell(40,10,utf8_decode("Liste des restes à Faire :"));
    $pdf->Ln();
    $pdf->headerTable();
    //on affiche les elements RAF de la basse de donnée corespondant au projet.
    while($display = $element->fetch(PDO::FETCH_ASSOC)){
        $auteur = $dbh->prepare("SELECT * FROM author WHERE id ='" . $display['author_id'] . "'");
        $auteur->execute();
        $nomAuteur = $auteur->fetch()[1];
        $pdf->viewTable($nomAuteur, $display);
    }
    $pdf->Output("I","Export ".$projetName." ".$today,1);
}
catch(Exception $a){
    echo $a;
}
?>