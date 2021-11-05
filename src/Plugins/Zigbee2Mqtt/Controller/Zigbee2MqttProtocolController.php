<?php

namespace App\Plugins\Zigbee2Mqtt\Controller;

use App\Entity\Protocol;
use App\Plugins\Zigbee2Mqtt\Form\ProtocolZigbee2MqttType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class Zigbee2MqttProtocolController extends AbstractController
{
    /**
     * @param Protocol $protocol
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return Response
     */
    #[Route('/protocols/zigbee2mqtt/edit/{id}', name: 'protocol.zigbee2mqtt.edit', methods: ['GET', 'POST'])]
    public function edit(Protocol $protocol, Request $request, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ProtocolZigbee2MqttType::class, $protocol);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($protocol);
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash(
                    'success',
                    $translator->trans('general.message.save')
                );

                return $this->redirectToRoute('protocol.zigbee2mqtt.edit', ['id' => $protocol->getId()]);
            } else {
                dd($form->getErrors());
            }
        }

        return $this->renderForm('plugins/zigbee2mqtt/protocol.edit.twig', [
            'controller_name' => 'ProtocolController',
            'protocol' => $protocol,
            'form' => $form,
        ]);
    }

    /**
     * @param Protocol $protocol
     * @param Request $request
     * @return Response
     */
    #[Route('/protocols/zigbee2mqtt/delete/{id}', name: 'protocol.zigbee2mqtt.delete', methods: ['DELETE'])]
    public function delete(Protocol $protocol, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $protocolName = $protocol->getName();

        if (isset($data['_token']) && $this->isCsrfTokenValid('protocol_delete_'.$protocol->getId(), $data['_token'])) {
            if (file_exists($this->getParameter('upload_path').'protocols/'.$protocol->getPath())) {
                @unlink($this->getParameter('upload_path').'protocols/'.$protocol->getPath());
            }

            $this->getDoctrine()->getManager()->remove($protocol);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse([
                'status' => 'ok',
                'message' => 'Le protocol "'.$protocolName.'" à été supprimé.'
            ]);
        }

        return new JsonResponse([
            'status' => 'nok',
            'message' => 'Une erreur est survenue pendant la suppression de protocol "'.$protocolName.'".'
        ]);
    }
}
