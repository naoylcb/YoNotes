<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends AbstractController
{
    /**
     * Function that manages the login of an user
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/login', name: 'app_authentication_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Verify if an user is already logged
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('authentication/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * Function that manages the registration of an user
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route('/register', name: 'app_authentication_register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        // Verify if an user is already logged
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $password = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('info', 'Le compte a bien été créé !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('authentication/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
