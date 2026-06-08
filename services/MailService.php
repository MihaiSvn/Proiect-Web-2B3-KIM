<?php

namespace services;

require_once __DIR__ . '/../PHPMailer/Exception.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class MailService
{
    public static function sendEmail($toEmail, $subject, $htmlBody, $mockMail = false){

        $envPath = __DIR__ . "/../.env";
        if(!file_exists($envPath)){
            return false;
        }
        $env = parse_ini_file($envPath);

        $mail = new PHPMailer(true);
        try{

            //setari server din Mailtrap
            $mail->isSMTP();

            $mail->Host       = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth   = true;
            $mail->Username   = $env['MAILTRAP_USER'];
            $mail->Password   = $env['MAILTRAP_PASS'];
            $mail->Port       = 2525;

            //expeditor si destinatar
            $mail->setFrom('noreply@kim.ro', 'KIM Fitness Admin');
            $mail->addAddress($toEmail);

            //continut mail
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;

            //varianta text simplu pt clienti de mail f vechi
            $mail->altBody = strip_tags($htmlBody);

            //trimit
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}