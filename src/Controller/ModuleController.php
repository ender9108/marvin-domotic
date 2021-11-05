<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ModuleController extends AbstractController
{
    #[Route('/modules', name: 'module.index', methods: ['GET'])]
    public function index(): Response
    {
        $modules = $this->getDoctrine()->getRepository(Module::class)->findAll();

        return $this->renderForm('module/index.html.twig', [
            'controller_name' => 'ModuleController',
            'modules' => $modules,
        ]);
    }

    #[Route('/modules/edit/{id}', name: 'module.edit', methods: ['GET', 'POST'])]
    public function edit(Module $module, Request $request): Response
    {
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($module);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('module.edit', ['id' => $module->getId()]);
        }

        return $this->renderForm('module/edit.html.twig', [
            'controller_name' => 'ModuleController',
            'module' => $module,
            'form' => $form
        ]);
    }

    #[Route('/modules/add', name: 'module.add', methods: ['GET', 'POST'])]
    public function add(Request $request, TranslatorInterface $translator): Response
    {

    }

    #[Route('/modules/delete/{id}', name: 'module.delete', methods: ['DELETE'])]
    public function delete(Module $module, Request $request): Response
    {

    }
}
