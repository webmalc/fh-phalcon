<?php
namespace FH\Lib;
use Phalcon\Mvc\User\Component;

/**
 * Mail class
 */
class Mail extends Component
{
    /**
     * Mailer
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Construct method
     */
    public function __construct()
    {
        $this->setMailer();
    }

    /**
     * Set mailer
     * @return \Swift_Mailer
     */
    private function setMailer()
    {
        $config = $this->di->get('config');
        $transport = \Swift_SmtpTransport::newInstance(
            $config->smtp->host,
            $config->smtp->port,
            $config->smtp->security
        );
        $transport->setUsername($config->smtp->username)
            ->setPassword($config->smtp->password)
        ;
        $this->mailer = \Swift_Mailer::newInstance($transport);
        return $this->mailer;
    }
    /**
     * Send email
     * @param string $email
     * @param string $text
     * @param string|null $subject
     * @return integer
     */
    public function send($email, $text, $subject = null)
    {
        $config = $this->di->get('config');;
        if (empty($subject)) {
            $subject = $config->mail->subject;
        }
        $body = $this->di->get('view')->getRender('mail', 'base', ['content' => $text, 'subject' => $subject]);
        $message = \Swift_Message::newInstance($subject)
            ->setFrom($config->mail->fromEmail, $config->mail->fromName)
            ->setTo($email)
            ->setBody($body, 'text/html')
        ;
        return $this->mailer->send($message);
    }
}