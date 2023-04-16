<?php

namespace App\Controller;

use App\Entity;
use App\Form;
use App\Repository;
use App\Repository\TemplateRepository;
use App\Service;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/email')]
class EmailController extends AbstractController
{
    public function __construct(
        private readonly Service\Mailer $mailer,
        private readonly Repository\EmailRepository $emailRepository,
        private readonly PaginatorInterface $paginator,
    ) {
    }

    #[Route('', name: 'email_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $pagination = $this->paginator->paginate(
            $this->emailRepository->createQueryBuilder('e'),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('email/index.html.twig', [
            'emails' => $pagination->getItems(),
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'email_new')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(Form\SendMailsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addresses = explode(PHP_EOL, $form->get('addresses')->getData());
            $addresses = array_filter($addresses);
            $template = $form->get('template')->getData();

            foreach ($addresses as $address) {
                $this->mailer->sendByTemplate($template, $address);
            }

            $this->addFlash('success', 'Odesláno');
        }

        return $this->render('send.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'email_detail', methods: ['GET'])]
    public function detail(Entity\Email $email): Response
    {
        return $this->renderForm('email/detail.html.twig', [
            'email' => $email,
        ]);
    }
}
