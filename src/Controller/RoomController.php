<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RoomController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/rooms', name: 'room.index', methods: ['GET'])]
    public function index(): Response
    {
        $rooms = $this->getDoctrine()->getRepository(Room::class)->findAll();
        $countRooms = $this->getDoctrine()->getRepository(Room::class)->count([]);

        return $this->render('room/index.html.twig', [
            'controller_name' => 'RoomController',
            'menu' => 'room',
            'rooms' => $rooms,
            'countRooms' => $countRooms
        ]);
    }

    /**
     * @param Room $room
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return Response
     */
    #[Route('/rooms/edit/{id}', name: 'room.edit', methods: ['GET', 'POST'])]
    public function edit(Room $room, Request $request, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($room);
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash(
                    'success',
                    $translator->trans('general.message.save')
                );

                return $this->redirectToRoute('room.edit', ['id' => $room->getId()]);
            } else {
                dd($form->getErrors());
            }
        }

        return $this->renderForm('room/edit.html.twig', [
            'controller_name' => 'RoomController',
            'room' => $room,
            'form' => $form,
        ]);
    }

    /**
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return Response
     */
    #[Route('/rooms/add', name: 'room.add', methods: ['GET', 'POST'])]
    public function add(Request $request, TranslatorInterface $translator): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($room);
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash(
                    'success',
                    $translator->trans('general.message.save')
                );

                return $this->redirectToRoute('room.edit', ['id' => $room->getId()]);
            } else {
                dd($form->getErrors());
            }
        }

        return $this->renderForm('room/edit.html.twig', [
            'controller_name' => 'RoomController',
            'form' => $form,
        ]);
    }

    /**
     * @param Room $room
     * @param Request $request
     * @return Response
     */
    #[Route('/rooms/delete/{id}', name: 'room.delete', methods: ['DELETE'])]
    public function delete(Room $room, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $roomName = $room->getName();

        if (isset($data['_token']) && $this->isCsrfTokenValid('room_delete_'.$room->getId(), $data['_token'])) {
            if (file_exists($this->getParameter('upload_path').'rooms/'.$room->getPath())) {
                @unlink($this->getParameter('upload_path').'rooms/'.$room->getPath());
            }

            $this->getDoctrine()->getManager()->remove($room);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse([
                'status' => 'ok',
                'message' => 'La pièce "'.$roomName.'" à été supprimée.'
            ]);
        }

        return new JsonResponse([
            'status' => 'nok',
            'message' => 'Une erreur est survenue pendant la suppression de la pièce "'.$roomName.'".'
        ]);
    }
}
