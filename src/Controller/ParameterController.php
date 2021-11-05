<?php

namespace App\Controller;

use App\Entity\Parameter;
use App\Form\ParameterCollectionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ParameterController extends AbstractController
{
    #[Route('/parameters', name: 'parameter.index', methods: ['GET', 'POST'])]
    public function index(Request $request, TranslatorInterface $translator): Response
    {
        $parameters = $this->getDoctrine()->getRepository(Parameter::class)->findBy([], [
            'type' => 'ASC',
            'subtype' => 'ASC',
            'paramOrder' => 'ASC'
        ]);

        $form = $this->createForm(ParameterCollectionFormType::class, ['parameters' => $parameters]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parameters = $form->getData();
            $restartRequired = false;

            /** @var Parameter $parameter */
            foreach ($parameters['parameters'] as $parameter) {
                $this->getDoctrine()->getManager()->persist($parameter);

                if ($parameter->getRestartRequired()) {
                    $restartRequired = true;
                }
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                $translator->trans('general.message.save')
            );

            if (true === $restartRequired) {
                $this->addFlash(
                    'warning',
                    $translator->trans('parameters.restart_required')
                );
            }

            return $this->redirectToRoute('parameter.index');
        }

        return $this->renderForm('parameter/index.html.twig', [
            'controller_name' => 'ParameterController',
            'form' => $form
        ]);
    }
}
