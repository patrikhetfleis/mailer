<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    public function __construct(
        private readonly TransportInterface $mailer,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function sendByTemplate(Entity\Template $template, string $emailAddress): void
    {
        $this->send(
            $emailAddress,
            $template->getSubject(),
            $template->getBody()
        );
    }

    public function send(
        string $to,
        string $subject,
        string $html,
    ): void {
        $email = (new Email())
            ->from('patrik.hetfleis@gmail.com')
            ->to($to)
            ->subject($subject)
            ->html($html);

        $this->mailer->send($email);

        $emailEntity = new Entity\Email();
        $emailEntity->setRecipient($to);
        $emailEntity->setSubject($subject);
        $emailEntity->setBody($html);
        $emailEntity->setTimeCreated(new \DateTime());

        $this->entityManager->persist($emailEntity);
        $this->entityManager->flush();
    }
}
