<?php 
    namespace Jubilant;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use Jubilant\Lang;

    class EmailSender {

        private $mailer;

        public function __construct($senderName = 'JubilantPHP EmailSender system') {
            require_once __DIR__.'/../settings.php';

            $this->mailer = new PHPMailer(true);

            $this->mailer->isSMTP();
            $this->mailer->Host = $EmailServer[0];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $EmailServer[1];
            $this->mailer->Password = $EmailServer[2];
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = $EmailServer[3];
            $this->mailer->SetLanguage("hu","php_mailer/language");
            $this->mailer->CharSet = "utf-8";

            $this->mailer->setFrom($EmailServer[4], $senderName);
        }

        public function sendMail(string $to, string $subject, string $body) {
            try {
                $this->mailer->addAddress($to);
    
                $this->mailer->isHTML(true);
                $this->mailer->Subject = $subject;
                $this->mailer->Body    = $body;
                $this->mailer->AltBody = strip_tags($body);
                $this->mailer->Subject = "=?UTF-8?B?".base64_encode($subject)."?=";
    
                $this->mailer->send();
                return Lang::trans('emailSuccessfullySent');
            } catch (Exception $e) {
                throw new Exception(Lang::trans('cantSendEmail').$this->mailer->ErrorInfo."<br>");
            }
            return true;
        }

    }
?>