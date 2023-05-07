<?php

use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //SMTP::DEBUG_OFF;   //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gianluca16.reyes@gmail.com';      //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'gianluca16.reyes@gmail.com';           //SMTP username
    $mail->Password   = 'GianlucaEnvios';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('gianluca16.reyes@gmail.com', 'TIENDA PIA');
    $mail->addAddress('gianluca16.reyes@gmail.com', 'Gianluca Reyes');
    $mail ->addReplyTo('codificac@gmail.com');     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Detalle de su pedido';

    $cuerpo = "<h4>Gracias por su comrpa</h4>";
    $cuerpo = '<p>El ID de su compra es: <b>' .$idTransaccion . '</b></p>';

    $mail->Body    = utf8_decode($cuerpo);
    $mail->AltBody = 'Le enviamos los detalles de su compra';
    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

    $mail->send();

} catch (Exception $e) {
    echo "Error al enviar el correo electronico de la compra: {$mail->ErrorInfo}";
    return false;
    // exit;
}