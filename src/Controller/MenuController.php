<?php

namespace App\Controller;

use App\Entity\MenuCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menus/list', name: 'menu.index')]
    public function index(string $controller): Response
    {
        $menus = $this->getDoctrine()->getRepository(MenuCategory::class)->findBy([
            'isEnable' => true
        ]);

        return $this->render('menu/list.html.twig', [
            'controller' => $controller,
            'menus' => $menus,
        ]);
    }
}
