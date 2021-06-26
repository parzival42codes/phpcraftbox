<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// phpcraftbox/Library/Thrirdparty/PHPMailer/src/Exception.php
// phpcraftbox/Library/Thrirdparty/Phpmailer/src/Exception.php

require CMS_PATH_LIBRARY . 'Thrirdparty/PHPMailer/src/Exception.php';
require CMS_PATH_LIBRARY . 'Thrirdparty/PHPMailer/src/PHPMailer.php';
require CMS_PATH_LIBRARY . 'Thrirdparty/PHPMailer/src/SMTP.php';

class ContainerFactoryMail
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer          = new PHPMailer(true);
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->isSMTP();
        $this->mailer->Host      = \Config::get('/environment/email/host');
        $this->mailer->SMTPDebug = constant(\Config::get('/environment/email/debug'));
        $this->mailer->SMTPAuth  = \Config::get('/environment/email/SMTPAuth');
        $this->mailer->Username  = \Config::get('/environment/email/username');
        $this->mailer->Password  = \Config::get('/environment/email/password');
        $this->setFrom(\Config::get('/environment/email/from'),
                       \Config::get('/environment/email/fromName'));

        if (Config::get('/environment/email/SMTPSecure') == false) {
            $this->mailer->SMTPSecure = false;
        }
        else {
            $this->mailer->SMTPSecure = constant(Config::get('/environment/email/SMTPSecure'));
        }

        $this->mailer->Port = \Config::get('/environment/email/port');
        $this->mailer->isHTML(true);

    }

    public function setFrom(string $email, string $name = ''): void
    {
        $this->mailer->setFrom($email,
                               $name);
    }

    public function addAddress(string $email, string $name = ''): void
    {
        $this->mailer->addAddress($email,
                                  $name);
    }

    public function addReplyTo(string $email, string $name = ''): void
    {
        $this->mailer->addReplyTo($email,
                                  $name);
    }

    public function addCC(string $email, string $name = ''): void
    {
        $this->mailer->addCC($email,
                             $name);
    }

    public function addBCC(string $email, string $name = ''): void
    {
        $this->mailer->addBCC($email,
                              $name);
    }

    public function setSubject(string $value): void
    {
        $this->mailer->Subject = $value;
    }

    public function getSubject(): string
    {
        return $this->mailer->Subject;
    }

    public function setBody(string $value): void
    {
        $this->mailer->Body = $value;
    }

    public function getBody(): string
    {
        return $this->mailer->Body;
    }

    public function setAltBody(string $value): void
    {
        $this->mailer->AltBody = $value;
    }

    public function getAltBody(): string
    {
        return $this->mailer->AltBody;
    }

    public function send(): void
    {
        if (empty($this->mailer->AltBody)) {
            $this->mailer->AltBody = strip_tags($this->mailer->Body);
        }
        $this->mailer->send();

    }

}
