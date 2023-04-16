<?php

namespace App\Controller;

use App\Entity\Template;
use App\Form\TemplateType;
use App\Repository\TemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/template')]
class TemplateController extends AbstractController
{
    public function __construct(
        private readonly TemplateRepository $templateRepository
    ) {
    }

    #[Route('', name: 'template_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('template/index.html.twig', [
            'templates' => $this->templateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'template_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $template = new Template();
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->templateRepository->add($template, true);

            return $this->redirectToRoute('template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('template/new.html.twig', [
            'template' => $template,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'template_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Template $template): Response
    {
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->templateRepository->add($template, true);

            return $this->redirectToRoute('template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('template/edit.html.twig', [
            'template' => $template,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'template_delete', methods: ['POST'])]
    public function delete(Request $request, Template $template, TemplateRepository $templateRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $template->getId(), $request->request->get('_token'))) {
            $templateRepository->remove($template, true);
        }

        return $this->redirectToRoute('template_index', [], Response::HTTP_SEE_OTHER);
    }
}
