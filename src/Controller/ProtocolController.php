<?php

namespace App\Controller;

use App\Entity\Protocol;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProtocolController extends AbstractController
{
    #[Route('/protocols', name: 'protocol.index')]
    public function index(): Response
    {
        $protocols = $this->getDoctrine()->getRepository(Protocol::class)->findAll();

        return $this->render('protocol/index.html.twig', [
            'controller_name' => 'ProtocolController',
            'protocols' => $protocols
        ]);
    }

    public function displayProtocolOnHeader(): Response
    {
        $protocols = $this->getDoctrine()->getRepository(Protocol::class)->findAll();

        return $this->renderForm('protocol/display_protocol_on_header.html.twig', [
            'controller_name' => 'ProtocolController',
            'protocols' => $protocols,
        ]);
    }
}
