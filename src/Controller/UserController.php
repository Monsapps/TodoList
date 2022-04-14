<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/users')]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_list')]
    public function listAction(UserService $userService)
    {
        return $this->render('user/list.html.twig', ['users' => $userService->getUsersList()]);
    }

    #[Route('/create', name: 'user_create')]
    public function createAction(Request $request, UserService $userService)
    {
        $user = $userService->createUser();

        $this->denyAccessUnlessGranted('register', $user);

        $form = $this->createForm(UserType::class, $user);

        // Only admin can create an user and add custom roles
        if($this->isGranted('register_roles', $user)) {
            $form = $this->createForm(UserType::class, $user, ['roles' => true]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userService->addUser($user);

            $this->addFlash('success', 'L\'utilisateur a bien été ajouté.');

            if($this->isGranted('manage', $user)) {
                return $this->redirectToRoute('user_list');
            }
            return $this->redirectToRoute('login');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}/edit', name: 'user_edit')]
    public function editAction(User $user, Request $request, UserService $userService)
    {
        $this->denyAccessUnlessGranted('manage', $user);

        $form = $this->createForm(UserType::class, $user, ['create' => false, 'roles' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userService->updateUser($user, $form->get('password')->getData());

            $this->addFlash('success', 'L\'utilisateur a bien été modifié');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
