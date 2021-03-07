<?php
    if(!isset($_SESSION))
        session_start();

    if(!isset($_SESSION['idUser']) || !isset($_SESSION['status'])){
        session_unset();
        header("location: ./connection");
    }
    include_once("./../config/config.php");

    if(!isset($_COOKIE['idOrder'])){
        header("location: ./home");
    }

    require("./php/bill.php");
    require("./../lib/fpdf/fpdf.php");

    $tab = billInformation($_COOKIE['idOrder']);

    $counter = 1;   //To count the package number
    $subTotalPrice = 0;
    $totalTax = 0;
    $totalPrice = 0;

    // Instanciation de la classe dérivée
    $pdf = new FPDF('P','mm','A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',9);

    //nom du fichier final
    $input = $tab[1]['creationDate']; $date = strtotime($input); $dateNumber = date('Ym', $date);
    $nom_file = "quickBaluchonInvoice[" . hash('sha256', $_COOKIE['idOrder']) . "]" . ".pdf";

    //Invoice number
    $nameOrder = 'F' . $dateNumber . '0' . $_COOKIE['idOrder'];
    $invoceText = "Invoice n" . utf8_decode("° ") . 'F' . $dateNumber . '0' . $_COOKIE['idOrder'];
    $pdf->SetXY( 20, 20 ); $pdf->SetFont( "Arial", "B", 20 ); $pdf->Cell( 55, 8, $invoceText, 0, 0, 'L');

    // date facture
    $input = $tab[1]['creationDate']; $date = strtotime($input); $date_fact = date('d/m/Y', $date);
    $pdf->SetFont('Arial','',11); $pdf->SetXY( 20, 30 );
    $pdf->Cell( 50, 8, "le " . $date_fact, 0, 0, 'L');

    //logo
    $pdf->Image('../img/logoQuickBaluchon.png',155,10,30);

    //srcdstBill
    $pdf->Cell(35, 20, '', 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(140, 5, 'From:', 0, 0, 'L');
    $pdf->Cell(60, 5, 'To:', 0, 1, 'L');

    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(140, 5, 'Quick Baluchon', 0, 0, 'L');
    $pdf->Cell(60, 5, $tab[2]['companyName'], 0, 1, 'L');

    $pdf->Cell(140, 5, 'contact@quickbaluchon.fr', 0, 0, 'L');
    $pdf->Cell(60, 5, $tab[2]['email'], 0, 1, 'L');

    $pdf->Cell(140, 5, '242 Rue du Faubourg Saint-Antoine, Paris', 0, 0, 'L');
    $pdf->Cell(40, 5, utf8_decode($tab[2]['address']), 0, 0, 'L');
    $pdf->SetXY( 150, 70 );
    $pdf->Cell(40, 5, $tab[2]['telNumber'], 0, 0, 'L');


    $pdf->SetXY( 20, 75 );

    $pdf->Cell(35,10,'',0,1,'R');


    // cadre titre des colonnes
    $pdf->Line(5, 105, 205, 105);
    // titre colonne
    $pdf->SetXY( 1, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 140, 8, "Label", 0, 0, 'C');
    $pdf->SetXY( 145, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 13, 8, "quantity", 0, 0, 'C');
    $pdf->SetXY( 156, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 22, 8, "Price HT", 0, 0, 'C');
    $pdf->SetXY( 177, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 10, 8, "TVA", 0, 0, 'C');
    $pdf->SetXY( 185, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 22, 8, "TOTAL TTC", 0, 0, 'C');


    // les articles
    $pdf->SetFont('Arial','',8);
    $y = 107;

    foreach($tab[0] as $package){
        // libelle
        $pdf->SetXY( 7, $y); $pdf->Cell( 140, 5, "Package number " . $counter, 0, 0, 'L');

        // qte
        $pdf->SetXY( 145, $y); $pdf->Cell( 13, 5, strrev(wordwrap(strrev("1"), 3, ' ', true)), 0, 0, 'R');

        // PU
        $priceHT = number_format(calculatePrice($package['weight'], $tab[1]['deliveryType']), 2, ',', ' ');
        $pdf->SetXY( 158, $y); $pdf->Cell( 18, 5, $priceHT, 0, 0, 'R');

        // Taux
        $pdf->SetXY( 177, $y); $pdf->Cell( 10, 5, "20 %", 0, 0, 'R');

        // total
        $priceTTC = number_format(calculatePrice($package['weight'], $tab[1]['deliveryType'])*1.2, 2, ',', ' ');
        $pdf->SetXY( 187, $y ); $pdf->Cell( 18, 5, $priceTTC, 0, 0, 'R');

        $subTotalPrice += calculatePrice($package['weight'], $tab[1]['deliveryType']);
        $totalTax += calculatePrice($package['weight'], $tab[1]['deliveryType'])*0.2;
        $totalPrice += calculatePrice($package['weight'], $tab[1]['deliveryType'])*1.2;
        $counter++;
        $y += 6;
    }

    // ***********************
    // le cadre des articles
    // ***********************
    // cadre dynamique
    $pdf->SetLineWidth(0.1); $pdf->Rect(5, 95, 200, $y-95, "D");
    // les traits verticaux colonnes
    $pdf->Line(145, 95, 145, $y); $pdf->Line(158, 95, 158, $y); $pdf->Line(176, 95, 176, $y); $pdf->Line(187, 95, 187, $y);


    //priceBill
    $y += 5;
    $pdf->SetXY( 145, $y);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(25, 5, "Subtotal: " . chr(128) . " ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(60, 5, $subTotalPrice, 0, 1, 'L');

    $y += 5; $pdf->SetXY( 145, $y);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(25, 5, "Tax rate: %", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(60, 5, "20.0", 0, 1, 'L');

    $y += 5; $pdf->SetXY( 145, $y);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(25, 5, "Total tax: " . chr(128) . " ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(60, 5, $totalTax, 0, 1, 'L');

    $y += 5; $pdf->SetXY( 145, $y);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(25, 5, "Total (TTC): " . chr(128) . " ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(60, 5, $totalPrice, 0, 1, 'L');

    //termsBill
    $y += 15; $pdf->SetXY( 20, $y);
    $pdf->SetFont('Arial', '', 14);
    $pdf->Cell(25, 5, "Terms:", 0, 1, 'L');

    $y += 7; $pdf->SetXY( 20, $y);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(30, 5, "Type of payment: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(60, 5, ($tab[1]['paymentType'] == 0) ? 'Credit card' : 'Bank transfer', 0, 1, 'L');

    $y += 7; $pdf->SetXY( 20, $y);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(30, 5, "Payment due date: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(60, 5, "45 days end of month", 0, 1, 'L');

    // $y += 15; $pdf->SetXY( 20, $y);
    // $pdf->SetFont('Arial', '', 14);
    // $pdf->Cell(25, 5, "./../users/" . $tab[2]['idUser'] . "/qrcode/" . $nameOrder . ".bmp", 0, 1, 'L');

    //QRCode
    // $pdf->Image("./../users/" . $tab[2]['idUser'] . "/qrcode/" . $nameOrder . ".png", 25, 180, 40); //goodOne
    // $pdf->Image("./../users/2/qrcode/F20210202.bmp", 25, 180, 40);

    $pdf->Output("I", $nom_file, true);

?>
