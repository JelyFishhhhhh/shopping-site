<?php

require_once('./include/TCPDF/tcpdf_import.php');

/*---------------- Sent Mail Start -----------------*/

/*---------------- Sent Mail End -------------------*/

/*---------------- Print PDF Start -----------------*/
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetFont('cid0jp','', 18); 
$pdf->AddPage();

$PRICE_LIST= array
(
	0=> 200,
	1=> 200,
	2=> 200,
	3=> 200,
	4=> 7000,
	5=> 6756,
	6=> 6756,
	7=> 6852,
	8=> 500,
	9=> 500,
	10=> 500,
	11=> 500,
	12=> 500,
	13=> 500,
	14=> 500,
	15=> 500
);
$QUANTITY_LIST= array
(
	0=> $_POST["quantity-1"], 
	1=> $_POST["quantity-2"],
	2=> $_POST["quantity-3"],
	3=> $_POST["quantity-4"],
	4=> $_POST["quantity-5"],
	5=> $_POST["quantity-6"],
	6=> $_POST["quantity-7"],
	7=> $_POST["quantity-8"],
	8=> $_POST["quantity-9"],
);

$TOTAL=0;
for($x=0; $x < 8; $x++)
{
	$TOTAL += intval($QUANTITY_LIST[$x])* $PRICE_LIST[$x];
}

$NAME = $_POST["NAME"];
$EMAIL = $_POST["EMAIL"];
$PHONE = $_POST["phoneNumber"];
$html = <<<EOF
<h1>Figure shop</h1>

EOF;
/*---------------- Print PDF End -------------------*/

$pdf->writeHTML($html);
$pdf->lastPage();
$pdf->Output('order.pdf', 'I');
