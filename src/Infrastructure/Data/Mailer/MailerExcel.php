<?php


namespace App\Infrastructure\Data\Mailer;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerExcel
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public  function send(array $data){
        //TODO CASE with data je besoin id unique shema vers file manager et le message  f function bideha
        $this->notfiyExportSuccess();
    }
    private function notfiyExportSuccess(){
        $message = (new Email())
            ->text("notfiyExportSuccess")
            ->subject('notfiyExportSuccess')
            ->from('haikelbrinis@gmail.com')
            ->to('haikelbrinis@gmail.com');
        $this->mailer->send($message);
    }
    private function notfiyExportError(){
        $message = (new Email())
            ->text("notfiyExportError")
            ->subject('notfiyExportError')
            ->from('haikelbrinis@gmail.com')
            ->to('haikelbrinis@gmail.com');
        $this->mailer->send($message);
    }
    private function notfiyImportSuccess(){
        $message = (new Email())
            ->text("notfiyImportSuccess")
            ->subject('notfiyImportSuccess')
            ->from('haikelbrinis@gmail.com')
            ->to('haikelbrinis@gmail.com');
        $this->mailer->send($message);
    }
    private function notfiyImportError(){
        $message = (new Email())
            ->text("notfiyImportError")
            ->subject('notfiyImportError')
            ->from('haikelbrinis@gmail.com')
            ->to('haikelbrinis@gmail.com');
        $this->mailer->send($message);
    }
}