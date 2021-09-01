<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . "/Config.php";

$cfg = new Helpers\Config();
$cfg->load("../config/email.php");


function sendMail($email, $password, $recipient, $url, $lang) {
    global $cfg;

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    
    $mail->Host = "smtp.gmail.com";
    
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Port = "587";
    
    // Email a heslo z ktorého budeme odosielať 
    $mail->Username = $email;
    $mail->Password = $password;
    
    // Subjekt, sender mail a telo 
    $mail->setFrom($email);
    $mail->Subject = $cfg->get("header");

    if ($lang == "Slovak") $mail->Body = $cfg->get("sk") . "\n\n" . "$url\n\n" . $cfg->get("en") . "\n\n" . $url;
    else $mail->Body = $cfg->get("cz") . "\n\n" . "$url\n\n" . $cfg->get("en") . "\n\n" . $url;


    // Osoba, ktorá dostane mail
    $mail->addAddress($recipient);
    
    // Konečne môžme odoslať mailer
    $mail->Send();


    
    // Zatvoríme SMTP spojenie
    $mail->smtpClose();


}

