<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('admin//user')]
class UserController extends AbstractController
{
//    #[Route('/', name: 'app_user_index', methods: ['GET'])]
//    public function index(UserRepository $userRepository): Response
//    {
//        return $this->render('user/index.html.twig', [
//            'users' => $userRepository->findAll(),
//        ]);
//    }

//    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, UserRepository $userRepository): Response
//    {
//        $user = new User();
//        $form = $this->createForm(UserType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $userRepository->save($user, true);
//
//            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('user/new.html.twig', [
//            'user' => $user,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}', name: 'app_user_profile', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
//        $userId = $this->getUser()->getId();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin',[], Response::HTTP_SEE_OTHER);
//            return $this->redirectToRoute('app_user_profile', ['id' => $userId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

//    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
//    public function delete(Request $request, User $user, UserRepository $userRepository): Response
//    {
//        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
//            $userRepository->remove($user, true);
//        }
//
//        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
//    }
}
