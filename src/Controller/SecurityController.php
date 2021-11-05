<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserAddType;
use App\Form\UserType;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home.index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->renderForm('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/users', name: 'user.index', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->renderForm('security/index.html.twig', [
            'controller_name' => 'SecurityController',
            'users' => $users
        ]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return Response
     */
    #[Route('/users/edit/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
    public function edit(
        User $user,
        Request $request,
        TranslatorInterface $translator
    ): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash(
                    'success',
                    $translator->trans('general.message.save')
                );

                return $this->redirectToRoute('user.index');
            } else {
                dd($form->getErrors());
            }
        }

        return $this->renderForm('security/edit.html.twig', [
            'controller_name' => 'SecurityController',
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    #[Route('/users/change-password/{id}', name: 'user.change.password', methods: ['POST'])]
    public function changePassword(
        User $user,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        TranslatorInterface $translator
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['password']) || !isset($data['confirmPassword'])) {
            return new JsonResponse([
                'status' => 'nok',
                'message' => $translator->trans('users.error.incomplete_form')
            ]);
        }

        if (trim($data['password']) !== trim($data['confirmPassword'])) {
            return new JsonResponse([
                'status' => 'nok',
                'message' => $translator->trans('users.error.no_identical')
            ]);
        }

        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'status' => 'ok',
            'message' => $translator->trans('general.message.save')
        ]);
    }

    /**
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    #[Route('/users/add', name: 'user.add', methods: ['GET', 'POST'])]
    public function add(
        Request $request,
        TranslatorInterface $translator,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $user = new User();
        $form = $this->createForm(UserAddType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));

                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash(
                    'success',
                    $translator->trans('general.message.save')
                );

                return $this->redirectToRoute('user.index');
            } else {
                dd($form->getErrors());
            }
        }

        return $this->renderForm('security/edit.html.twig', [
            'controller_name' => 'SecurityController',
            'form' => $form,
        ]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return Response
     */
    #[Route('/users/delete/{id}', name: 'user.delete', methods: ['DELETE'])]
    public function delete(User $user, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $userName = $user->getFirstname().' '.$user->getLastname();

        if (isset($data['_token']) && $this->isCsrfTokenValid('user_delete_'.$user->getId(), $data['_token'])) {
            $this->getDoctrine()->getManager()->remove($user);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse([
                'status' => 'ok',
                'message' => 'L\'utilisateur "'.$userName.'" à été supprimée.'
            ]);
        }

        return new JsonResponse([
            'status' => 'nok',
            'message' => 'Une erreur est survenue pendant la suppression de l\'utilisateur "'.$userName.'".'
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
