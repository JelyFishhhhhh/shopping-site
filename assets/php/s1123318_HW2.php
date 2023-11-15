<?php

session_start();
/*----------Mailer Config-----------------*/

date_default_timezone_set('Asia/Taipei');

$PRICE_LIST= array( 	0=> 59980, 
						1=> 48500,
						2=> 49000,
						3=> 162000,
						4=> 25000,
						5=> 46990,
						6=> 50000,
						7=> 7000,
						8=> 74000,
						9=> 598000
);

$PRODUCT_LIST= array(	0=> "A7CII", 
						1=> "A7C",
						2=> "A7III",
						3=> "Z9",
						4=> "R10",
						5=> "X-S20",
						6=> "E-M1X",
						7=> "MjuII",
						8=> "DC-S5IIX",
						9=> "S3"

);

$QUANTITY_LIST = array(	0=> $_POST["quantity-0"], 
						1=> $_POST["quantity-1"],
						2=> $_POST["quantity-2"],
						3=> $_POST["quantity-3"],
						4=> $_POST["quantity-4"],
						5=> $_POST["quantity-5"],
						6=> $_POST["quantity-6"],
						7=> $_POST["quantity-7"],
						8=> $_POST["quantity-8"],
						9=> $_POST["quantity-9"]
);

$TOTAL= 0;
for ($x = 0; $x < 10; $x++) {
	
	if($QUANTITY_LIST[$x]){
		
		
		$TOTAL+= $QUANTITY_LIST[$x]* $PRICE_LIST[$x];
	}
}

$NAME = $_POST["NAME"];
$phoneNumber = $_POST["phoneNumber"];
$EMAIL = $_POST["EMAIL"];
$paymentMethod = $_POST["paymentMethod"];
$ADDRESS = $_POST["ADDRESS"];

$ORDER_ID= substr($NAME, 0, 2).'_'.date("m-d_H-i-s", time());

// TCPDF
require_once('./include/TCPDF/tcpdf_import.php');

// QRCODE-Generator
require_once('./include/phpqrcode/phpqrcode.php');

/*-------------- Generate qrcode Start --------------*/
include 'phpqrcode/qrlib.php';
$text = "http://140.138.155.243/~s1123318/HW2/assets/php/orders/".$ORDER_ID.".pdf";
QRcode::png($text, './orders/'.$ORDER_ID.".png");

/*-------------- Generate qrcode End ----------------*/

/*---------------- Sent Mail Start -----------------*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require './include/phpmailer/src/Exception.php';
require './include/phpmailer/src/PHPMailer.php';
require './include/phpmailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

//Server settings
$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
$mail->isSMTP();                                            //Send using SMTP
$mail->Host       = 'Smtp.gmail.com';                     //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = 'tony0940419@gmail.com';                     //SMTP username
$mail->Password   = 'long wryq xrqt plaa';                               //SMTP password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`;
$mail->SMTPDebug = SMTP::DEBUG_OFF;
//Recipients
$mail->setFrom('tony0940419@gmail.com', 'Mailer');
$mail->addAddress($EMAIL, 'User');


//Attachments
$mail->addAttachment('./orders/'.$ORDER_ID.'.png', 'QRCode.png');    //Optional name

//Content
$mail->isHTML(true);                                  //Set email format to HTML
$mail->Subject = 'YOUR ORDER ON JELYFISHHHHHH\'s SHOP HAS BEEN ESTABLISHED';
$mail->Body = '<p>This is the </p><a href="' . $_SERVER['HTTP_HOST'] . '/~s1123318/HW2/assets/php/orders/' . $ORDER_ID . '.pdf">LINK</a><p> of your order list</p>';
$mail->AltBody = 'Thank you for your order!';

$mail->send();
/*---------------- Sent Mail End -------------------*/

/*---------------- Print PDF Start -----------------*/
ob_start();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetFont('cid0jp','', 18); 
$pdf->AddPage();
$qrcodeImagePath = './orders/' . $ORDER_ID . '.png';
$html = <<<EOF
<style>
  td {
    font-size: 15px;
    font-family: "Courier New", monospace;
	  text-align: center;
	  border-radius: 3.5px;
  }
  @import url('https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap');
	h1 {
		font-family: 'Permanent Marker', cursive;
	}
</style>

<h1>JelyFishhhhhh's Shop</h1>
<table border="1" width="535px" height="100px">
	
	<tr>
		<td colspan="4" style="background-color: #000000; color: #FFFFFF;">Personal Info</td>
	</tr>
	<tr>
        <td colspan="1">Name</td>
        <td colspan="3">$NAME</td>
	</tr>
	<tr>
        <td colspan="1">Phone</td>
        <td colspan="3">$phoneNumber</td>
    </tr>

    <tr>
        <td colspan="1">Email</td>
        <td colspan="3">$EMAIL</td>
    </tr>
    <tr>
        <td colspan="1">Payment</td>
        <td colspan="3">$paymentMethod</td>
    </tr>
    <tr>
        <td colspan="1">Address</td>
        <td colspan="3">$ADDRESS</td>
    </tr>
</table>
	<br><br>
<table border="1" width="535px" height="100px">
    <tr>
        <td colspan="4" style="background-color: #000000; color: #FFFFFF;">OrderList</td>
    </tr>
	<tr>
		<td colspan="1" style="background-color: #b8bcc2">Model</td>
		<td colspan="1" style="background-color: #b8bcc2">Quantity</td>
		<td colspan="2" style="background-color: #b8bcc2">Subtotal</td>
	</tr>
	
EOF;

for ($i = 0; $i < 10; $i++) {
    if ($QUANTITY_LIST[$i]) {
		$subTotal = $QUANTITY_LIST[$i]*$PRICE_LIST[$i];
        $html .= "<tr><td colspan=\"1\">{$PRODUCT_LIST[$i]}</td><td colspan=\"1\">{$QUANTITY_LIST[$i]}</td><td colspan=\"2\">{$subTotal}</td></tr>";
    }
}

$html .= <<<EOF
</table>
<br><br>
<table  border="1" width="535px" height="100px">
	<tr>
		<td colspan="4" style="background-color: #000000; color: #FFFFFF;">Summarize</td>
	</tr>
	<tr>
		<td colspan="1">Total</td>
		<td colspan="3">{$TOTAL}</td>
	</tr>
</table>
EOF;
$pdf->Image($qrcodeImagePath, $x = 10, $y = $pdf->getPageHeight() - 90, $w = 50, $h = 50, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false);

/*---------------- Print PDF End -------------------*/

ob_end_clean();
$pdf->writeHTML($html);
$pdf->lastPage();
$pdf->Output(__DIR__.'/orders/'.$ORDER_ID.'.pdf', 'F');

header('Location: http://' . $_SERVER['HTTP_HOST'] . '/~s1123318/HW2/index.html#order');
exit;
?>
