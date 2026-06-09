<?php

namespace services;

require_once __DIR__ . '/../PHPMailer/Exception.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class MailService
{
    public static function sendEmail($toEmail, $subject, $title, $text, $buttonText = null, $buttonUrl = null){

        $envPath = __DIR__ . "/../.env";
        if(!file_exists($envPath)){
            return false;
        }
        $env = parse_ini_file($envPath);
        $mockMail = filter_var($env['MOCK_MAIL'] ?? false, FILTER_VALIDATE_BOOLEAN); // transforma true, 1, yes, on in true boolean, la fel cu false
        $htmlBody = self::getHtmlTemplate($title, $text, $buttonText, $buttonUrl);


        $mail = new PHPMailer(true);
        if($mockMail){
            try{

                //setari server din Mailtrap
                $mail->isSMTP();

                $mail->Host       = 'sandbox.smtp.mailtrap.io';
                $mail->SMTPAuth   = true;
                $mail->Username   = $env['MAILTRAP_USER'] ?? '';
                $mail->Password   = $env['MAILTRAP_PASS'] ?? '';
                $mail->Port       = 2525;

                //expeditor si destinatar
                $mail->setFrom('noreply@kim.ro', 'KIM Fitness App');
                $mail->addAddress($toEmail);

                //continut mail
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $htmlBody;

                //varianta text simplu pt clienti de mail f vechi
                $mail->AltBody = strip_tags($htmlBody);

                //trimit
                $mail->send();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        } else {
            try{
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = $env['SMTP_USER'] ?? '';
                $mail->Password   = $env['SMTP_PASS'] ?? '';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom($env['SMTP_USER'] ?? '', 'KIM Fitness App');
                $mail->addAddress($toEmail);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $htmlBody;

                //varianta text simplu pt clienti de mail f vechi
                $mail->AltBody = strip_tags($htmlBody);

                //trimit
                $mail->send();
                return true;
            } catch (\Exception $e){
                return false;
            }

        }

    }


    private static function getHtmlTemplate($title, $text, $buttonText, $buttonUrl) {

        $buttonHtml = '';
        if ($buttonText && $buttonUrl) {
            $buttonHtml = '
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 25px;">
                    <tr>
                        <td align="center">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" bgcolor="#352929" style="border-radius: 40px;">
                                        <a href="' . $buttonUrl . '" target="_blank" style="display: inline-block; padding: 14px 32px; font-family: Arial, sans-serif; font-size: 15px; color: #ffffff; text-decoration: none; border-radius: 40px; font-weight: normal;">
                                            ' . $buttonText . '
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>';
        }

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . $title . '</title>
        </head>
        <body style="margin: 0; padding: 0; background-color: #FCF9F9; font-family: Arial, sans-serif;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FCF9F9; padding: 40px 15px;">
                <tr>
                    <td align="center">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 550px; background-color: #ffffff; border-radius: 20px; overflow: hidden; border: 1px solid #F2EDED;">
                            
                            <tr>
                                <td align="center" style="padding: 35px 20px 10px 20px;">
                                    <h1 style="margin: 0; font-family: Georgia, \'Times New Roman\', serif; font-size: 26px; color: #352929; font-weight: normal;">
                                        KIM Fitness
                                    </h1>
                                </td>
                            </tr>
                            
                            <tr>
                                <td style="padding: 20px 40px 40px 40px;">
                                    <h2 style="margin: 0 0 15px 0; font-family: Georgia, \'Times New Roman\', serif; font-size: 22px; color: #352929; font-weight: normal;">
                                        ' . $title . '
                                    </h2>
                                    
                                    <div style="margin: 0; font-size: 16px; color: #666666; line-height: 1.7;">
                                        ' . $text . '
                                    </div>
                                    
                                    ' . $buttonHtml . '
                                </td>
                            </tr>
                  
                            
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>';
    }
}